<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Merek;
use Yajra\DataTables\DataTables;

class MerekController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Merek';

        if ($request->ajax()) {

            $data = Merek::orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn =
                        '
                            <button class="btn btn-icon btn-primary btn-merek-edit" data-id="' . $row->id . '" type="button" role="button">
                                <i class="ft-edit"></i>
                            </button>

                            <button class="btn btn-icon btn-danger btn-merek-delete" data-id="' . $row->id . '" type="button" role="button">
                                <i class="ft-trash"></i>
                            </button>
                            ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.masterdata.merek.merek', compact('title'));
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
            'deskripsi' => 'nullable|string'
        ]);

        try {
            $merek = Merek::create([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Merek berhasil ditambahkan',
                'data' => $merek
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan merek: ' . $e->getMessage()
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
            $merek = Merek::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $merek
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data merek: ' . $e->getMessage()
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
            $merek = Merek::findOrFail($id);
            $merek->update([
                'nama' => $request->nama,
                'deskripsi' => $request->deskripsi,
                'updated_by' => auth()->user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Merek berhasil diupdate',
                'data' => $merek
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate merek: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $merek = Merek::findOrFail($id);
            $merek->updated_by = auth()->user()->id;
            $merek->delete();

            return response()->json([
                'success' => true,
                'message' => 'Merek berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus merek: ' . $e->getMessage()
            ], 500);
        }
    }
}
