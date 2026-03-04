<?php

namespace App\Http\Controllers\Inventori;

use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

use App\Models\BarangKeluar;
use App\Models\DetailBarangKeluar;
use App\Models\JenisStok;
use App\Models\Barang;
use App\Models\Satuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Barang Keluar';

        if ($request->ajax()) {

            $data = BarangKeluar::with(['jenisStok', 'detailBarangKeluar'])->orderBy('created_at', 'desc')->get();


            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('jenis_stok', function ($row) {
                    return $row->jenisStok ? $row->jenisStok->nama : '-';
                })
                ->addColumn('tanggal_keluar', function ($row) {
                    return $row->tanggal_keluar ? $row->tanggal_keluar->format('d-m-Y') : '-';
                })
                ->addColumn('jumlah', function ($row) {
                    return $row->detailBarangKeluar->sum('jumlah');
                })
                ->addColumn('total', function ($row) {
                    return 'Rp ' . number_format($row->detailBarangKeluar->sum('total'), 0, ',', '.');
                })
                ->addColumn('catatan', function ($row) {
                    return $row->catatan ? Str::limit($row->catatan, 50) : '-';
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group" role="group">';

                    $btn .= '<button class="btn btn-sm btn-info btn-barangkeluar-detail" data-id="' . $row->id . '" title="Lihat Detail">
                                <i class="ft-eye"></i>
                             </button>';

                    $btn .= '<button class="btn btn-sm btn-warning btn-barangkeluar-edit" data-id="' . $row->id . '" title="Edit">
                                <i class="ft-edit"></i>
                             </button>';

                    $btn .= '<button class="btn btn-sm btn-danger btn-barangkeluar-delete" data-id="' . $row->id . '" title="Hapus">
                                <i class="ft-trash"></i>
                             </button>';

                    $btn .= '</div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.inventori.barangkeluar.barangkeluar', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Barang Keluar';

        $jenisStoks = JenisStok::all();
        $barangs = Barang::all();
        $satuans = Satuan::all();

        return view('pages.inventori.barangkeluar._partials.form', compact('title', 'jenisStoks', 'barangs', 'satuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'no_reff' => 'required|string|max:255|unique:barang_keluars,no_reff',
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

            $barangKeluar = BarangKeluar::create([
                'no_reff' => $request->no_reff,
                'tanggal_keluar' => $request->tanggal,
                'jenis_stok_id' => $request->jenis_stok_id,
                'catatan' => $request->catatan,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            foreach ($detailItems as $item) {
                if (empty($item['barang_id']) || empty($item['satuan']) ||
                    $item['jumlah'] <= 0 || $item['harga_jual'] <= 0) {
                    throw new \Exception('Data detail barang tidak lengkap atau tidak valid');
                }

                $satuan = Satuan::where('id', $item['satuan'])->first();
                if (!$satuan) {
                    throw new \Exception('Satuan "' . $item['satuan'] . '" tidak ditemukan');
                }

                $stokPpn = $item['ppn'] == '1' ? 'PPN' : 'Non PPN';

                DetailBarangKeluar::create([
                    'barang_keluar_id' => $barangKeluar->id,
                    'stok_ppn' => $stokPpn,
                    'barang_id' => $item['barang_id'],
                    'satuan_id' => $satuan->id,
                    'isi' => $item['isi'],
                    'harga_jual' => $item['harga_jual'],
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
                'message' => 'Data barang keluar berhasil disimpan dengan ' . count($detailItems) . ' item',
                'data' => [
                    'id' => $barangKeluar->id,
                    'no_reff' => $barangKeluar->no_reff,
                    'total_items' => count($detailItems)
                ],
                'redirect' => route('barangkeluar.index')
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

            Log::error('Error saving Barang Keluar: ' . $e->getMessage(), [
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
            $barangKeluar = BarangKeluar::with([
                'detailBarangKeluar.barang',
                'detailBarangKeluar.satuan',
                'jenisStok',
                'createdBy',
                'updatedBy'
            ])->findOrFail($id);

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => [
                        'id' => $barangKeluar->id,
                        'no_reff' => $barangKeluar->no_reff,
                        'tanggal_keluar' => $barangKeluar->tanggal_keluar->format('d-m-Y'),
                        'jenis_stok' => $barangKeluar->jenisStok->nama ?? '-',
                        'catatan' => $barangKeluar->catatan ?? '-',
                        'created_by' => $barangKeluar->createdBy->name ?? '-',
                        'updated_by' => $barangKeluar->updatedBy->name ?? '-',
                        'created_at' => $barangKeluar->created_at->format('d-m-Y H:i:s'),
                        'updated_at' => $barangKeluar->updated_at->format('d-m-Y H:i:s'),
                        'details' => $barangKeluar->detailBarangKeluar->map(function($detail) {
                            return [
                                'id' => $detail->id,
                                'kode_barang' => $detail->barang->kode ?? '-',
                                'nama_barang' => $detail->barang->nama_barang ?? '-',
                                'stok_ppn' => $detail->stok_ppn,
                                'satuan' => $detail->satuan->nama ?? '-',
                                'isi' => $detail->isi,
                                'harga_jual' => $detail->harga_jual,
                                'jumlah' => $detail->jumlah,
                                'total' => $detail->total,
                                'keterangan' => $detail->keterangan ?? '-'
                            ];
                        }),
                        'total_items' => $barangKeluar->detailBarangKeluar->count(),
                        'total_jumlah' => $barangKeluar->detailBarangKeluar->sum('jumlah'),
                        'grand_total' => $barangKeluar->detailBarangKeluar->sum('total')
                    ]
                ]);
            }

            $title = 'Detail Barang Keluar';
            return view('pages.inventori.barangkeluar._partials.detail', compact('title', 'barangKeluar'));

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data barang keluar tidak ditemukan'
                ], 404);
            }

            return redirect()->route('barangkeluar.index')
                ->with('error', 'Data barang keluar tidak ditemukan');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $title = 'Edit Barang Keluar';

            Log::info('Editing Barang Keluar', ['id' => $id]);

            $barangKeluar = BarangKeluar::with(['detailBarangKeluar.barang', 'detailBarangKeluar.satuan'])
                ->findOrFail($id);

            Log::info('Barang Keluar found', [
                'id' => $barangKeluar->id,
                'no_reff' => $barangKeluar->no_reff,
                'details_count' => $barangKeluar->detailBarangKeluar->count()
            ]);

            $jenisStoks = JenisStok::all();
            $barangs = Barang::all();
            $satuans = Satuan::all();

            return view('pages.inventori.barangkeluar._partials.form-edit', compact(
                'title',
                'barangKeluar',
                'jenisStoks',
                'barangs',
                'satuans'
            ));

        } catch (\Exception $e) {
            Log::error('Error editing Barang Keluar: ' . $e->getMessage(), [
                'id' => $id,
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('barangkeluar.index')
                ->with('error', 'Data barang keluar tidak ditemukan: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {

            $request->validate([
                'no_reff' => 'required|string|max:255|unique:barang_keluars,no_reff,' . $id,
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

            $barangKeluar = BarangKeluar::findOrFail($id);

            $barangKeluar->update([
                'no_reff' => $request->no_reff,
                'tanggal_keluar' => $request->tanggal,
                'jenis_stok_id' => $request->jenis_stok_id,
                'catatan' => $request->catatan,
                'updated_by' => Auth::id(),
            ]);

            $barangKeluar->detailBarangKeluar()->delete();

            foreach ($detailItems as $item) {
                if (empty($item['barang_id']) || empty($item['satuan']) ||
                    $item['jumlah'] <= 0 || $item['harga_jual'] <= 0) {
                    throw new \Exception('Data detail barang tidak lengkap atau tidak valid');
                }

                $satuan = Satuan::where('id', $item['satuan'])->first();
                if (!$satuan) {
                    throw new \Exception('Satuan "' . $item['satuan'] . '" tidak ditemukan');
                }

                $stokPpn = $item['ppn'] == '1' ? 'PPN' : 'Non PPN';

                DetailBarangKeluar::create([
                    'barang_keluar_id' => $barangKeluar->id,
                    'stok_ppn' => $stokPpn,
                    'barang_id' => $item['barang_id'],
                    'satuan_id' => $satuan->id,
                    'isi' => $item['isi'],
                    'harga_jual' => $item['harga_jual'],
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
                'message' => 'Data barang keluar berhasil diperbarui dengan ' . count($detailItems) . ' item',
                'data' => [
                    'id' => $barangKeluar->id,
                    'no_reff' => $barangKeluar->no_reff,
                    'total_items' => count($detailItems)
                ],
                'redirect' => route('barangkeluar.index')
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

            Log::error('Error updating Barang Keluar: ' . $e->getMessage(), [
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
            $barangKeluar = BarangKeluar::findOrFail($id);

            DB::beginTransaction();

            $barangKeluar->detailBarangKeluar()->delete();

            $barangKeluar->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data barang keluar berhasil dihapus'
            ]);

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error deleting Barang Keluar: ' . $e->getMessage(), [
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
