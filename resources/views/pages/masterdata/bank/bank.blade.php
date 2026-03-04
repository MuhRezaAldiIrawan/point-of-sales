@extends('layouts.main')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/css/tables/datatable/datatables.min.css') }}">
@endsection
@section('content')
    <section id="bootstrap3">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Daftar {{ $title }}</h4>
                        <div class="heading-elements">
                            <button type="button" class="btn btn-primary btn-md d-flex align-items-center"
                                id="btnTambahBank">
                                <i class="la la-plus mr-1"></i> Tambah {{ $title }}
                            </button>
                        </div>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">

                            <table class="table table-striped table-bordered" id="bank-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama Bank</th>
                                        <th class="text-center">No. Rekening</th>
                                        <th class="text-center">Atas Nama</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('pages.masterdata.bank._partials.form')
@endsection

@section('js')
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('app-assets/js/scripts/tables/datatables/datatable-basic.min.js') }}" type="text/javascript"></script>

    <script>
        var table;

        $(document).ready(function() {
            table = $('#bank-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('bank.index') }}",
                "columns": [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'nama',
                        name: 'nama'
                    },
                    {
                        data: 'no_rekening',
                        name: 'no_rekening'
                    },
                    {
                        data: 'atas_nama',
                        name: 'atas_nama'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                "language": {
                    "lengthMenu": "Tampilkan _MENU_ data per halaman",
                    "zeroRecords": "Data tidak ditemukan",
                    "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                    "infoEmpty": "Tidak ada data yang tersedia",
                    "infoFiltered": "(difilter dari _MAX_ total data)",
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Selanjutnya",
                        "previous": "Sebelumnya"
                    }
                },
                "pageLength": 10,
                "order": [
                    [0, "desc"]
                ],
                "scrollX": true,
                "columnDefs": [{
                    "width": "5%",
                    "targets": 0
                }]
            });

            // Handle form submit
            $('#formBank').on('submit', function(e) {
                e.preventDefault();

                var bankId = $('#bank_id').val();
                var url = bankId ? "/bank/" + bankId : "{{ route('bank.store') }}";
                var method = bankId ? "PUT" : "POST";

                $.ajax({
                    url: url,
                    type: method,
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#inlineForm').modal('hide');
                            $('#formBank')[0].reset();
                            $('#bank_id').val(''); // Reset ID setelah submit
                            table.ajax.reload();

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 2000
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
                                text: errors.message || 'Terjadi kesalahan saat menyimpan data',
                                confirmButtonText: 'OK'
                            });
                        }
                    }
                });
            });

        });

        $('#btnTambahBank').on('click', function() {
            $('#modalTitle').text('Tambah Bank');
            $('#formBank')[0].reset();
            $('#bank_id').val('');
            $('#inlineForm').modal('show');
        });


        $(document).on('click', '.btn-bank-edit', function(e) {
            e.preventDefault();
            let id = $(this).data('id');

            $.ajax({
                url: "/bank/edit/" + id,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $('#modalTitle').text('Edit Bank');
                        $('#bank_id').val(response.data.id);
                        $('#nama').val(response.data.nama);
                        $('#no_rekening').val(response.data.no_rekening);
                        $('#atas_nama').val(response.data.atas_nama);
                        $('#inlineForm').modal('show');
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Gagal mengambil data bank',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        $(document).on('click', '.btn-bank-delete', function(e) {
            e.preventDefault();
            let id = $(this).data('id');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/bank/" + id,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function(response) {
                            if (response.success) {
                                table.ajax.reload();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        },
                        error: function(xhr) {
                            console.error(xhr);
                            var errorMsg = 'Terjadi kesalahan saat menghapus data';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: errorMsg,
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });

    </script>
@endsection
