<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\DaftarHarga;
use App\Models\DetailDaftarHarga;
use App\Models\Satuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class DaftarHargaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Daftar Harga';

        if ($request->ajax()) {

            $data = DaftarHarga::orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn =
                        '
                            <button class="btn btn-icon btn-primary btn-daftarharga-edit" data-id="'.$row->id.'" type="button" role="button">
                                <i class="ft-edit"></i>
                            </button>

                            <button class="btn btn-icon btn-danger btn-daftarharga-delete" data-id="'.$row->id.'" type="button" role="button">
                                <i class="ft-trash"></i>
                            </button>
                            ';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pages.masterdata.daftarharga.daftarharga', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Buat Daftar Harga';
        $barangs = Barang::with(['merek', 'jenisBarang', 'detailBarang.satuan'])
            ->orderBy('nama_barang')
            ->get();
        $satuans = Satuan::all();

        return view('pages.masterdata.daftarharga._partials.form', compact('title', 'barangs', 'satuans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'diskon' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:all barang,pcs',
            'is_active' => 'required|boolean',
            'detail' => 'required_if:status,pcs|array',
            'detail.*.barang_id' => 'required_if:status,pcs|exists:barangs,id',
            'detail.*.satuan_id' => 'required_if:status,pcs|exists:satuans,id',
            'detail.*.harga_jual' => 'required_if:status,pcs|numeric|min:0',
            'detail.*.diskon' => 'nullable|numeric|min:0|max:100',
            'detail.*.is_active' => 'required_if:status,pcs|boolean',
        ], [
            'nama.required' => 'Nama daftar harga harus diisi',
            'status.required' => 'Tipe harga harus dipilih',
            'status.in' => 'Tipe harga tidak valid',
            'is_active.required' => 'Status aktif harus dipilih',
            'detail.required_if' => 'Minimal harus ada 1 barang untuk tipe Per Satuan',
            'detail.*.barang_id.required_if' => 'Barang harus dipilih',
            'detail.*.barang_id.exists' => 'Barang tidak valid',
            'detail.*.satuan_id.required_if' => 'Satuan harus dipilih',
            'detail.*.satuan_id.exists' => 'Satuan tidak valid',
            'detail.*.harga_jual.required_if' => 'Harga jual harus diisi',
            'detail.*.harga_jual.numeric' => 'Harga jual harus berupa angka',
            'detail.*.is_active.required_if' => 'Status aktif detail harus dipilih',
        ]);

        DB::beginTransaction();
        try {

            $daftarHarga = DaftarHarga::create([
                'nama' => $validated['nama'],
                'diskon' => $validated['diskon'] ?? 0,
                'status' => $validated['status'],
                'is_active' => $validated['is_active'],
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ]);

            if ($validated['status'] === 'pcs' && ! empty($validated['detail'])) {
                foreach ($validated['detail'] as $detail) {
                    DetailDaftarHarga::create([
                        'daftar_harga_id' => $daftarHarga->id,
                        'barang_id' => $detail['barang_id'],
                        'satuan_id' => $detail['satuan_id'],
                        'harga_jual' => $detail['harga_jual'],
                        'diskon' => $detail['diskon'] ?? 0,
                        'is_active' => $detail['is_active'] ?? true,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Daftar harga berhasil ditambahkan',
                ]);
            }

            return redirect()
                ->route('daftarharga.index')
                ->with('success', 'Daftar harga berhasil ditambahkan');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menambahkan daftar harga: '.$e->getMessage(),
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan daftar harga: '.$e->getMessage());
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
        $title = 'Edit Daftar Harga';
        $daftarHarga = DaftarHarga::with('details.barang')->findOrFail($id);
        $barangs = Barang::with(['merek', 'jenisBarang', 'detailBarang.satuan'])
            ->orderBy('nama_barang')
            ->get();
        $satuans = Satuan::all();

        return view('pages.masterdata.daftarharga._partials.form', compact('title', 'daftarHarga', 'barangs', 'satuans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'diskon' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:all barang,pcs',
            'is_active' => 'required|boolean',
            'detail' => 'required_if:status,pcs|array',
            'detail.*.barang_id' => 'required_if:status,pcs|exists:barangs,id',
            'detail.*.satuan_id' => 'required_if:status,pcs|exists:satuans,id',
            'detail.*.harga_jual' => 'required_if:status,pcs|numeric|min:0',
            'detail.*.diskon' => 'nullable|numeric|min:0|max:100',
            'detail.*.is_active' => 'required_if:status,pcs|boolean',
        ], [
            'nama.required' => 'Nama daftar harga harus diisi',
            'status.required' => 'Tipe harga harus dipilih',
            'status.in' => 'Tipe harga tidak valid',
            'is_active.required' => 'Status aktif harus dipilih',
            'detail.required_if' => 'Minimal harus ada 1 barang untuk tipe Per Satuan',
            'detail.*.barang_id.required_if' => 'Barang harus dipilih',
            'detail.*.barang_id.exists' => 'Barang tidak valid',
            'detail.*.satuan_id.required_if' => 'Satuan harus dipilih',
            'detail.*.satuan_id.exists' => 'Satuan tidak valid',
            'detail.*.harga_jual.required_if' => 'Harga jual harus diisi',
            'detail.*.harga_jual.numeric' => 'Harga jual harus berupa angka',
            'detail.*.is_active.required_if' => 'Status aktif detail harus dipilih',
        ]);

        DB::beginTransaction();
        try {
            $daftarHarga = DaftarHarga::findOrFail($id);

            $daftarHarga->update([
                'nama' => $validated['nama'],
                'diskon' => $validated['diskon'] ?? 0,
                'status' => $validated['status'],
                'is_active' => $validated['is_active'],
                'updated_by' => Auth::id(),
            ]);

            $daftarHarga->details()->update([
                'updated_by' => Auth::id(),
            ]);

            $daftarHarga->details()->delete();

            if ($validated['status'] === 'pcs' && ! empty($validated['detail'])) {
                foreach ($validated['detail'] as $detail) {
                    DetailDaftarHarga::create([
                        'daftar_harga_id' => $daftarHarga->id,
                        'barang_id' => $detail['barang_id'],
                        'satuan_id' => $detail['satuan_id'],
                        'harga_jual' => $detail['harga_jual'],
                        'diskon' => $detail['diskon'] ?? 0,
                        'is_active' => $detail['is_active'] ?? true,
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ]);
                }
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Daftar harga berhasil diperbarui',
                ]);
            }

            return redirect()
                ->route('daftarharga.index')
                ->with('success', 'Daftar harga berhasil diperbarui');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memperbarui daftar harga: '.$e->getMessage(),
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal memperbarui daftar harga: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $daftarHarga = DaftarHarga::findOrFail($id);

            $daftarHarga->details()->update([
                'updated_by' => Auth::id(),
            ]);

            $daftarHarga->details()->delete();

            $daftarHarga->update([
                'updated_by' => Auth::id(),
            ]);

            $daftarHarga->delete();

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Daftar harga berhasil dihapus',
                ]);
            }

            return redirect()
                ->route('daftarharga.index')
                ->with('success', 'Daftar harga berhasil dihapus');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus daftar harga: '.$e->getMessage(),
                ], 500);
            }

            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus daftar harga: '.$e->getMessage());
        }
    }
}
