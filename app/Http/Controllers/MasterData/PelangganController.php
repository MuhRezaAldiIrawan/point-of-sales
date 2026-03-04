<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

use App\Models\Pelanggan;

class PelangganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Pelanggan';

        if ($request->ajax()) {

            $data = Pelanggan::orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn =
                        '
                            <button class="btn btn-icon btn-primary btn-pelanggan-edit" data-id="' . $row->id . '" type="button" role="button">
                                <i class="ft-edit"></i>
                            </button>

                            <button class="btn btn-icon btn-danger btn-pelanggan-delete" data-id="' . $row->id . '" type="button" role="button">
                                <i class="ft-trash"></i>
                            </button>
                            ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.masterdata.pelanggan.pelanggan', compact('title'));
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

        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'alamat' => 'nullable|string',
                'no_hp' => 'nullable|string|max:20',
                'kota' => 'nullable|string|max:100',
                'status_bayar' => 'nullable|in:Tunai,Kredit',
                'batas_kredit' => 'nullable|numeric|min:0',
                'keterangan' => 'nullable|string',
            ]);
            $pelanggan = Pelanggan::create([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'kota' => $request->kota,
                'status_bayar' => $request->status_bayar,
                'batas_kredit' => $request->batas_kredit,
                'keterangan' => $request->keterangan,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pelanggan berhasil ditambahkan',
                'data' => $pelanggan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan pelanggan: ' . $e->getMessage()
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
            $pelanggan = Pelanggan::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $pelanggan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pelanggan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'alamat' => 'nullable|string',
                'no_hp' => 'nullable|string|max:20',
                'kota' => 'nullable|string|max:100',
                'status_bayar' => 'nullable|in:Tunai,Kredit',
                'batas_kredit' => 'nullable|numeric|min:0',
                'keterangan' => 'nullable|string',
            ]);

            $pelanggan = Pelanggan::findOrFail($id);
            $pelanggan->update([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'kota' => $request->kota,
                'status_bayar' => $request->status_bayar,
                'batas_kredit' => $request->batas_kredit,
                'keterangan' => $request->keterangan,
                'updated_by' => auth()->user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'pelanggan berhasil diupdate',
                'data' => $pelanggan
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate pelanggan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $pelanggan = Pelanggan::findOrFail($id);
            $pelanggan->updated_by = auth()->user()->id;
            $pelanggan->delete();

            return response()->json([
                'success' => true,
                'message' => 'pelanggan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pelanggan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Search pelanggan for AJAX autocomplete
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        $pelanggans = Pelanggan::where('nama', 'LIKE', '%' . $query . '%')
            ->orWhere('kode', 'LIKE', '%' . $query . '%')
            ->limit(10)
            ->get(['id', 'nama', 'kode']);

        return response()->json($pelanggans);
    }
}
