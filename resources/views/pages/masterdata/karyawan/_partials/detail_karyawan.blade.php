@extends('layouts.main')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('content')
    <section id="basic-examples">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $title }}</h4>
                        <div class="heading-elements">
                            <a href="{{ route('karyawan.index') }}"
                                class="btn btn-secondary btn-md d-flex align-items-center">
                                <i class="la la-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Terjadi Kesalahan!</strong>
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center mb-4">
                                            <img src="{{ $karyawan->foto ? Storage::url($karyawan->foto) : asset('app-assets/images/portrait/small/avatar-s-12.png') }}"
                                                alt="Foto Karyawan" class="rounded-circle img-border" id="preview-foto"
                                                style="width: 200px; height: 200px; object-fit: cover;">
                                            <div class="mt-3">
                                                <label class="btn btn-sm btn-primary">
                                                    <i class="la la-upload"></i> Upload Foto
                                                    <input type="file" name="foto" class="d-none" accept="image/*" onchange="previewImage(event)">
                                                </label>
                                            </div>
                                        </div>

                                        <h5 class="mb-3">Informasi Gaji</h5>
                                        <div class="form-group">
                                            <label>Gaji Pokok <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" name="gaji_pokok" class="form-control"
                                                    value="{{ $karyawan->gaji_pokok }}" step="0.01">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Tunjangan Jabatan</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" name="tunjangan_jabatan" class="form-control"
                                                    value="{{ $karyawan->tunjangan_jabatan }}" step="0.01">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Uang Makan</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" name="uang_makan" class="form-control"
                                                    value="{{ $karyawan->uang_makan }}" step="0.01">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Tunjangan Lain</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" name="tunjangan_lain" class="form-control"
                                                    value="{{ $karyawan->tunjangan_lain }}" step="0.01">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <h5 class="mb-3">Informasi Personal</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>NIP <span class="text-danger">*</span></label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" name="nip" class="form-control"
                                                            value="{{ $karyawan->nip }}" required>
                                                        <div class="form-control-position">
                                                            <i class="la la-book"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>KTP</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" name="ktp" class="form-control" placeholder="KTP"
                                                            value="{{ $karyawan->ktp }}">
                                                        <div class="form-control-position">
                                                            <i class="ft-book"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Depan <span class="text-danger">*</span></label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" name="nama_depan" class="form-control"
                                                            value="{{ $karyawan->nama_depan }}" required>
                                                        <div class="form-control-position">
                                                            <i class="ft-user"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Belakang</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" name="nama_belakang" class="form-control"
                                                            value="{{ $karyawan->nama_belakang }}">
                                                        <div class="form-control-position">
                                                            <i class="ft-user"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Jenis Kelamin</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select name="jenis_kelamin" class="form-control">
                                                            <option value="">Pilih Jenis Kelamin</option>
                                                            <option value="L" {{ $karyawan->jenis_kelamin == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                                            <option value="P" {{ $karyawan->jenis_kelamin == 'P' ? 'selected' : '' }}>Perempuan</option>
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-venus-mars"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tanggal Lahir</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="date" name="tanggal_lahir" class="form-control"
                                                            value="{{ $karyawan->tanggal_lahir ? $karyawan->tanggal_lahir->format('Y-m-d') : '' }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-calendar"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Email</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="email" name="email" class="form-control" placeholder="Email"
                                                            value="{{ $karyawan->email }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-envelope"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>No. Telepon</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" name="no_telepon" class="form-control" placeholder="No telephone"
                                                            value="{{ $karyawan->no_telepon }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-phone"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat</label>
                                            <div class="position-relative has-icon-left">
                                                <textarea name="alamat" class="form-control" rows="2">{{ $karyawan->alamat }}</textarea>
                                                <div class="form-control-position">
                                                    <i class="la la-map-marker"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <h5 class="mb-3 mt-4">Informasi Kepegawaian</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Jabatan</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select name="jabatan_id" class="form-control">
                                                            <option value="">Pilih Jabatan</option>
                                                            @foreach($jabatans ?? [] as $jabatan)
                                                                <option value="{{ $jabatan->id }}" {{ $karyawan->jabatan_id == $jabatan->id ? 'selected' : '' }}>
                                                                    {{ $jabatan->nama }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-briefcase"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tanggal Masuk</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="date" name="tanggal_masuk" class="form-control"
                                                            value="{{ $karyawan->tanggal_masuk ? $karyawan->tanggal_masuk->format('Y-m-d') : '' }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-calendar-check-o"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Status Karyawan</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select name="status_karyawan" class="form-control">
                                                            <option value="aktif" {{ $karyawan->status_karyawan == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                            <option value="non-aktif" {{ $karyawan->status_karyawan == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-info-circle"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Status Login</label>
                                                    <div class="position-relative has-icon-left">
                                                        <select name="status_login" class="form-control">
                                                            <option value="aktif" {{ $karyawan->status_login == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                                            <option value="non-aktif" {{ $karyawan->status_login == 'non-aktif' ? 'selected' : '' }}>Non-Aktif</option>
                                                        </select>
                                                        <div class="form-control-position">
                                                            <i class="la la-sign-in"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Tanggungan</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" name="tanggungan" class="form-control"
                                                            value="{{ $karyawan->tanggungan }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-users"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Referensi</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" name="referensi" class="form-control"
                                                            value="{{ $karyawan->referensi }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-link"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                                            <div class="position-relative has-icon-left">
                                                <input type="password" name="password" class="form-control"
                                                    placeholder="Masukkan password baru">
                                                <div class="form-control-position">
                                                    <i class="la la-lock"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-actions right mt-4">
                                            <a href="{{ route('karyawan.index') }}" class="btn btn-secondary mr-1">
                                                <i class="ft-x"></i> Batal
                                            </a>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="la la-check-square-o"></i> Update Data
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('preview-foto');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
