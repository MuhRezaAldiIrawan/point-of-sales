<?php

namespace App\Http\Controllers\Inventori;

use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

use App\Models\BarangMasuk;
use App\Models\DetailBarangMasuk;
use App\Models\JenisStok;
use App\Models\Barang;
use App\Models\Satuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BarangMasukController extends Controller
{
    public function getBarangs(Request $request)
    {
        $search = $request->get('q');
        $page = $request->get('page', 1);
        $perPage = 15;

        $query = Barang::with(['detailBarang.satuan']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_barang', 'like', "%{$search}%")
                  ->orWhere('kode', 'like', "%{$search}%");
            });
        }

        $total = $query->count();
        $barangs = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

        $results = $barangs->map(function ($barang) {
            $satuans = $barang->detailBarang
                ->filter(fn($detail) => !empty($detail->satuan))
                ->unique('satuan_id')
                ->map(fn($detail) => [
                    'id' => $detail->satuan_id,
                    'nama' => $detail->satuan->nama,
                ])
                ->values();

            return [
                'id' => $barang->id,
                'text' => $barang->nama_barang . ' - ' . $barang->kode,
                'kode' => $barang->kode,
                'nama_barang' => $barang->nama_barang,
                'satuans' => $satuans,
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => ['more' => ($page * $perPage) < $total],
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Barang Masuk';

        if ($request->ajax()) {

            $data = BarangMasuk::with(['jenisStok', 'detailBarangMasuk'])->orderBy('created_at', 'desc')->get();


            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jenis_stok', function ($row) {
                    return $row->jenisStok ? $row->jenisStok->nama : '-';
                })
                ->addColumn('tanggal_masuk', function ($row) {
                    return $row->tanggal_masuk ? $row->tanggal_masuk->format('d-m-Y') : '-';
                })
                ->addColumn('jumlah', function ($row) {
                    return $row->detailBarangMasuk->sum('jumlah');
                })
                ->addColumn('total', function ($row) {
                    return 'Rp ' . number_format($row->detailBarangMasuk->sum('total'), 0, ',', '.');
                })
                ->addColumn('catatan', function ($row) {
                    return $row->catatan ? Str::limit($row->catatan, 50) : '-';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group" role="group">';

                    // View button
                    $btn .= '<button class="btn btn-sm btn-info btn-barangmasuk-detail" data-id="' . $row->id . '" title="Lihat Detail">
                                <i class="ft-eye"></i>
                             </button>';

                    // Edit button
                    $btn .= '<button class="btn btn-sm btn-warning btn-barangmasuk-edit" data-id="' . $row->id . '" title="Edit">
                                <i class="ft-edit"></i>
                             </button>';

                    // Delete button
                    $btn .= '<button class="btn btn-sm btn-danger btn-barangmasuk-delete" data-id="' . $row->id . '" title="Hapus">
                                <i class="ft-trash"></i>
                             </button>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.inventori.barangmasuk.barangmasuk', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Barang Masuk';

        $jenisStoks = JenisStok::all();
        $satuans = Satuan::all();

        return view('pages.inventori.barangmasuk._partials.form', compact('title', 'jenisStoks', 'satuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'no_reff' => 'required|string|max:255|unique:barang_masuks,no_reff',
                'tanggal' => 'required|date',
                'jenis_stok_id' => 'required|exists:jenis_stoks,id',
                'catatan' => 'nullable|string',
                'detail_items' => 'required|json',
            ], [
                'no_reff.required' => 'No. Referensi wajib diisi',
                'no_reff.unique' => 'No. Referensi sudah digunakan',
                'tanggal.required' => 'Tanggal wajib diisi',
                'jenis_stok_id.required' => 'Jenis Stok wajib dipilih',
                'jenis_stok_id.exists' => 'Jenis Stok tidak valid',
                'detail_items.required' => 'Detail barang wajib diisi',
            ]);

            $detailItems = json_decode($request->detail_items, true);

            if (empty($detailItems)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Detail barang tidak boleh kosong'
                ], 422);
            }

            DB::beginTransaction();

            $barangMasuk = BarangMasuk::create([
                'no_reff' => $request->no_reff,
                'tanggal_masuk' => $request->tanggal,
                'jenis_stok_id' => $request->jenis_stok_id,
                'catatan' => $request->catatan,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            foreach ($detailItems as $item) {
                if (empty($item['barang_id']) || empty($item['satuan']) ||
                    $item['jumlah'] <= 0 || $item['harga_beli'] <= 0) {
                    throw new \Exception('Data detail barang tidak lengkap atau tidak valid');
                }

                $satuan = Satuan::where('id', $item['satuan'])->first();
                if (!$satuan) {
                    throw new \Exception('Satuan "' . $item['satuan'] . '" tidak ditemukan');
                }

                $stokPpn = $item['ppn'] == '1' ? 'PPN' : 'Non PPN';

                DetailBarangMasuk::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'stok_ppn' => $stokPpn,
                    'barang_id' => $item['barang_id'],
                    'satuan_id' => $satuan->id,
                    'isi' => $item['isi'],
                    'harga_beli' => $item['harga_beli'],
                    'jumlah' => $item['jumlah'],
                    'total' => $item['total'],
                    'keterangan' => $item['keterangan'],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data barang masuk berhasil disimpan dengan ' . count($detailItems) . ' item',
                'data' => [
                    'id' => $barangMasuk->id,
                    'no_reff' => $barangMasuk->no_reff,
                    'total_items' => count($detailItems)
                ],
                'redirect' => route('barangmasuk.index')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Data yang dimasukkan tidak valid',
                'errors' => $e->errors(),
                'error_type' => 'validation'
            ], 422);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error saving Barang Masuk: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'error_type' => 'server_error'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $barangMasuk = BarangMasuk::with([
                'detailBarangMasuk.barang',
                'detailBarangMasuk.satuan',
                'jenisStok',
                'createdBy',
                'updatedBy'
            ])->findOrFail($id);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $barangMasuk->id,
                        'no_reff' => $barangMasuk->no_reff,
                        'tanggal_masuk' => $barangMasuk->tanggal_masuk->format('d-m-Y'),
                        'jenis_stok' => $barangMasuk->jenisStok->nama ?? '-',
                        'catatan' => $barangMasuk->catatan ?? '-',
                        'created_by' => $barangMasuk->createdBy->name ?? '-',
                        'updated_by' => $barangMasuk->updatedBy->name ?? '-',
                        'created_at' => $barangMasuk->created_at->format('d-m-Y H:i:s'),
                        'updated_at' => $barangMasuk->updated_at->format('d-m-Y H:i:s'),
                        'details' => $barangMasuk->detailBarangMasuk->map(function($detail) {
                            return [
                                'id' => $detail->id,
                                'kode_barang' => $detail->barang->kode ?? '-',
                                'nama_barang' => $detail->barang->nama ?? '-',
                                'stok_ppn' => $detail->stok_ppn,
                                'satuan' => $detail->satuan->nama ?? '-',
                                'isi' => $detail->isi,
                                'harga_beli' => $detail->harga_beli,
                                'jumlah' => $detail->jumlah,
                                'total' => $detail->total,
                                'keterangan' => $detail->keterangan ?? '-'
                            ];
                        }),
                        'total_items' => $barangMasuk->detailBarangMasuk->count(),
                        'total_jumlah' => $barangMasuk->detailBarangMasuk->sum('jumlah'),
                        'grand_total' => $barangMasuk->detailBarangMasuk->sum('total')
                    ]
                ]);
            }

            $title = 'Detail Barang Masuk';
            return view('pages.inventori.barangmasuk._partials.detail', compact('title', 'barangMasuk'));

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data barang masuk tidak ditemukan'
                ], 404);
            }

            return redirect()->route('barangmasuk.index')
                ->with('error', 'Data barang masuk tidak ditemukan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $title = 'Edit Barang Masuk';

            $barangMasuk = BarangMasuk::with(['detailBarangMasuk.barang', 'detailBarangMasuk.satuan'])
                ->findOrFail($id);

            $jenisStoks = JenisStok::all();
            $satuans = Satuan::all();

            return view('pages.inventori.barangmasuk._partials.form-edit', compact(
                'title',
                'barangMasuk',
                'jenisStoks',
                'satuans'
            ));

        } catch (\Exception $e) {
            return redirect()->route('barangmasuk.index')
                ->with('error', 'Data barang masuk tidak ditemukan');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            // Validasi input
            $request->validate([
                'no_reff' => 'required|string|max:255|unique:barang_masuks,no_reff,' . $id,
                'tanggal' => 'required|date',
                'jenis_stok_id' => 'required|exists:jenis_stoks,id',
                'catatan' => 'nullable|string',
                'detail_items' => 'required|json',
            ], [
                'no_reff.required' => 'No. Referensi wajib diisi',
                'no_reff.unique' => 'No. Referensi sudah digunakan',
                'tanggal.required' => 'Tanggal wajib diisi',
                'jenis_stok_id.required' => 'Jenis Stok wajib dipilih',
                'jenis_stok_id.exists' => 'Jenis Stok tidak valid',
                'detail_items.required' => 'Detail barang wajib diisi',
            ]);

            $detailItems = json_decode($request->detail_items, true);

            if (empty($detailItems)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Detail barang tidak boleh kosong'
                ], 422);
            }

            DB::beginTransaction();

            $barangMasuk = BarangMasuk::findOrFail($id);

            $barangMasuk->update([
                'no_reff' => $request->no_reff,
                'tanggal_masuk' => $request->tanggal,
                'jenis_stok_id' => $request->jenis_stok_id,
                'catatan' => $request->catatan,
                'updated_by' => Auth::id(),
            ]);

            $barangMasuk->detailBarangMasuk()->delete();

            foreach ($detailItems as $item) {
                // Validasi setiap item detail
                if (empty($item['barang_id']) || empty($item['satuan']) ||
                    $item['jumlah'] <= 0 || $item['harga_beli'] <= 0) {
                    throw new \Exception('Data detail barang tidak lengkap atau tidak valid');
                }

                $satuan = Satuan::where('id', $item['satuan'])->first();
                if (!$satuan) {
                    throw new \Exception('Satuan "' . $item['satuan'] . '" tidak ditemukan');
                }

                $stokPpn = $item['ppn'] == '1' ? 'PPN' : 'Non PPN';

                DetailBarangMasuk::create([
                    'barang_masuk_id' => $barangMasuk->id,
                    'stok_ppn' => $stokPpn,
                    'barang_id' => $item['barang_id'],
                    'satuan_id' => $satuan->id,
                    'isi' => $item['isi'],
                    'harga_beli' => $item['harga_beli'],
                    'jumlah' => $item['jumlah'],
                    'total' => $item['total'],
                    'keterangan' => $item['keterangan'],
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data barang masuk berhasil diperbarui dengan ' . count($detailItems) . ' item',
                'data' => [
                    'id' => $barangMasuk->id,
                    'no_reff' => $barangMasuk->no_reff,
                    'total_items' => count($detailItems)
                ],
                'redirect' => route('barangmasuk.index')
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollback();

            return response()->json([
                'success' => false,
                'message' => 'Data yang dimasukkan tidak valid',
                'errors' => $e->errors(),
                'error_type' => 'validation'
            ], 422);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error updating Barang Masuk: ' . $e->getMessage(), [
                'id' => $id,
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage(),
                'error_type' => 'server_error'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $barangMasuk = BarangMasuk::findOrFail($id);

            DB::beginTransaction();

            // Soft delete detail barang masuk
            $barangMasuk->detailBarangMasuk()->delete();

            // Soft delete barang masuk
            $barangMasuk->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data barang masuk berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error deleting Barang Masuk: ' . $e->getMessage(), [
                'id' => $id,
                'user_id' => Auth::id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }
}
