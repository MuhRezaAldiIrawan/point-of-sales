<?php

namespace App\Http\Controllers\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class SettingController extends Controller
{
    /**
     * Display the setting page.
     */
    public function index()
    {
        $title = 'Pengaturan Aplikasi';
        $setting = Setting::first();

        // Jika belum ada setting, buat data default
        if (!$setting) {
            $setting = Setting::create([
                'nama_perusahaan' => 'Nama Perusahaan',
                'alamat' => 'Alamat Perusahaan',
                'telepon_1' => '0000000000',
                'email' => 'email@perusahaan.com',
                'stok_minimal' => 10,
                'ppn' => 11,
            ]);
        }

        return view('pages.masterdata.setting.setting', compact('title', 'setting'));
    }

    /**
     * Update the setting.
     */
    public function update(Request $request, string $id)
    {
        try {
            $setting = Setting::findOrFail($id);

            $validated = $request->validate([
                'nama_perusahaan' => 'required|string|max:255',
                'alamat' => 'required|string',
                'telepon_1' => 'required|string|max:20',
                'telepon_2' => 'nullable|string|max:20',
                'email' => 'required|email|max:255',
                'nama_direktur' => 'nullable|string|max:255',
                'no_hp' => 'nullable|string|max:20',
                'stok_minimal' => 'required|integer|min:0',
                'ppn' => 'required|integer|min:0|max:100',
                'keterangan_struk' => 'nullable|string',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($request->hasFile('logo')) {
                // Hapus logo lama jika ada
                if ($setting->logo && Storage::disk('public')->exists($setting->logo)) {
                    Storage::disk('public')->delete($setting->logo);
                }

                $logo = $request->file('logo');
                $fileName = time() . '_' . uniqid() . '.' . $logo->getClientOriginalExtension();
                $logoPath = $logo->storeAs('setting', $fileName, 'public');
                $validated['logo'] = $logoPath;
            }

            $setting->update($validated);

            alert()->success('Berhasil', 'Pengaturan aplikasi berhasil diupdate');
            return redirect()->route('setting.index');

        } catch (\Illuminate\Validation\ValidationException $e) {
            alert()->error('Gagal', 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all()));
            return redirect()->back()->withInput()->withErrors($e->validator);
        } catch (\Exception $e) {
            alert()->error('Gagal', 'Gagal mengupdate pengaturan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }
}
