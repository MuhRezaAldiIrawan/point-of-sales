@extends('layouts.main')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/forms/selects/select2.min.css') }}">
    <style>
        .barang-item {
            background: linear-gradient(to right, #f8f9fa 0%, #ffffff 100%);
            border-left: 4px solid #007bff !important;
            transition: all 0.3s ease;
        }

        .barang-item:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .form-section {
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        #detail-barang-section {
            animation: fadeIn 0.5s;
        }

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

        .form-actions {
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
            margin-top: 20px;
        }

        #barang-item-template {
            display: none !important;
        }

        .barang-item-footer {
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px dashed #cfd8dc;
        }
    </style>
@endsection

@section('content')
    <section id="basic-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="basic-layout-form">{{ isset($daftarHarga) ? 'Edit' : 'Tambah' }} Daftar
                            Harga</h4>
                        <a class="heading-elements-toggle"><i class="ft-more-horizontal font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a href="{{ route('daftarharga.index') }}"><i class="ft-arrow-left"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> {{ session('error') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <form
                                action="{{ isset($daftarHarga) ? route('daftarharga.update', $daftarHarga->id) : route('daftarharga.store') }}"
                                method="POST" class="form">
                                @csrf
                                @if (isset($daftarHarga))
                                    @method('PUT')
                                @endif

                                <div class="form-body">
                                    <h4 class="form-section"><i class="ft-file-text"></i> Informasi Daftar Harga</h4>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nama">Nama Daftar Harga <span
                                                        class="text-danger">*</span></label>
                                                <input type="text" id="nama"
                                                    class="form-control @error('nama') is-invalid @enderror" name="nama"
                                                    value="{{ old('nama', $daftarHarga->nama ?? '') }}"
                                                    placeholder="Contoh: Harga Member, Harga Grosir" required>
                                                @error('nama')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="status">Tipe Harga <span class="text-danger">*</span></label>
                                                <select id="status"
                                                    class="form-control @error('status') is-invalid @enderror"
                                                    name="status" required>
                                                    <option value="">Pilih Tipe</option>
                                                    <option value="all barang"
                                                        {{ old('status', $daftarHarga->status ?? '') == 'all barang' ? 'selected' : '' }}>
                                                        Semua Barang
                                                    </option>
                                                    <option value="pcs"
                                                        {{ old('status', $daftarHarga->status ?? '') == 'pcs' ? 'selected' : '' }}>
                                                        Per Satuan
                                                    </option>
                                                </select>
                                                @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="is_active">Status Aktif <span
                                                        class="text-danger">*</span></label>
                                                <select id="is_active"
                                                    class="form-control @error('is_active') is-invalid @enderror"
                                                    name="is_active" required>
                                                    <option value="1"
                                                        {{ (string) old('is_active', $daftarHarga->is_active ?? 1) === '1' ? 'selected' : '' }}>
                                                        Aktif
                                                    </option>
                                                    <option value="0"
                                                        {{ (string) old('is_active', $daftarHarga->is_active ?? 1) === '0' ? 'selected' : '' }}>
                                                        Nonaktif
                                                    </option>
                                                </select>
                                                @error('is_active')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="diskon">Diskon Global (%)</label>
                                                <div class="input-group">
                                                    <input type="number" id="diskon"
                                                        class="form-control @error('diskon') is-invalid @enderror"
                                                        name="diskon"
                                                        value="{{ old('diskon', $daftarHarga->diskon ?? 0) }}"
                                                        placeholder="0" step="0.01" min="0" max="100">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">%</span>
                                                    </div>
                                                </div>
                                                @error('diskon')
                                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div id="detail-barang-section" style="display: none;">
                                        <h4 class="form-section mt-2"><i class="ft-package"></i> Detail Barang</h4>

                                        <div id="barang-list" class="mb-2"></div>

                                        <button type="button" class="btn btn-primary btn-sm" id="add-barang-btn">
                                            <i class="ft-plus"></i> Tambah Barang
                                        </button>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ft-check-square"></i> Simpan
                                    </button>
                                    <a href="{{ route('daftarharga.index') }}" class="btn btn-secondary">
                                        <i class="ft-x"></i> Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="barang-item-template" style="display: none;"></div>
@endsection

@section('js')
    <script src="{{ asset('app-assets/vendors/js/forms/select/select2.full.min.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            let barangIndex = 0;

            function formatRupiah(angka) {
                const value = parseInt(angka, 10);
                if (isNaN(value)) {
                    return '';
                }
                return value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function unformatRupiah(value) {
                return (value || '').replace(/\./g, '');
            }

            $(document).on('input', '.harga-jual', function() {
                const value = $(this).val().replace(/\D/g, '');
                $(this).val(value ? formatRupiah(value) : '');
            });

            function toggleDiskonGlobal() {
                const status = $('#status').val();
                const diskonCol = $('#diskon').closest('.col-md-3');
                if (status === 'all barang') {
                    diskonCol.show();
                } else {
                    diskonCol.hide();
                }
            }

            function destroyAllSelect2() {
                $('#barang-list .barang-select').each(function() {
                    if ($(this).hasClass('select2-hidden-accessible')) {
                        $(this).select2('destroy');
                    }
                });
            }

            $('#status').on('change', function() {
                toggleDiskonGlobal();
                if ($(this).val() === 'pcs') {
                    $('#detail-barang-section').slideDown(300);
                } else {
                    $('#detail-barang-section').slideUp(300);
                    destroyAllSelect2();
                    $('#barang-list').empty();
                    barangIndex = 0;
                }
            });

            toggleDiskonGlobal();

            if ($('#status').val() === 'pcs') {
                $('#detail-barang-section').show();
            }

            function createBarangRow(index) {
                return `
                <div class="barang-item border rounded p-2 mb-3 bg-light" data-index="${index}">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">Barang <span class="text-danger">*</span></label>
                                <select name="detail[${index}][barang_id]" class="form-control barang-select" required>
                                    <option value="">-- Cari Nama / Kode Barang --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">Satuan <span class="text-danger">*</span></label>
                                <select name="detail[${index}][satuan_id]" class="form-control satuan-select" required>
                                    <option value="">-- Pilih --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">Harga Jual <span class="text-danger">*</span></label>
                                <input type="text" name="detail[${index}][harga_jual]" class="form-control harga-jual" placeholder="0" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">Diskon (%)</label>
                                <input type="number" name="detail[${index}][diskon]" class="form-control" step="0.01" min="0" max="100" placeholder="0" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row barang-item-footer align-items-end">
                        <div class="col-md-3 ml-auto">
                            <div class="form-group mb-0">
                                <label class="font-weight-bold">Status Aktif</label>
                                <select name="detail[${index}][is_active]" class="form-control">
                                    <option value="1">Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group mb-0">
                                <button type="button" class="btn btn-danger btn-sm btn-block remove-barang-btn">
                                    <i class="ft-trash-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>`;
            }

            function initRowSelect2(row) {
                const select = row.find('.barang-select');

                select.select2({
                    placeholder: '-- Cari Nama / Kode Barang --',
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $('body'),
                    ajax: {
                        url: '{{ route('daftarharga.getBarangs') }}',
                        dataType: 'json',
                        delay: 300,
                        data: function(params) {
                            return {
                                q: params.term,
                                page: params.page || 1
                            };
                        },
                        processResults: function(data) {
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
                            return 'Barang tidak ditemukan';
                        },
                        searching: function() {
                            return 'Mencari...';
                        },
                        inputTooShort: function() {
                            return 'Ketik minimal 1 karakter untuk mencari';
                        }
                    }
                });

                select.on('change', function() {
                    handleBarangChange($(this).closest('.barang-item'));
                });
            }

            function populateRowSatuan(satuanSelect, satuans, selectedSatuanId = null) {
                satuanSelect.html('<option value="">-- Pilih --</option>');

                satuans.forEach(function(satuan) {
                    const selected = selectedSatuanId && String(selectedSatuanId) === String(satuan.id);
                    satuanSelect.append(
                        $('<option>', {
                            value: satuan.id,
                            text: satuan.nama,
                            'data-harga-jual': satuan.harga_jual,
                            selected: selected
                        })
                    );
                });

                if (!selectedSatuanId && satuans.length === 1) {
                    satuanSelect.find('option:eq(1)').prop('selected', true);
                }

                satuanSelect.trigger('change');
            }

            function handleBarangChange(row) {
                const select = row.find('.barang-select');
                const selectedData = select.select2('data');
                const satuanSelect = row.find('.satuan-select');

                if (selectedData && selectedData.length > 0 && selectedData[0].id) {
                    const item = selectedData[0];
                    populateRowSatuan(satuanSelect, item.satuans || []);
                } else {
                    satuanSelect.html('<option value="">-- Pilih --</option>');
                    row.find('.harga-jual').val('');
                }
            }

            $(document).on('change', '.satuan-select', function() {
                const row = $(this).closest('.barang-item');
                const hargaJual = $(this).find('option:selected').data('harga-jual');
                if (hargaJual !== undefined && hargaJual !== '') {
                    row.find('.harga-jual').val(formatRupiah(hargaJual));
                } else {
                    row.find('.harga-jual').val('');
                }
            });

            $('#add-barang-btn').on('click', function() {
                const html = createBarangRow(barangIndex);
                $('#barang-list').append(html);
                const newRow = $('#barang-list .barang-item:last');
                initRowSelect2(newRow);
                barangIndex++;
            });

            $(document).on('click', '.remove-barang-btn', function() {
                const row = $(this).closest('.barang-item');
                const select = row.find('.barang-select');
                if (select.hasClass('select2-hidden-accessible')) {
                    select.select2('destroy');
                }
                row.fadeOut(300, function() {
                    $(this).remove();
                });
            });

            @if (isset($daftarHarga) && $daftarHarga->status === 'pcs' && $daftarHarga->details->count() > 0)
                @php
                    $existingDetails = $daftarHarga->details->map(
                        fn($d) => [
                            'barang_id' => $d->barang_id,
                            'barang_text' => ($d->barang->nama_barang ?? '') . ' - ' . ($d->barang->kode ?? ''),
                            'satuans' => $d->barang->detailBarang
                                ->filter(fn($db) => !empty($db->satuan))
                                ->unique('satuan_id')
                                ->map(
                                    fn($db) => [
                                        'id' => $db->satuan_id,
                                        'nama' => $db->satuan->nama,
                                        'harga_jual' => (float) $db->harga_jual,
                                    ],
                                )
                                ->values(),
                            'satuan_id' => $d->satuan_id,
                            'harga_jual' => (float) $d->harga_jual,
                            'diskon' => (float) $d->diskon,
                            'is_active' => (int) $d->is_active,
                        ],
                    );
                @endphp
                const existingDetails = {!! json_encode($existingDetails) !!};

                existingDetails.forEach(function(detail) {
                    const index = barangIndex;
                    const html = createBarangRow(index);
                    $('#barang-list').append(html);

                    const row = $('#barang-list .barang-item:last');
                    initRowSelect2(row);

                    const barangSelect = row.find('.barang-select');
                    const option = new Option(detail.barang_text, detail.barang_id, true, true);
                    barangSelect.append(option).trigger('change.select2');

                    populateRowSatuan(row.find('.satuan-select'), detail.satuans, detail.satuan_id);

                    row.find('.harga-jual').val(detail.harga_jual ? formatRupiah(detail.harga_jual) : '');
                    row.find('input[name*="[diskon]"]').val(detail.diskon);
                    row.find('select[name*="[is_active]"]').val(String(detail.is_active));

                    barangIndex++;
                });
            @endif

            $('.form').on('submit', function(e) {
                e.preventDefault();

                $('.harga-jual').each(function() {
                    const rawValue = unformatRupiah($(this).val());
                    $(this).val(rawValue);
                });

                const formData = new FormData(this);
                const url = $(this).attr('action');

                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Mohon tunggu sebentar',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href =
                                    '{{ route('daftarharga.index') }}';
                            });
                        }
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON;
                        if (errors && errors.errors) {
                            let errorMessage = '';
                            $.each(errors.errors, function(key, value) {
                                errorMessage += value[0] + '<br>';
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Validasi Gagal',
                                html: errorMessage,
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: (errors && errors.message) ||
                                    'Terjadi kesalahan saat menyimpan data',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
