@extends('layouts.main')

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

                                        <div id="barang-list" class="mb-2">
                                        </div>

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

    <div id="barang-item-template" style="display: none;">
        <div class="barang-item border rounded p-3 mb-3 bg-light">
            <div class="row align-items-end">
                <div class="col-md-4">
                    <div class="form-group mb-md-0">
                        <label class="font-weight-bold">Barang <span class="text-danger">*</span></label>
                        <select name="detail[INDEX][barang_id]" class="form-control barang-select" required>
                            <option value="">Pilih Barang</option>
                            @foreach ($barangs ?? [] as $barang)
                                <option value="{{ $barang->id }}"
                                    data-harga-beli="{{ $barang->detailBarang->first()->harga_beli ?? 0 }}"
                                    data-harga-jual="{{ $barang->detailBarang->first()->harga_jual ?? 0 }}"
                                    data-satuan-id="{{ $barang->detailBarang->first()->satuan_id ?? '' }}"
                                    data-satuan-nama="{{ $barang->detailBarang->first()->satuan->nama ?? '' }}">
                                    {{ $barang->nama_barang }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group mb-md-0">
                        <label class="font-weight-bold">Satuan</label>
                        <select name="detail[INDEX][satuan_id]" class="form-control satuan-select" required>
                            <option value="">Pilih Satuan</option>
                            @foreach ($satuans ?? [] as $satuan)
                                <option value="{{ $satuan->id }}">{{ $satuan->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group mb-md-0">
                        <label class="font-weight-bold">Harga Jual</label>
                        <input type="text" name="detail[INDEX][harga_jual]" class="form-control harga-jual"
                            placeholder="0" required>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group mb-md-0">
                        <label class="font-weight-bold">Diskon (%)</label>
                        <input type="number" name="detail[INDEX][diskon]" class="form-control" step="0.01"
                            min="0" max="100" placeholder="0" value="0">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group mb-md-0">
                        <label class="font-weight-bold">Status Aktif</label>
                        <select name="detail[INDEX][is_active]" class="form-control" required>
                            <option value="1" selected>Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group mb-md-0">
                        <button type="button" class="btn btn-danger btn-block remove-barang-btn"
                            style="margin-top: 32px;">
                            <i class="ft-trash-2"></i> Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
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
    </style>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            let barangIndex = 0;

            function formatRupiah(angka) {
                return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            function unformatRupiah(value) {
                return value.replace(/\./g, '');
            }

            $(document).on('input', '.harga-jual', function() {
                let value = $(this).val().replace(/\D/g, ''); // remove non-digit
                if (value) {
                    $(this).val(formatRupiah(value));
                } else {
                    $(this).val('');
                }
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

            $('#status').on('change', function() {
                toggleDiskonGlobal();
                const status = $(this).val();
                console.log('Status changed to:', status);

                if (status === 'pcs') {
                    $('#detail-barang-section').slideDown(300);
                    console.log('Showing detail section');
                } else {
                    $('#detail-barang-section').slideUp(300);
                    $('#barang-list').empty();
                    barangIndex = 0;
                    console.log('Hiding detail section');
                }
            });

            // Initial toggle
            toggleDiskonGlobal();

            const initialStatus = $('#status').val();
            if (initialStatus === 'pcs') {
                $('#detail-barang-section').show();
            }

            @if (isset($daftarHarga) && $daftarHarga->status === 'pcs' && $daftarHarga->details->count() > 0)
                @foreach ($daftarHarga->details as $detail)
                    const template{{ $loop->index }} = $('#barang-item-template').html();
                    const newItem{{ $loop->index }} = template{{ $loop->index }}.replace(/INDEX/g,
                        {{ $loop->index }});
                    $('#barang-list').append(newItem{{ $loop->index }});

                    const item{{ $loop->index }} = $('#barang-list .barang-item:last');
                    item{{ $loop->index }}.find('.barang-select').val({{ $detail->barang_id }});
                    item{{ $loop->index }}.find('.satuan-select').val({{ $detail->satuan_id ?? '' }});
                    item{{ $loop->index }}.find('.harga-jual').val(formatRupiah({{ $detail->harga_jual }}));
                    item{{ $loop->index }}.find('input[name*="[diskon]"]').val({{ $detail->diskon }});
                    item{{ $loop->index }}.find('select[name*="[is_active]"]').val(
                        '{{ (int) ($detail->is_active ?? 1) }}');

                    barangIndex = {{ $loop->index + 1 }};
                @endforeach
            @endif

            $('#add-barang-btn').on('click', function() {
                console.log('Add button clicked');
                const template = $('#barang-item-template').html();
                console.log('Template:', template ? 'Found' : 'Not found');

                if (template) {
                    const newItem = template.replace(/INDEX/g, barangIndex);
                    $('#barang-list').append(newItem);
                    barangIndex++;
                    console.log('Item added, new index:', barangIndex);
                }
            });

            $(document).on('click', '.remove-barang-btn', function() {
                const item = $(this).closest('.barang-item');
                item.fadeOut(300, function() {
                    $(this).remove();
                });
            });

            $(document).on('change', '.barang-select', function() {
                const option = $(this).find('option:selected');
                const hargaBeli = option.data('harga-beli') || 0;
                const hargaJual = option.data('harga-jual') || 0;
                const satuanId = option.data('satuan-id') || '';
                const satuanNama = option.data('satuan-nama') || '';

                console.log('Barang changed:', {
                    barangId: $(this).val(),
                    satuanId: satuanId,
                    satuanNama: satuanNama
                });

                const item = $(this).closest('.barang-item');
                item.find('.harga-jual').val(formatRupiah(hargaJual));
                item.find('.satuan-select').val(satuanId).trigger('change');

                console.log('Satuan select set to:', satuanId);
            });

            $(document).on('blur', 'input[type="number"]', function() {
                const value = parseFloat($(this).val());
                if (!isNaN(value)) {
                    $(this).val(value.toFixed(2));
                }
            });

            $('.form').on('submit', function(e) {
                e.preventDefault();

                // Clean harga-jual values before submit
                $('.harga-jual').each(function() {
                    let rawValue = unformatRupiah($(this).val());
                    $(this).val(rawValue);
                });

                var formData = new FormData(this);
                var url = $(this).attr('action');
                var method = $(this).find('input[name="_method"]').val() || 'POST';

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
                                    "{{ route('daftarharga.index') }}";
                            });
                        }
                    },
                    error: function(xhr) {
                        var errors = xhr.responseJSON;
                        if (errors && errors.errors) {
                            var errorMessage = '';
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
                                text: errors.message ||
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
