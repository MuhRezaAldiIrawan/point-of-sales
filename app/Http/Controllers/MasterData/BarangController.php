<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Barang;
use App\Models\DetailBarang;
use App\Models\JenisBarang;
use App\Models\Satuan;
use App\Models\Merek;
use App\Models\Supplier;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Barang';

        if ($request->ajax()) {

            $data = Barang::with('merek', 'jenisBarang', 'supplier')->orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('foto', function ($row) {
                    if ($row->foto) {
                        return '<img src="' . Storage::url($row->foto) . '" alt="Foto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">';
                    }
                    return '<div style="width: 50px; height: 50px; background: #f1f1f1; border-radius: 8px; display: flex; align-items: center; justify-content: center;"><i class="ft-image" style="color: #ccc;"></i></div>';
                })
                ->addColumn('jenis_barang', function ($row) {
                    return $row->jenisBarang ? $row->jenisBarang->nama : '-';
                })
                ->addColumn('merek', function ($row) {
                    return $row->merek ? $row->merek->nama : '-';
                })
                ->addColumn('supplier', function ($row) {
                    return $row->supplier ? $row->supplier->nama : '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('barang.edit', $row->id);
                    $btn =
                        '
                            <a href="' . $editUrl . '" class="btn btn-icon btn-primary" title="Edit">
                                <i class="ft-edit"></i>
                            </a>

                            <button class="btn btn-icon btn-danger btn-barang-delete" data-id="' . $row->id . '" type="button" role="button" title="Hapus">
                                <i class="ft-trash"></i>
                            </button>
                            ';
                    return $btn;
                })
                ->rawColumns(['action', 'foto'])
                ->make(true);
        }

        return view('pages.masterdata.barang.barang', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Barang';

        // generate kode barang terbaru
        $baseTimestamp = now();
        $kodeBarang = 'BRG-' . $baseTimestamp->copy()->addMinutes(1)->format('ymdHis');

        return view('pages.masterdata.barang._partials.form', compact('title', 'kodeBarang'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'kode' => 'required|string|max:50|unique:barangs,kode',
                'nama_barang' => 'required|string|max:255',
                'jenis_barang_id' => 'required|exists:jenis_barangs,id',
                'merek_id' => 'required|exists:mereks,id',
                'supplier_id' => 'required|exists:suppliers,id',
                'keterangan' => 'nullable|string',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'detail_barang' => 'required|json',
            ], [
                'kode.required' => 'Kode barang harus diisi',
                'kode.unique' => 'Kode barang sudah digunakan',
                'nama_barang.required' => 'Nama barang harus diisi',
                'jenis_barang_id.required' => 'Jenis barang harus dipilih',
                'merek_id.required' => 'Merek harus dipilih',
                'supplier_id.required' => 'Supplier harus dipilih',
                'foto.image' => 'File harus berupa gambar',
                'foto.max' => 'Ukuran foto maksimal 2MB',
                'detail_barang.required' => 'Detail barang harus diisi minimal 1',
            ]);

            $detailBarang = json_decode($request->detail_barang, true);

            if (empty($detailBarang)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Detail barang harus diisi minimal 1'
                ], 422);
            }

            DB::beginTransaction();

            $fotoPath = null;
            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $fileName = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $fotoPath = $foto->storeAs('barang', $fileName, 'public');
            }

            $barang = Barang::create([
                'kode' => $validated['kode'],
                'nama_barang' => $validated['nama_barang'],
                'jenis_barang_id' => $validated['jenis_barang_id'],
                'merek_id' => $validated['merek_id'],
                'supplier_id' => $validated['supplier_id'],
                'keterangan' => $validated['keterangan'] ?? null,
                'foto' => $fotoPath,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            foreach ($detailBarang as $detail) {
                DetailBarang::create([
                    'barang_id' => $barang->id,
                    'satuan_id' => $detail['satuan_id'],
                    'isi' => $detail['isi'],
                    'harga_beli' => $detail['harga_beli'],
                    'harga_jual' => $detail['harga_jual'],
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data barang berhasil disimpan!',
                'data' => $barang
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            if (isset($fotoPath) && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Edit Barang';

        $barang = Barang::with('detailBarang.satuan')->findOrFail($id);

        return view('pages.masterdata.barang._partials.form', compact(
            'title',
            'barang'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $barang = Barang::findOrFail($id);

            $validated = $request->validate([
                'kode' => 'required|string|max:50|unique:barangs,kode,' . $id,
                'nama_barang' => 'required|string|max:255',
                'jenis_barang_id' => 'required|exists:jenis_barangs,id',
                'merek_id' => 'required|exists:mereks,id',
                'supplier_id' => 'required|exists:suppliers,id',
                'keterangan' => 'nullable|string',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'detail_barang' => 'required|json',
            ], [
                'kode.required' => 'Kode barang harus diisi',
                'kode.unique' => 'Kode barang sudah digunakan',
                'nama_barang.required' => 'Nama barang harus diisi',
                'jenis_barang_id.required' => 'Jenis barang harus dipilih',
                'merek_id.required' => 'Merek harus dipilih',
                'supplier_id.required' => 'Supplier harus dipilih',
                'foto.image' => 'File harus berupa gambar',
                'foto.max' => 'Ukuran foto maksimal 2MB',
                'detail_barang.required' => 'Detail barang harus diisi minimal 1',
            ]);

            $detailBarang = json_decode($request->detail_barang, true);

            if (empty($detailBarang)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Detail barang harus diisi minimal 1'
                ], 422);
            }

            DB::beginTransaction();

            $fotoPath = $barang->foto;
            if ($request->hasFile('foto')) {
                if ($barang->foto && Storage::disk('public')->exists($barang->foto)) {
                    Storage::disk('public')->delete($barang->foto);
                }

                $foto = $request->file('foto');
                $fileName = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $fotoPath = $foto->storeAs('barang', $fileName, 'public');
            }

            $barang->update([
                'kode' => $validated['kode'],
                'nama_barang' => $validated['nama_barang'],
                'jenis_barang_id' => $validated['jenis_barang_id'],
                'merek_id' => $validated['merek_id'],
                'supplier_id' => $validated['supplier_id'],
                'keterangan' => $validated['keterangan'] ?? null,
                'foto' => $fotoPath,
                'updated_by' => auth()->user()->id
            ]);

            DetailBarang::where('barang_id', $barang->id)->delete();

            foreach ($detailBarang as $detail) {
                DetailBarang::create([
                    'barang_id' => $barang->id,
                    'satuan_id' => $detail['satuan_id'],
                    'isi' => $detail['isi'],
                    'harga_beli' => $detail['harga_beli'],
                    'harga_jual' => $detail['harga_jual'],
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data barang berhasil diupdate!',
                'data' => $barang
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $barang = Barang::findOrFail($id);

            DB::beginTransaction();

            DetailBarang::where('barang_id', $barang->id)->delete();

            if ($barang->foto && Storage::disk('public')->exists($barang->foto)) {
                Storage::disk('public')->delete($barang->foto);
            }

            $barang->update([
                'updated_by' => auth()->user()->id
            ]);

            DetailBarang::where('barang_id', $barang->id)->update([
                'updated_by' => auth()->user()->id
            ]);

            $barang->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data barang berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get suppliers for select2 remote data loading
     */
    public function getSuppliers(Request $request)
    {
        $search = $request->get('q', '');
        $page = $request->get('page', 1);
        $perPage = 10;

        $query = Supplier::query();

        if (!empty($search)) {
            $query->where('nama', 'LIKE', '%' . $search . '%');
        }

        $suppliers = $query->orderBy('nama')
            ->paginate($perPage, ['*'], 'page', $page);

        $results = $suppliers->map(function ($supplier) {
            return [
                'id' => $supplier->id,
                'text' => $supplier->nama
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $suppliers->hasMorePages()
            ]
        ]);
    }

    /**
     * Get jenis barang for select2 remote data loading
     */
    public function getJenisBarang(Request $request)
    {
        $search = $request->get('q', '');
        $page = $request->get('page', 1);
        $perPage = 10;

        $query = JenisBarang::query();

        if (!empty($search)) {
            $query->where('nama', 'LIKE', '%' . $search . '%');
        }

        $jenisBarang = $query->orderBy('nama')
            ->paginate($perPage, ['*'], 'page', $page);

        $results = $jenisBarang->map(function ($jenis) {
            return [
                'id' => $jenis->id,
                'text' => $jenis->nama
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $jenisBarang->hasMorePages()
            ]
        ]);
    }

    /**
     * Get merek for select2 remote data loading
     */
    public function getMerek(Request $request)
    {
        $search = $request->get('q', '');
        $page = $request->get('page', 1);
        $perPage = 10;

        $query = Merek::query();

        if (!empty($search)) {
            $query->where('nama', 'LIKE', '%' . $search . '%');
        }

        $merek = $query->orderBy('nama')
            ->paginate($perPage, ['*'], 'page', $page);

        $results = $merek->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nama
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $merek->hasMorePages()
            ]
        ]);
    }

    /**
     * Get satuan for select2 remote data loading
     */
    public function getSatuan(Request $request)
    {
        $search = $request->get('q', '');
        $page = $request->get('page', 1);
        $perPage = 10;

        $query = Satuan::query();

        if (!empty($search)) {
            $query->where('nama', 'LIKE', '%' . $search . '%');
        }

        $satuan = $query->orderBy('nama')
            ->paginate($perPage, ['*'], 'page', $page);

        $results = $satuan->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->nama
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $satuan->hasMorePages()
            ]
        ]);
    }

    /**
     * Search barang for AJAX autocomplete
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $barangs = Barang::with('detailBarang')
            ->where('nama_barang', 'LIKE', '%' . $query . '%')
            ->orWhere('kode', 'LIKE', '%' . $query . '%')
            ->limit(10)
            ->get(['id', 'nama_barang', 'kode']);

        $results = $barangs->map(function ($barang) {
            $hargaJual = $barang->detailBarang->first()?->harga_jual ?? 0;
            return [
                'id' => $barang->id,
                'nama' => $barang->nama_barang,
                'kode' => $barang->kode,
                'harga_jual' => $hargaJual
            ];
        });

        return response()->json($results);
    }
}
