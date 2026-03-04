<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Profile';
        $user = auth()->user()->load('jabatan');

        return view('pages.profile.profile', compact('title', 'user'));
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
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $user = auth()->user();

            $validated = $request->validate([
                'nama_depan' => 'required|string|max:255',
                'nama_belakang' => 'nullable|string|max:255',
                'email' => 'required|email|unique:users,email,' . $user->id,
                'no_telepon' => 'required|string|unique:users,no_telepon,' . $user->id,
                'jenis_kelamin' => 'required|in:L,P',
                'alamat' => 'nullable|string',
                'tanggal_lahir' => 'nullable|date',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($request->hasFile('foto')) {
                if ($user->foto && Storage::disk('public')->exists($user->foto)) {
                    Storage::disk('public')->delete($user->foto);
                }

                $foto = $request->file('foto');
                $fileName = time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
                $fotoPath = $foto->storeAs('karyawan', $fileName, 'public');
                $validated['foto'] = $fotoPath;
            }

            $validated['updated_by'] = auth()->id();
            $user->update($validated);

            alert()->success('Berhasil', 'Profile berhasil diupdate');
            return redirect()->route('profile.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            alert()->error('Gagal', 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all()));
            return redirect()->back()->withInput()->withErrors($e->validator);
        } catch (\Exception $e) {
            alert()->error('Gagal', 'Gagal mengupdate profile: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
