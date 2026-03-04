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

                            <form action="{{ route('setting.update', $setting->id ?? 1) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="text-center mb-4">
                                            <img src="{{ $setting->logo ? Storage::url($setting->logo) : asset('app-assets/images/logo/logo.png') }}"
                                                alt="Logo Perusahaan" class="img-border" id="preview-logo"
                                                style="width: 200px; height: 200px; object-fit: contain;">
                                            <div class="mt-3">
                                                <label class="btn btn-sm btn-primary">
                                                    <i class="la la-upload"></i> Upload Logo
                                                    <input type="file" name="logo" class="d-none" accept="image/*" onchange="previewLogo(event)">
                                                </label>
                                            </div>
                                        </div>

                                        <h5 class="mb-3">Konfigurasi Sistem</h5>
                                        <div class="form-group">
                                            <label>Stok Minimal <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" name="stok_minimal" class="form-control"
                                                    value="{{ $setting->stok_minimal ?? 10 }}" min="0">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Unit</span>
                                                </div>
                                            </div>
                                            <small class="text-muted">Batas minimal stok sebelum notifikasi</small>
                                        </div>

                                        <div class="form-group">
                                            <label>PPN (%) <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <input type="number" name="ppn" class="form-control"
                                                    value="{{ $setting->ppn ?? 11 }}" min="0" max="100" step="0.01">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            <small class="text-muted">Pajak Pertambahan Nilai</small>
                                        </div>

                                        <div class="form-group">
                                            <label>Keterangan Struk</label>
                                            <textarea name="keterangan_struk" class="form-control" rows="4"
                                                placeholder="Contoh: Terima kasih atas kunjungan Anda">{{ $setting->keterangan_struk ?? '' }}</textarea>
                                            <small class="text-muted">Pesan di bawah struk pembelian</small>
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <h5 class="mb-3">Informasi Perusahaan</h5>
                                        <div class="form-group">
                                            <label>Nama Perusahaan <span class="text-danger">*</span></label>
                                            <div class="position-relative has-icon-left">
                                                <input type="text" name="nama_perusahaan" class="form-control"
                                                    value="{{ $setting->nama_perusahaan ?? '' }}" required>
                                                <div class="form-control-position">
                                                    <i class="la la-building"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat <span class="text-danger">*</span></label>
                                            <div class="position-relative has-icon-left">
                                                <textarea name="alamat" class="form-control" rows="2" required>{{ $setting->alamat ?? '' }}</textarea>
                                                <div class="form-control-position">
                                                    <i class="la la-map-marker"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Telepon 1 <span class="text-danger">*</span></label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" name="telepon_1" class="form-control"
                                                            value="{{ $setting->telepon_1 ?? '' }}" required>
                                                        <div class="form-control-position">
                                                            <i class="la la-phone"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Telepon 2</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" name="telepon_2" class="form-control"
                                                            value="{{ $setting->telepon_2 ?? '' }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-phone"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <div class="position-relative has-icon-left">
                                                <input type="email" name="email" class="form-control"
                                                    value="{{ $setting->email ?? '' }}" required>
                                                <div class="form-control-position">
                                                    <i class="la la-envelope"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <h5 class="mb-3 mt-4">Informasi Direktur</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Nama Direktur</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" name="nama_direktur" class="form-control"
                                                            value="{{ $setting->nama_direktur ?? '' }}">
                                                        <div class="form-control-position">
                                                            <i class="ft-user"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>No. HP Direktur</label>
                                                    <div class="position-relative has-icon-left">
                                                        <input type="text" name="no_hp" class="form-control"
                                                            value="{{ $setting->no_hp ?? '' }}">
                                                        <div class="form-control-position">
                                                            <i class="la la-mobile"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="alert alert-info mt-3">
                                            <i class="la la-info-circle"></i>
                                            <strong>Informasi:</strong> Data setting ini akan digunakan untuk struk, laporan, dan informasi perusahaan di sistem.
                                        </div>

                                        <div class="form-actions right mt-4">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="la la-check-square-o"></i> Simpan Pengaturan
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
        function previewLogo(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const output = document.getElementById('preview-logo');
                output.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection

