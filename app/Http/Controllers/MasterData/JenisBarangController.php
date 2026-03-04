<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

use App\Models\JenisBarang;


class JenisBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Jenis Barang';

        if ($request->ajax()) {

            $data = JenisBarang::orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn =
                        '
                            <button class="btn btn-icon btn-primary btn-jenis-barang-edit" data-id="' . $row->id . '" type="button" role="button">
                                <i class="ft-edit"></i>
                            </button>

                            <button class="btn btn-icon btn-danger btn-jenis-barang-delete" data-id="' . $row->id . '" type="button" role="button">
                                <i class="ft-trash"></i>
                            </button>
                            ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('pages.masterdata.jenisbarang.jenisbarang', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'kode' => 'nullable|string'
        ]);

        try {
            $jenisBarang = JenisBarang::create([
                'nama' => $request->nama,
                'kode' => $request->kode,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jenis Barang berhasil ditambahkan',
                'data' => $jenisBarang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan jenis barang: ' . $e->getMessage()
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
        try {
            $jenisBarang = JenisBarang::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $jenisBarang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data jenis barang: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string'
        ]);

        try {
            $jenisBarang = JenisBarang::findOrFail($id);
            $jenisBarang->update([
                'nama' => $request->nama,
                'kode' => $request->kode,
                'updated_by' => auth()->user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jenis Barang berhasil diupdate',
                'data' => $jenisBarang
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate jenis barang: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $jenisBarang = JenisBarang::findOrFail($id);
            $jenisBarang->updated_by = auth()->user()->id;
            $jenisBarang->delete();

            return response()->json([
                'success' => true,
                'message' => 'Jenis Barang berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus jenis barang: ' . $e->getMessage()
            ], 500);
        }
    }
}
