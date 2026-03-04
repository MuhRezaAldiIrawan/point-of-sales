<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Supplier';

        if ($request->ajax()) {

            $data = Supplier::orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn =
                        '
                            <button class="btn btn-icon btn-primary btn-supplier-edit" data-id="' . $row->id . '" type="button" role="button">
                                <i class="ft-edit"></i>
                            </button>

                            <button class="btn btn-icon btn-danger btn-supplier-delete" data-id="' . $row->id . '" type="button" role="button">
                                <i class="ft-trash"></i>
                            </button>
                            ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }


        return view('pages.masterdata.supplier.supplier', compact('title'));
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
                'kota' => 'nullable|string|max:100',
                'no_hp' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'contact_person' => 'nullable|string|max:255',
                'telepon_contact_person' => 'nullable|string|max:20',
                'keterangan' => 'nullable|string',
            ]);

            $supplier = Supplier::create([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'kota' => $request->kota,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'contact_person' => $request->contact_person,
                'telepon_contact_person' => $request->telepon_contact_person,
                'keterangan' => $request->keterangan,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Supplier berhasil ditambahkan',
                'data' => $supplier
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan supplier: ' . $e->getMessage()
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
            $supplier = Supplier::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $supplier
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data Supplier: ' . $e->getMessage()
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
                'kota' => 'nullable|string|max:100',
                'no_hp' => 'nullable|string|max:20',
                'email' => 'nullable|email|max:255',
                'contact_person' => 'nullable|string|max:255',
                'telepon_contact_person' => 'nullable|string|max:20',
                'keterangan' => 'nullable|string',
            ]);

            $supplier = Supplier::findOrFail($id);

            $supplier->update([
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'kota' => $request->kota,
                'no_hp' => $request->no_hp,
                'email' => $request->email,
                'contact_person' => $request->contact_person,
                'telepon_contact_person' => $request->telepon_contact_person,
                'keterangan' => $request->keterangan,
                'updated_by' => auth()->user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Supplier berhasil diupdate',
                'data' => $supplier
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate supplier: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->updated_by = auth()->user()->id;
            $supplier->delete();

            return response()->json([
                'success' => true,
                'message' => 'Supplier berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus supplier: ' . $e->getMessage()
            ], 500);
        }
    }
}
