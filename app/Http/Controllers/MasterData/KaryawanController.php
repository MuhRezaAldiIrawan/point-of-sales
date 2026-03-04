<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Jabatan;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Karyawan';
        $jabatan = Jabatan::all();

        if ($request->ajax()) {

            $data = User::with('jabatan')->orderBy('created_at', 'desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('foto', function ($row) {
                    if ($row->foto) {
                        return '<img src="' . Storage::url($row->foto) . '" alt="Foto" style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">';
                    }
                    return '<div style="width: 50px; height: 50px; background: #f1f1f1; border-radius: 50%; display: flex; align-items: center; justify-content: center;"><i class="ft-user" style="color: #ccc;"></i></div>';
                })
                ->addColumn('jabatan', function ($row) {
                    return $row->jabatan->nama;
                })
                ->addColumn('nama_lengkap', function ($row) {
                    return $row->nama_depan . ' ' . $row->nama_belakang;
                })
                ->addColumn('action', function ($row) {
                    $btn =
                        '

                            <button class="btn btn-icon btn-info btn-karyawan-reset-password" data-id="' . $row->id . '" type="button" role="button" data-toggle="tooltip" data-placement="top" title="Reset Password">
                                <i class="ft-refresh-cw"></i>
                            </button>

                            <a href="' . route('karyawan.edit', $row->id) . '" class="btn btn-icon btn-warning" type="button" role="button">
                                <i class="ft-edit"></i>
                            </a>

                            <button class="btn btn-icon btn-danger btn-karyawan-delete" data-id="' . $row->id . '" type="button" role="button">
                                <i class="ft-trash"></i>
                            </button>
                            ';
                    return $btn;
                })
                ->rawColumns(['action', 'foto'])
                ->make(true);
        }

        return view('pages.masterdata.karyawan.karyawan', compact('title', 'jabatan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Tambah Karyawan';
        $jabatans = Jabatan::all();

        // Generate unique NIP
        $baseTimestamp = now();
        $nip = $baseTimestamp->copy()->addMinutes(1)->format('ymdHis');

        return view('pages.masterdata.karyawan._partials.form', compact('title', 'jabatans', 'nip'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        try {

            $request->validate([
                'nip' => 'required|string|unique:users,nip',
                'nama_depan' => 'required|string|max:255',
                'nama_belakang' => 'required|string|max:255',
                'jabatan_id' => 'required|exists:jabatans,id',
            ]);

            $karyawan = User::create([
                'nip' => $request->nip,
                'nama_depan' => $request->nama_depan,
                'nama_belakang' => $request->nama_belakang,
                'jabatan_id' => $request->jabatan_id,
                'password' => bcrypt($request->nip),
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id
            ]);

            alert()->success('Berhasil', 'Karyawan berhasil ditambahkan');
            return redirect()->route('karyawan.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            alert()->error('Gagal', 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all()));
            return redirect()->back()->withInput()->withErrors($e->validator);
        } catch (\Exception $e) {
            alert()->error('Gagal', 'Gagal menambahkan karyawan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $title = 'Detail Karyawan';
        $karyawan = User::with('jabatan')->findOrFail($id);
        $jabatans = Jabatan::all();

        return view('pages.masterdata.karyawan._partials.detail_karyawan', compact('title', 'karyawan', 'jabatans'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $title = 'Edit Karyawan';
        $karyawan = User::findOrFail($id);
        $jabatans = Jabatan::all();

        return view('pages.masterdata.karyawan._partials.form', compact('title', 'karyawan', 'jabatans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $karyawan = User::findOrFail($id);

            $rules = [
                'nip' => 'required|string|unique:users,nip,' . $id,
                'ktp' => 'nullable|string|unique:users,ktp,' . $id,
                'nama_depan' => 'required|string|max:255',
                'nama_belakang' => 'nullable|string|max:255',
                'jenis_kelamin' => 'nullable|in:L,P',
                'alamat' => 'nullable|string',
                'tanggal_lahir' => 'nullable|date',
                'email' => 'nullable|email|unique:users,email,' . $id,
                'no_telepon' => 'nullable|string|unique:users,no_telepon,' . $id,
                'jabatan_id' => 'nullable|exists:jabatans,id',
                'tanggungan' => 'nullable|string',
                'referensi' => 'nullable|string',
                'status_karyawan' => 'required|in:aktif,non-aktif',
                'status_login' => 'required|in:aktif,non-aktif',
                'tanggal_masuk' => 'nullable|date',
                'gaji_pokok' => 'required|numeric|min:0',
                'tunjangan_jabatan' => 'nullable|numeric|min:0',
                'uang_makan' => 'nullable|numeric|min:0',
                'tunjangan_lain' => 'nullable|numeric|min:0',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];

            if ($request->filled('password')) {
                $rules['password'] = 'nullable|string|min:5';
            }

            $validated = $request->validate($rules);


            if (empty($validated['tanggal_lahir'])) {
                $validated['tanggal_lahir'] = null;
            }
            if (empty($validated['tanggal_masuk'])) {
                $validated['tanggal_masuk'] = null;
            }


            if ($request->hasFile('foto')) {
                if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                    Storage::disk('public')->delete($karyawan->foto);
                }

                $foto = $request->file('foto');
                $fileName = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $fotoPath = $foto->storeAs('karyawan', $fileName, 'public');
                $validated['foto'] = $fotoPath;
            }


            if ($request->filled('password')) {
                $validated['password'] = bcrypt($request->password);
            } else {
                unset($validated['password']);
            }


            $validated['updated_by'] = auth()->user()->id ?? null;


            $karyawan->update($validated);

            alert()->success('Berhasil', 'Data karyawan berhasil diupdate');
            return redirect()->route('karyawan.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            alert()->error('Gagal', 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all()));
            return redirect()->back()->withInput()->withErrors($e->validator);
        } catch (\Exception $e) {
            alert()->error('Gagal', 'Gagal mengupdate data karyawan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Reset password karyawan menjadi NIP.
     */
    public function resetPassword(string $id)
    {
        try {
            $karyawan = User::findOrFail($id);

            $karyawan->password = bcrypt($karyawan->nip);
            $karyawan->updated_by = auth()->user()->id;
            $karyawan->save();

            return response()->json([
                'success' => true,
                'message' => 'Password karyawan berhasil direset'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mereset password: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $karyawan = User::findOrFail($id);

            // Hapus foto jika ada
            if ($karyawan->foto && Storage::disk('public')->exists($karyawan->foto)) {
                Storage::disk('public')->delete($karyawan->foto);
            }

            $karyawan->updated_by = auth()->user()->id;

            $karyawan->delete();

            return response()->json([
                'success' => true,
                'message' => 'Karyawan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus karyawan: ' . $e->getMessage()
            ], 500);
        }
    }
}
