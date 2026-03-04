@extends('layouts.main')

@php
use Illuminate\Support\Facades\Storage;
@endphp

@section('css')
    <!-- Select2 CSS -->
    <link rel="stylesheet" href="{{ asset('app-assets/vendors/css/forms/selects/select2.min.css') }}">
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .card {
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            border: none;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e8e8e8;
        }


        .image-upload-wrapper {
            position: relative;
            width: 100%;
            max-width: 300px;
            margin: 0 auto;
        }

        .preview-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 12px;
            border: 3px solid #e8e8e8;
            transition: all 0.3s ease;
        }

        .image-placeholder {
            width: 100%;
            height: 300px;
            border: 3px dashed #cbd5e0;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .image-placeholder:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            transform: translateY(-2px);
        }

        .image-placeholder i {
            font-size: 64px;
            color: #a0aec0;
            margin-bottom: 15px;
        }

        .detail-input-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .detail-input-card h5 {
            color: white;
            font-weight: 700;
            margin-bottom: 25px;
            font-size: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .detail-input-card .form-control {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.5);
            color: #2d3748;
            border-radius: 8px;
            padding: 12px 15px;
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .detail-input-card .form-control:focus {
            background: white;
            border-color: white;
            color: #2d3748;
            box-shadow: 0 0 0 3px rgba(255, 255, 255, 0.3);
        }

        .detail-input-card .form-control::placeholder {
            color: #a0aec0;
        }

        .detail-input-card select.form-control {
            background: rgba(255, 255, 255, 0.95);
            color: #2d3748;
        }

        .detail-input-card select.form-control option {
            background: white;
            color: #2d3748;
        }

        .detail-input-card label {
            color: white;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-add-detail {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
        }

        .btn-add-detail:hover {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(72, 187, 120, 0.4);
        }

        .table-detail {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }

        .table-detail thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .table-detail thead th {
            border: none;
            font-weight: 600;
            padding: 15px 12px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-detail tbody td {
            border: none;
            padding: 12px;
            vertical-align: middle;
            background: white;
            border-bottom: 1px solid #f1f5f9;
        }

        .table-detail tbody tr {
            transition: all 0.3s ease;
        }

        .table-detail tbody tr:hover {
            background: #f8fafc;
            transform: scale(1.01);
        }

        .empty-state {
            background: white;
            padding: 40px 20px;
            text-align: center;
            border: 2px dashed #e2e8f0;
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .badge-required {
            background: #e53e3e;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Select2 Custom Styling - Based on Chameleon Template */
        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #d9dee3;
            border-radius: 0.25rem;
            background: #fff;
            padding: 0 12px;
            font-size: 14px;
            color: #495057;
            transition: all 0.15s ease-in-out;
        }

        .select2-container--default .select2-selection--single:hover {
            border-color: #adb5bd;
        }

        .select2-container--default .select2-selection--single:focus-within {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            background: #fff;
        }

        .select2-container--default .select2-selection__rendered {
            color: #495057;
            padding: 6px 0;
            line-height: 1.5;
        }

        .select2-container--default .select2-selection__placeholder {
            color: #6c757d;
            font-style: italic;
        }

        .select2-container--default .select2-selection__clear {
            color: #6c757d;
            cursor: pointer;
            padding: 0 5px;
        }

        .select2-container--default .select2-selection__clear:hover {
            color: #dc3545;
        }

        .select2-container--default .select2-selection__arrow {
            height: 36px;
            right: 8px;
        }

        .select2-container--default .select2-selection__arrow b {
            border-color: #495057 transparent transparent transparent;
            border-width: 5px 5px 0 5px;
        }

        .select2-container--default.select2-container--open .select2-selection__arrow b {
            border-color: transparent transparent #495057 transparent;
            border-width: 0 5px 5px 5px;
        }

        .select2-dropdown {
            border: 1px solid #d9dee3;
            border-radius: 0.25rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            background: #fff;
            margin-top: 2px;
        }

        .select2-container--default .select2-results__option {
            padding: 8px 12px;
            font-size: 14px;
            color: #495057;
            background: #fff;
            border-bottom: 1px solid #f8f9fa;
        }

        .select2-container--default .select2-results__option--highlighted {
            background: #007bff;
            color: #fff;
        }

        .select2-container--default .select2-results__option--selected {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1px solid #d9dee3;
            border-radius: 4px;
            padding: 6px 8px;
            font-size: 14px;
            margin: 8px;
            width: calc(100% - 16px);
        }

        .select2-search--dropdown .select2-search__field:focus {
            border-color: #80bdff;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Custom Select Group Styling - Simplified */
        .select-group {
            position: relative;
            display: flex;
            align-items: stretch;
        }

        .select-group .select2-container {
            flex: 1;
        }

        .select-group .btn-add {
            border: 1px solid #28a745;
            border-left: none;
            background: #28a745;
            color: white;
            padding: 0 12px;
            font-size: 14px;
            font-weight: 400;
            transition: all 0.15s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0 0.25rem 0.25rem 0;
            min-width: 45px;
        }

        .select-group .btn-add:hover {
            background: #218838;
            border-color: #1e7e34;
            color: white;
        }

        .select-group .btn-add:focus {
            box-shadow: none;
            outline: none;
        }

        .select-group .select2-container .select2-selection--single {
            border-radius: 0.25rem 0 0 0.25rem;
            border-right: none;
        }

        .select-group .select2-container .select2-selection--single:focus-within {
            border-right: none;
        }

        /* Form Control Styling */
        .form-control {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            color: #2d3748;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
            color: #2d3748;
        }

        .btn-save {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            border: none;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(72, 187, 120, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-save:hover {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(72, 187, 120, 0.4);
        }

        .btn-cancel {
            background: #e2e8f0;
            border: 1px solid #cbd5e1;
            color: #4a5568;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-right: 10px;
        }

        .btn-cancel:hover {
            background: #cbd5e1;
            border-color: #a0aec0;
            color: #2d3748;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .btn-delete {
            background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);
            border: 1px solid #c53030;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .btn-delete:hover {
            background: linear-gradient(135deg, #c53030 0%, #9b2c2c 100%);
            border-color: #9b2c2c;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(229, 62, 62, 0.3);
        }

        .btn-edit {
            background: linear-gradient(135deg, #3182ce 0%, #2c5282 100%);
            border: 1px solid #2c5282;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            transition: all 0.3s ease;
        }

        .btn-edit:hover {
            background: linear-gradient(135deg, #2c5282 0%, #1a365d 100%);
            border-color: #1a365d;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(49, 130, 206, 0.3);
        }

        .input-group .input-group-append .btn {
            border-left: 0;
            border-radius: 0 4px 4px 0;
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            border: 1px solid #38a169;
            color: white;
            transition: all 0.3s ease;
        }

        .input-group .input-group-append .btn:hover {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            border-color: #2f855a;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(72, 187, 120, 0.3);
        }

        /* Override Bootstrap btn-success */
        .btn-success {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            border: 1px solid #38a169;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            border-color: #2f855a;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(72, 187, 120, 0.3);
        }

        /* Modal button styling */
        .modal .modal-footer .btn-primary {
            background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
            border: 1px solid #38a169;
            color: white;
            transition: all 0.3s ease;
        }

        .modal .modal-footer .btn-primary:hover {
            background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);
            border-color: #2f855a;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(72, 187, 120, 0.3);
        }

        .modal .modal-footer .btn-secondary {
            background: #e2e8f0;
            border: 1px solid #cbd5e1;
            color: #4a5568;
            transition: all 0.3s ease;
        }

        .modal .modal-footer .btn-secondary:hover {
            background: #cbd5e1;
            border-color: #a0aec0;
            color: #2d3748;
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .form-control {
                padding: 10px 12px;
            }

            .table-detail thead th,
            .table-detail tbody td {
                padding: 8px 4px;
                font-size: 12px;
            }

            .btn-save, .btn-cancel {
                padding: 8px 16px;
                font-size: 14px;
            }

            .section-title {
                font-size: 16px;
                margin-bottom: 15px;
            }

            .detail-input-card {
                padding: 15px;
            }

            .image-upload-wrapper {
                margin-top: 15px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">

                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Berhasil!</strong> {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error!</strong> {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form id="formBarang"
                            action="{{ isset($barang) ? route('barang.update', $barang->id) : route('barang.store') }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            @if (isset($barang))
                                @method('PUT')
                            @endif

                            <div class="section-title">
                                <i class="ft-package"></i> Data Barang
                            </div>

                            <div class="row">
                                <div class="col-md-7">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Kode Barang <span
                                                        class="badge-required">Wajib</span></label>
                                                <input type="text" class="form-control" id="kode" name="kode"
                                                    placeholder="Contoh: BRG001" value="{{ isset($barang) ? $barang->kode : $kodeBarang }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Jenis Barang <span
                                                        class="badge-required">Wajib</span></label>
                                            <div class="select-group">
                                                <select class="form-control" id="jenis_barang_id" name="jenis_barang_id">
                                                    <option value="">-- Pilih Jenis --</option>
                                                </select>
                                                <button type="button" class="btn btn-success btn-add" data-toggle="modal" data-target="#modalJenisBarang" title="Tambah Jenis Barang">
                                                    <i class="ft-plus"></i>
                                                </button>
                                            </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Nama Barang <span
                                                class="badge-required">Wajib</span></label>
                                        <input type="text" class="form-control" id="nama_barang" name="nama_barang"
                                            placeholder="Masukkan nama barang">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Merek <span
                                                        class="badge-required">Wajib</span></label>
                                                <div class="select-group">
                                                    <select class="form-control" id="merek_id" name="merek_id">
                                                        <option value="">-- Pilih Merek --</option>
                                                    </select>
                                                    <button type="button" class="btn btn-success btn-add" data-toggle="modal" data-target="#modalMerek" title="Tambah Merek">
                                                        <i class="ft-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">Supplier <span
                                                        class="badge-required">Wajib</span></label>
                                                <div class="select-group">
                                                    <select class="form-control" id="supplier_id" name="supplier_id">
                                                        <option value="">-- Pilih Supplier --</option>
                                                        @if(isset($barang) && $barang->supplier)
                                                            <option value="{{ $barang->supplier_id }}" selected>{{ $barang->supplier->nama }}</option>
                                                        @endif
                                                    </select>
                                                    <button type="button" class="btn btn-success btn-add" data-toggle="modal" data-target="#modalSupplier" title="Tambah Supplier">
                                                        <i class="ft-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">Keterangan</label>
                                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                                            placeholder="Tambahkan keterangan atau catatan tentang barang ini (opsional)"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label class="form-label">Foto Barang</label>
                                        <div class="image-upload-wrapper">
                                            <div id="imagePreviewContainer"
                                                style="cursor: pointer; margin: 0;">
                                                <div id="placeholderText" class="image-placeholder">
                                                    <i class="ft-camera"></i>
                                                    <h6 style="color: #718096; font-weight: 600; margin: 0;">Upload Foto
                                                    </h6>
                                                    <p style="color: #a0aec0; font-size: 12px; margin-top: 5px;">Klik untuk
                                                        memilih gambar</p>
                                                    <small style="color: #cbd5e0; font-size: 11px;">PNG, JPG, JPEG (Max.
                                                        2MB)</small>
                                                </div>
                                                <img id="imagePreview" class="preview-image" style="display: none;"
                                                    src="{{ isset($barang) && $barang->foto ? Storage::url($barang->foto) : '' }}"
                                                    alt="Preview">
                                            </div>
                                        </div>
                                        <input type="file" class="d-none" id="foto" name="foto"
                                            accept="image/*">
                                    </div>
                                </div>
                            </div>


                            <div class="section-title mt-4">
                                <i class="ft-tag"></i> Detail Harga Barang
                            </div>

                            <div class="detail-input-card">
                                <h5>
                                    <i class="ft-shopping-cart"></i>
                                    Input Detail Satuan & Harga
                                </h5>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label>Satuan</label>
                                            <div class="select-group">
                                                <select class="form-control" id="detail_satuan_id">
                                                    <option value="">-- Pilih Satuan --</option>
                                                </select>
                                                <button type="button" class="btn btn-success btn-add" data-toggle="modal" data-target="#modalSatuan" title="Tambah Satuan">
                                                    <i class="ft-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label>Jumlah Isi</label>
                                            <input type="number" class="form-control" id="detail_isi" min="1"
                                                placeholder="Masukkan jumlah">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label>Harga Beli (Rp)</label>
                                            <input type="text" class="form-control" id="detail_harga_beli"
                                                placeholder="Masukkan harga beli">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group mb-0">
                                            <label>Harga Jual (Rp)</label>
                                            <input type="text" class="form-control" id="detail_harga_jual"
                                                placeholder="Masukkan harga jual">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right mb-4">
                                <button type="button" class="btn btn-add-detail" id="btnTambahDetail">
                                    <i class="ft-plus-circle"></i> Tambahkan ke Tabel
                                </button>
                            </div>

                            <div class="section-title">
                                <i class="ft-list"></i> Daftar Detail Harga
                            </div>

                            <div class="table-responsive">
                                <table class="table table-detail table-hover" id="tableDetail">
                                    <thead>
                                        <tr>
                                            <th width="5%" class="text-center">#</th>
                                            <th>Satuan</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-right">Harga Beli</th>
                                            <th class="text-right">Harga Jual</th>
                                            <th width="15%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="detailBarangContainer">
                                        <tr>
                                            <td colspan="7" class="empty-state">
                                                <i class="ft-inbox"
                                                    style="font-size: 48px; color: #cbd5e0; display: block; margin-bottom: 10px;"></i>
                                                <p style="margin: 0; font-size: 14px;">Belum ada detail harga barang</p>
                                                <small style="color: #cbd5e0;">Tambahkan detail harga dengan mengisi form
                                                    di atas</small>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>


                            <input type="hidden" name="detail_barang" id="detailBarangData">

                            <div class="form-group mt-4 text-right">
                                <a href="{{ route('barang.index') }}" class="btn btn-cancel">
                                    <i class="ft-x"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-save">
                                    <i class="ft-save"></i> Simpan Data Barang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Jenis Barang -->
    <div class="modal fade" id="modalJenisBarang" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jenis Barang</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formJenisBarang">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Kode <span class="badge-required">Wajib</span></label>
                            <input type="text" class="form-control" name="kode" required placeholder="Contoh: JB001">
                        </div>
                        <div class="form-group">
                            <label>Nama <span class="badge-required">Wajib</span></label>
                            <input type="text" class="form-control" name="nama" required placeholder="Masukkan nama jenis barang">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Merek -->
    <div class="modal fade" id="modalMerek" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Merek</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formMerek">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nama <span class="badge-required">Wajib</span></label>
                            <input type="text" class="form-control" name="nama" required placeholder="Masukkan nama merek">
                        </div>
                        <div class="form-group">
                            <label>Deskripsi</label>
                            <textarea class="form-control" name="deskripsi" rows="3" placeholder="Deskripsi merek (opsional)"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Supplier -->
    <div class="modal fade" id="modalSupplier" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Supplier</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formSupplier">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nama <span class="badge-required">Wajib</span></label>
                                    <input type="text" class="form-control" name="nama" required placeholder="Masukkan nama supplier">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <input type="text" class="form-control" name="alamat" placeholder="Masukkan alamat supplier">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Kota</label>
                                    <input type="text" class="form-control" name="kota" placeholder="Masukkan kota">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>No. HP</label>
                                    <input type="text" class="form-control" name="no_hp" placeholder="Masukkan nomor HP">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" class="form-control" name="email" placeholder="Masukkan email">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Contact Person</label>
                                    <input type="text" class="form-control" name="contact_person" placeholder="Masukkan nama contact person">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Telepon Contact Person</label>
                                    <input type="text" class="form-control" name="telepon_contact_person" placeholder="Masukkan telepon contact person">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <input type="text" class="form-control" name="keterangan" placeholder="Keterangan supplier">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Satuan -->
    <div class="modal fade" id="modalSatuan" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Satuan</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formSatuan">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Kode <span class="badge-required">Wajib</span></label>
                            <input type="text" class="form-control" name="kode" required placeholder="Contoh: PCS">
                        </div>
                        <div class="form-group">
                            <label>Nama <span class="badge-required">Wajib</span></label>
                            <input type="text" class="form-control" name="nama" required placeholder="Masukkan nama satuan">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Select2 JS -->
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}"></script>
    <script>
        let detailBarang = [];
        let detailCounter = 0;

        // Constants
        const SWAL_COLORS = {
            primary: '#667eea',
            success: '#48bb78',
            error: '#f56565'
        };

        $(document).ready(function() {
            // Initialize form data for edit mode
            initializeFormData();

            // Event listeners
            setupEventListeners();
        });

        // Initialize form data and load existing details for edit mode
        function initializeFormData() {
            @if (isset($barang))
                loadFormFields();
                loadExistingPhoto();
                loadExistingDetails();
            @endif
        }

        // Load form fields with existing data
        function loadFormFields() {
            $('#kode').val('{{ $barang->kode ?? '' }}');
            $('#nama_barang').val('{{ $barang->nama_barang ?? '' }}');

            // Handle jenis barang selection for edit mode with remote data loading
            @if(isset($barang) && $barang->jenisBarang)
                // For remote data loading, create option and set value
                const jenisOption = new Option('{{ $barang->jenisBarang->nama }}', '{{ $barang->jenis_barang_id }}', true, true);
                $('#jenis_barang_id').append(jenisOption).trigger('change');
            @endif

            // Handle merek selection for edit mode with remote data loading
            @if(isset($barang) && $barang->merek)
                // For remote data loading, create option and set value
                const merekOption = new Option('{{ $barang->merek->nama }}', '{{ $barang->merek_id }}', true, true);
                $('#merek_id').append(merekOption).trigger('change');
            @endif

            // Handle supplier selection for edit mode with remote data loading
            @if(isset($barang) && $barang->supplier)
                // For remote data loading, create option and set value
                const supplierOption = new Option('{{ $barang->supplier->nama }}', '{{ $barang->supplier_id }}', true, true);
                $('#supplier_id').append(supplierOption).trigger('change');
            @endif

            $('#keterangan').val('{{ $barang->keterangan ?? '' }}');
        }

        // Load existing photo
        function loadExistingPhoto() {
            @if (isset($barang) && $barang->foto)
                showImagePreview('{{ Storage::url($barang->foto) }}');
            @endif
        }

        // Load existing detail barang
        function loadExistingDetails() {
            @if (isset($barang) && $barang->detailBarang->count() > 0)
                @foreach ($barang->detailBarang as $detail)
                    detailCounter++;
                    detailBarang.push(createDetailObject(
                        detailCounter,
                        {{ $detail->satuan_id }},
                        '{{ $detail->satuan->nama }}',
                        {{ $detail->isi }},
                        {{ $detail->harga_beli }},
                        {{ $detail->harga_jual }},
                        '{{ $barang->kode }}',
                        '{{ $barang->nama_barang }}',
                        {{ $barang->jenis_barang_id }},
                        {{ $barang->merek_id }},
                        {{ $barang->supplier_id }},
                        '{{ $barang->keterangan ?? '' }}'
                    ));
                @endforeach
                renderDetailTable();
            @endif
        }

        // Setup all event listeners
        function setupEventListeners() {
            // Initialize Select2
            initializeSelect2();

            // Hapus semua event listener yang mungkin sudah ada sebelumnya
            $('#foto').off('change').on('change', handleFileChange);
            $('#imagePreviewContainer').off('click').on('click', handleImageContainerClick);
            $('#btnTambahDetail').off('click').on('click', handleAddDetail);
            $('#detail_satuan_id, #detail_isi, #detail_harga_beli, #detail_harga_jual').off('keypress').on('keypress', handleEnterKey);
            $('#formBarang').off('submit').on('submit', handleFormSubmit);

            // Modal forms
            $('#formJenisBarang').off('submit').on('submit', handleJenisBarangSubmit);
            $('#formMerek').off('submit').on('submit', handleMerekSubmit);
            $('#formSupplier').off('submit').on('submit', handleSupplierSubmit);
            $('#formSatuan').off('submit').on('submit', handleSatuanSubmit);

            // Format harga input dengan ribuan
            $('#detail_harga_beli, #detail_harga_jual').off('input blur keypress').on('input', handlePriceInput);
            $('#detail_harga_beli, #detail_harga_jual').on('blur', formatPriceOnBlur);
            $('#detail_harga_beli, #detail_harga_jual').on('keypress', validateNumericInput);
        }

        // Initialize Select2 for all select elements
        function initializeSelect2() {
            // Jenis Barang Select2 with Remote Data Loading
            $('#jenis_barang_id').select2({
                placeholder: '-- Pilih Jenis Barang --',
                allowClear: true,
                width: '100%',
                theme: 'default',
                ajax: {
                    url: '{{ route("barang.getJenisBarang") }}',
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: false
                },
                minimumInputLength: 1,
                language: {
                    noResults: function() {
                        return "Tidak ada hasil ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    },
                    inputTooShort: function() {
                        return "Ketik minimal 1 karakter untuk mencari";
                    }
                }
            });

            // Merek Select2 with Remote Data Loading
            $('#merek_id').select2({
                placeholder: '-- Pilih Merek --',
                allowClear: true,
                width: '100%',
                theme: 'default',
                ajax: {
                    url: '{{ route("barang.getMerek") }}',
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: false
                },
                minimumInputLength: 1,
                language: {
                    noResults: function() {
                        return "Tidak ada hasil ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    },
                    inputTooShort: function() {
                        return "Ketik minimal 1 karakter untuk mencari";
                    }
                }
            });

            // Supplier Select2 with Remote Data Loading
            $('#supplier_id').select2({
                placeholder: '-- Pilih Supplier --',
                allowClear: true,
                width: '100%',
                theme: 'default',
                ajax: {
                    url: '{{ route("barang.getSuppliers") }}',
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: false
                },
                minimumInputLength: 1,
                language: {
                    noResults: function() {
                        return "Tidak ada hasil ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    },
                    inputTooShort: function() {
                        return "Ketik minimal 1 karakter untuk mencari";
                    }
                }
            });

            // Satuan Select2 with Remote Data Loading
            $('#detail_satuan_id').select2({
                placeholder: '-- Pilih Satuan --',
                allowClear: true,
                width: '100%',
                theme: 'default',
                ajax: {
                    url: '{{ route("barang.getSatuan") }}',
                    dataType: 'json',
                    delay: 300,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: {
                                more: data.pagination.more
                            }
                        };
                    },
                    cache: false
                },
                minimumInputLength: 1,
                language: {
                    noResults: function() {
                        return "Tidak ada hasil ditemukan";
                    },
                    searching: function() {
                        return "Mencari...";
                    },
                    inputTooShort: function() {
                        return "Ketik minimal 1 karakter untuk mencari";
                    }
                }
            });
        }

        // Handle image container click
        function handleImageContainerClick(e) {
            e.preventDefault();
            e.stopPropagation();
            $('#foto')[0].click(); // Gunakan DOM click() bukan jQuery trigger()
        }        // Handle file upload
        function handleFileChange(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (!validateFileSize(file)) {
                $(this).val('');
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => showImagePreview(e.target.result);
            reader.readAsDataURL(file);
        }

        // Validate file size (max 2MB)
        function validateFileSize(file) {
            if (file.size > 2 * 1024 * 1024) {
                showAlert('error', 'File Terlalu Besar', 'Ukuran file maksimal 2MB');
                return false;
            }
            return true;
        }

        // Show image preview
        function showImagePreview(src) {
            $('#imagePreview').attr('src', src).show();
            $('#placeholderText').hide();
        }

        // Handle add detail button
        function handleAddDetail() {
            const detailData = getDetailFormData();
            const barangData = getBarangFormData();

            // Validate all data
            if (!validateBarangData(barangData) || !validateDetailData(detailData)) {
                return;
            }

            // Create and add detail
            detailCounter++;
            const detail = createDetailObject(
                detailCounter,
                detailData.satuanId,
                detailData.satuanText,
                detailData.isi,
                detailData.hargaBeli,
                detailData.hargaJual,
                barangData.kode,
                barangData.namaBarang,
                barangData.jenisBarangId,
                barangData.merekId,
                barangData.supplierId,
                barangData.keterangan
            );

            detailBarang.push(detail);
            renderDetailTable();

            showAlert('success', 'Berhasil!', 'Detail harga berhasil ditambahkan', 1500);
            resetDetailForm();
        }

        // Get detail form data
        function getDetailFormData() {
            return {
                satuanId: $('#detail_satuan_id').val(),
                satuanText: $('#detail_satuan_id option:selected').text(),
                isi: parseInt($('#detail_isi').val()) || 0,
                hargaBeli: getNumericValue($('#detail_harga_beli').val()),
                hargaJual: getNumericValue($('#detail_harga_jual').val())
            };
        }

        // Get barang form data
        function getBarangFormData() {
            return {
                kode: $('#kode').val().trim(),
                namaBarang: $('#nama_barang').val().trim(),
                jenisBarangId: $('#jenis_barang_id').val(),
                merekId: $('#merek_id').val(),
                supplierId: $('#supplier_id').val(),
                keterangan: $('#keterangan').val().trim()
            };
        }

        // Create detail object
        function createDetailObject(id, satuanId, satuanText, isi, hargaBeli, hargaJual, kode, namaBarang, jenisBarangId, merekId, supplierId, keterangan) {
            return {
                id: id,
                satuan_id: satuanId,
                satuan_text: satuanText,
                isi: isi,
                harga_beli: hargaBeli,
                harga_jual: hargaJual,
                kode_barang: kode,
                nama_barang: namaBarang,
                jenis_barang_id: jenisBarangId,
                merek_id: merekId,
                supplier_id: supplierId,
                keterangan: keterangan
            };
        }


        // Handle enter key on detail form
        function handleEnterKey(e) {
            if (e.which === 13) {
                e.preventDefault();
                handleAddDetail();
            }
        }

        // Validate barang data
        function validateBarangData(data) {
            if (!data.kode || !data.namaBarang || !data.jenisBarangId || !data.merekId || !data.supplierId) {
                showAlert('warning', 'Data Barang Belum Lengkap', 'Silakan lengkapi data barang terlebih dahulu (Kode, Nama, Jenis, Merek, Supplier)!');
                return false;
            }
            return true;
        }

        // Validate detail data
        function validateDetailData(data) {
            const validations = [
                { condition: !data.satuanId, message: 'Silakan pilih satuan terlebih dahulu!', focus: '#detail_satuan_id' },
                { condition: data.isi <= 0, message: 'Jumlah harus lebih dari 0!', focus: '#detail_isi' },
                { condition: data.hargaBeli <= 0, message: 'Harga beli harus lebih dari 0!', focus: '#detail_harga_beli' },
                { condition: data.hargaJual <= 0, message: 'Harga jual harus lebih dari 0!', focus: '#detail_harga_jual' },
                { condition: data.hargaJual < data.hargaBeli, message: 'Harga jual tidak boleh lebih kecil dari harga beli!', focus: '#detail_harga_jual' }
            ];

            for (let validation of validations) {
                if (validation.condition) {
                    showAlert('warning', 'Data Tidak Valid', validation.message);
                    if (validation.focus) $(validation.focus).focus();
                    return false;
                }
            }
            return true;
        }

        // Reset detail form
        function resetDetailForm() {
            $('#detail_satuan_id, #detail_isi, #detail_harga_beli, #detail_harga_jual').val('');
            $('#detail_satuan_id').focus();
        }

        // Show SweetAlert with consistent styling
        function showAlert(icon, title, text, timer = null) {
            const config = {
                icon: icon,
                title: title,
                text: text,
                confirmButtonColor: SWAL_COLORS.primary
            };

            if (timer) {
                config.timer = timer;
                config.showConfirmButton = false;
            }

            Swal.fire(config);
        }

        // Handle form submit
        function handleFormSubmit(e) {
            e.preventDefault();

            if (!validateFormSubmit()) return false;

            showLoadingAlert();
            submitForm(this);
        }

        // Validate form before submit
        function validateFormSubmit() {
            if (detailBarang.length === 0) {
                showAlert('error', 'Detail Harga Kosong', 'Tambahkan minimal 1 detail harga barang!');
                return false;
            }

            const barangData = getBarangFormData();
            if (!validateBarangData(barangData)) {
                return false;
            }

            return true;
        }

        // Show loading alert
        function showLoadingAlert() {
            Swal.fire({
                title: 'Menyimpan Data...',
                html: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });
        }

        // Submit form via AJAX
        function submitForm(form) {
            const formData = new FormData(form);
            formData.append('detail_barang', JSON.stringify(detailBarang));

            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: handleSubmitSuccess,
                error: handleSubmitError
            });
        }

        // Handle submit success
        function handleSubmitSuccess(response) {
            Swal.fire({
                title: 'Berhasil!',
                text: response.message || 'Data barang berhasil disimpan',
                icon: 'success',
                confirmButtonColor: SWAL_COLORS.success,
                confirmButtonText: '<i class="ft-check"></i> OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('barang.index') }}';
                }
            });
        }

        // Handle submit error
        function handleSubmitError(xhr) {
            let errorMessage = 'Terjadi kesalahan saat menyimpan data';

            if (xhr.status === 422 && xhr.responseJSON?.errors) {
                errorMessage = '<ul style="text-align: left; margin: 0;">';
                $.each(xhr.responseJSON.errors, function(key, value) {
                    errorMessage += '<li>' + value[0] + '</li>';
                });
                errorMessage += '</ul>';
            } else if (xhr.responseJSON?.message) {
                errorMessage = xhr.responseJSON.message;
            }

            Swal.fire({
                title: 'Gagal!',
                html: errorMessage,
                icon: 'error',
                confirmButtonColor: SWAL_COLORS.error
            });
        }

        // Handle jenis barang submit
        function handleJenisBarangSubmit(e) {
            e.preventDefault();
            const form = $(this);
            const data = form.serialize();

            $.post('{{ route("jenisbarang.store") }}', data, function(response) {
                // Add new option to select
                const newOption = new Option(response.data.nama, response.data.id, false, true);
                $('#jenis_barang_id').append(newOption).trigger('change');

                $('#modalJenisBarang').modal('hide');
                form[0].reset();
                showAlert('success', 'Berhasil', 'Jenis barang berhasil ditambahkan');
            }).fail(function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                if (xhr.responseJSON?.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                showAlert('error', 'Gagal', errorMessage);
            });
        }

        // Handle merek submit
        function handleMerekSubmit(e) {
            e.preventDefault();
            const form = $(this);
            const data = form.serialize();

            $.post('{{ route("merek.store") }}', data, function(response) {
                // Add new option to select
                const newOption = new Option(response.data.nama, response.data.id, false, true);
                $('#merek_id').append(newOption).trigger('change');

                $('#modalMerek').modal('hide');
                form[0].reset();
                showAlert('success', 'Berhasil', 'Merek berhasil ditambahkan');
            }).fail(function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                if (xhr.responseJSON?.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                showAlert('error', 'Gagal', errorMessage);
            });
        }

        // Handle supplier submit
        function handleSupplierSubmit(e) {
            e.preventDefault();
            const form = $(this);
            const data = form.serialize();

            $.post('{{ route("supplier.store") }}', data, function(response) {
                // For remote data loading, add the new option to select2
                const newOption = new Option(response.data.nama, response.data.id, false, true);
                $('#supplier_id').append(newOption).trigger('change');

                $('#modalSupplier').modal('hide');
                form[0].reset();
                showAlert('success', 'Berhasil', 'Supplier berhasil ditambahkan');
            }).fail(function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                if (xhr.responseJSON?.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                showAlert('error', 'Gagal', errorMessage);
            });
        }

        // Handle satuan submit
        function handleSatuanSubmit(e) {
            e.preventDefault();
            const form = $(this);
            const data = form.serialize();

            $.post('{{ route("satuan.store") }}', data, function(response) {
                // Add new option to select
                const newOption = new Option(response.data.nama, response.data.id, false, true);
                $('#detail_satuan_id').append(newOption).trigger('change');

                $('#modalSatuan').modal('hide');
                form[0].reset();
                showAlert('success', 'Berhasil', 'Satuan berhasil ditambahkan');
            }).fail(function(xhr) {
                let errorMessage = 'Terjadi kesalahan saat menyimpan data';
                if (xhr.responseJSON?.errors) {
                    errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                }
                showAlert('error', 'Gagal', errorMessage);
            });
        }


        // Reset entire form (public function for external use)
        window.resetFormBarang = function() {
            resetAllFields();
            detailBarang = [];
            detailCounter = 0;
            renderDetailTable();
            $('#kode').focus();
        };

        // Reset all form fields
        function resetAllFields() {
            $('#kode, #nama_barang, #jenis_barang_id, #merek_id, #supplier_id, #keterangan').val('');
            resetDetailForm();
            resetPhotoPreview();
        }

        // Reset photo preview
        function resetPhotoPreview() {
            $('#foto').val('');
            $('#imagePreview').hide().attr('src', '');
            $('#placeholderText').show();
        }

        // Render detail table with animation
        function renderDetailTable() {
            const tbody = $('#detailBarangContainer');

            tbody.fadeOut(200, function() {
                tbody.html(detailBarang.length === 0 ? getEmptyStateHTML() : getTableRowsHTML());
                tbody.fadeIn(200);
                updateHiddenInput();
            });
        }

        // Get empty state HTML
        function getEmptyStateHTML() {
            return `
                <tr>
                    <td colspan="6" class="empty-state">
                        <i class="ft-inbox" style="font-size: 48px; color: #cbd5e0; display: block; margin-bottom: 10px;"></i>
                        <p style="margin: 0; font-size: 14px;">Belum ada detail harga barang</p>
                        <small style="color: #cbd5e0;">Tambahkan detail harga dengan mengisi form di atas</small>
                    </td>
                </tr>
            `;
        }

        // Get table rows HTML
        function getTableRowsHTML() {
            return detailBarang.map((detail, index) => {
                return `
                    <tr style="animation: fadeIn 0.3s ease-in-out;">
                        <td class="text-center"><strong>${index + 1}</strong></td>
                        <td><span class="badge badge-info" style="font-size: 12px; padding: 5px 10px;">${detail.satuan_text}</span></td>
                        <td class="text-center"><strong>${detail.isi}</strong></td>
                        <td class="text-right">Rp ${formatRupiah(detail.harga_beli)}</td>
                        <td class="text-right"><strong>Rp ${formatRupiah(detail.harga_jual)}</strong></td>
                        <td class="text-center">
                            <button type="button" class="btn btn-sm btn-edit mr-1" onclick="editDetail(${detail.id})" title="Edit">
                                <i class="ft-edit"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-delete" onclick="hapusDetail(${detail.id})" title="Hapus">
                                <i class="ft-trash-2"></i>
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }



        // Update hidden input with current detail data
        function updateHiddenInput() {
            $('#detailBarangData').val(JSON.stringify(detailBarang));
        }

        // Format currency to Indonesian Rupiah
        function formatRupiah(angka) {
            return parseFloat(angka).toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 2
            });
        }

        // Format harga input dengan ribuan
        function formatPriceInput(value) {
            // Hapus semua karakter non-digit
            const cleanValue = value.toString().replace(/\D/g, '');

            if (cleanValue === '') return '';

            // Format dengan ribuan
            return parseInt(cleanValue).toLocaleString('id-ID');
        }

        // Handle input harga dengan format ribuan
        function handlePriceInput(e) {
            const input = $(this);
            const inputElement = input[0];
            const oldValue = input.val();
            const newValue = formatPriceInput(oldValue);

            // Only proceed if value actually changed
            if (oldValue !== newValue) {
                const cursorPosition = inputElement.selectionStart || 0;
                input.val(newValue);

                // Restore cursor position (only for text inputs that support selection)
                if (inputElement.type === 'text' && inputElement.setSelectionRange) {
                    try {
                        const newCursorPosition = cursorPosition + (newValue.length - oldValue.length);
                        inputElement.setSelectionRange(newCursorPosition, newCursorPosition);
                    } catch (error) {
                        // Ignore cursor positioning errors
                        console.log('Cursor positioning not supported');
                    }
                }
            }
        }

        // Format harga saat blur (kehilangan focus)
        function formatPriceOnBlur(e) {
            const input = $(this);
            const value = input.val();

            if (value) {
                input.val(formatPriceInput(value));
            }
        }

        // Validasi input numerik only
        function validateNumericInput(e) {
            const charCode = e.which || e.keyCode;

            // Allow: backspace, delete, tab, escape, enter
            if ([8, 9, 27, 13, 46].indexOf(charCode) !== -1 ||
                // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (charCode === 65 && e.ctrlKey) ||
                (charCode === 67 && e.ctrlKey) ||
                (charCode === 86 && e.ctrlKey) ||
                (charCode === 88 && e.ctrlKey)) {
                return;
            }

            // Allow only numbers (0-9)
            if (charCode < 48 || charCode > 57) {
                e.preventDefault();
            }
        }

        // Ambil nilai numerik dari harga yang diformat
        function getNumericValue(formattedValue) {
            if (!formattedValue) return 0;
            return parseFloat(formattedValue.toString().replace(/\./g, '')) || 0;
        }

        // Edit detail function (global)
        window.editDetail = function(id) {
            const detail = detailBarang.find(d => d.id === id);

            if (!detail) {
                showAlert('error', 'Data Tidak Ditemukan', 'Detail yang akan diedit tidak ditemukan!');
                return;
            }

            populateDetailForm(detail);
            removeDetailFromArray(id);
            showAlert('info', 'Mode Edit', 'Data detail telah dimuat ke form. Silakan edit dan klik "Tambahkan ke Tabel" untuk menyimpan perubahan.', 3000);
        };

        // Delete detail function (global)
        window.hapusDetail = function(id) {
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: 'Apakah Anda yakin ingin menghapus detail ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: SWAL_COLORS.error,
                cancelButtonColor: '#a0aec0',
                confirmButtonText: '<i class="ft-trash-2"></i> Ya, Hapus!',
                cancelButtonText: '<i class="ft-x"></i> Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    removeDetailFromArray(id);
                    showAlert('success', 'Terhapus!', 'Detail harga telah dihapus', 1500);
                }
            });
        };

        // Populate detail form with existing data
        function populateDetailForm(detail) {
            // Handle satuan selection for edit mode with remote data loading
            if (detail.satuan_id && detail.satuan_text) {
                const satuanOption = new Option(detail.satuan_text, detail.satuan_id, true, true);
                $('#detail_satuan_id').append(satuanOption).trigger('change');
            }

            $('#detail_isi').val(detail.isi);
            $('#detail_harga_beli').val(formatPriceInput(detail.harga_beli));
            $('#detail_harga_jual').val(formatPriceInput(detail.harga_jual));
            $('#detail_satuan_id').focus();
        }

        // Remove detail from array and update table
        function removeDetailFromArray(id) {
            detailBarang = detailBarang.filter(d => d.id !== id);
            renderDetailTable();
        }
    </script>
@endsection
