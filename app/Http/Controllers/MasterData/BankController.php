<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

use App\Models\Bank;


class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Bank';

        if ($request->ajax()) {

            $data = Bank::orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn =
                        '
                            <button class="btn btn-icon btn-primary btn-bank-edit" data-id="' . $row->id . '" type="button" role="button">
                                <i class="ft-edit"></i>
                            </button>

                            <button class="btn btn-icon btn-danger btn-bank-delete" data-id="' . $row->id . '" type="button" role="button">
                                <i class="ft-trash"></i>
                            </button>
                            ';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.masterdata.bank.bank', compact('title'));
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
                'no_rekening' => 'required|string|max:50',
                'atas_nama' => 'required|string|max:255',
            ]);
            $bank = Bank::create([
                'nama' => $request->nama,
                'no_rekening' => $request->no_rekening,
                'atas_nama' => $request->atas_nama,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Bank berhasil ditambahkan',
                'data' => $bank
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan Bank: ' . $e->getMessage()
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
            $bank = Bank::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $bank
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data bank: ' . $e->getMessage()
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
                'no_rekening' => 'required|string|max:50',
                'atas_nama' => 'required|string|max:255',
            ]);

            $bank = Bank::findOrFail($id);
            $bank->update([
                'nama' => $request->nama,
                'no_rekening' => $request->no_rekening,
                'atas_nama' => $request->atas_nama,
                'updated_by' => auth()->user()->id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'bank berhasil diupdate',
                'data' => $bank
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupdate bank: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $bank = Bank::findOrFail($id);
            $bank->updated_by = auth()->user()->id;
            $bank->delete();

            return response()->json([
                'success' => true,
                'message' => 'bank berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus bank: ' . $e->getMessage()
            ], 500);
        }
    }
}
