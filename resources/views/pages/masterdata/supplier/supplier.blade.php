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
                                id="btnTambahSupplier">
                                <i class="la la-plus mr-1"></i> Tambah {{ $title }}
                            </button>
                        </div>
                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">

                            <table class="table table-striped table-bordered" id="supplier-table">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Nama Supplier</th>
                                        <th class="text-center">Alamat</th>
                                        <th class="text-center">Kota</th>
                                        <th class="text-center">No. Telepon</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">CP</th>
                                        <th class="text-center">No. CP</th>
                                        <th class="text-center">Keterangan</th>
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

    @include('pages.masterdata.supplier._partials.form')
@endsection

@section('js')
    <script src="{{ asset('app-assets/vendors/js/tables/datatable/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('app-assets/js/scripts/tables/datatables/datatable-basic.min.js') }}" type="text/javascript">
    </script>

    <script>
        var table;

        $(document).ready(function() {
            table = $('#supplier-table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('supplier.index') }}",
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
                        data: 'alamat',
                        name: 'alamat'
                    },
                    {
                        data: 'kota',
                        name: 'kota'
                    },
                    {
                        data: 'no_hp',
                        name: 'no_hp'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'contact_person',
                        name: 'contact_person'
                    },
                    {
                        data: 'telepon_contact_person',
                        name: 'telepon_contact_person'
                    },
                    {
                        data: 'keterangan',
                        name: 'keterangan'
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
            $('#formSupplier').on('submit', function(e) {
                e.preventDefault();

                var supplierId = $('#supplier_id').val();
                var url = supplierId ? "/supplier/" + supplierId : "{{ route('supplier.store') }}";
                var method = supplierId ? "PUT" : "POST";

                $.ajax({
                    url: url,
                    type: method,
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#inlineForm').modal('hide');
                            $('#formSupplier')[0].reset();
                            $('#satuan_id').val(''); // Reset ID setelah submit
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

        $('#btnTambahSupplier').on('click', function() {
            $('#modalTitle').text('Tambah Supplier');
            $('#formSupplier')[0].reset();
            $('#supplier_id').val('');
            $('#inlineForm').modal('show');
        });


        $(document).on('click', '.btn-supplier-edit', function(e) {
            e.preventDefault();
            let id = $(this).data('id');

            $.ajax({
                url: "/supplier/edit/" + id,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    if (response.success) {
                        $('#modalTitle').text('Edit Supplier');
                        $('#supplier_id').val(response.data.id);
                        $('#nama').val(response.data.nama);
                        $('#alamat').val(response.data.alamat);
                        $('#kota').val(response.data.kota);
                        $('#no_hp').val(response.data.no_hp);
                        $('#email').val(response.data.email);
                        $('#contact_person').val(response.data.contact_person);
                        $('#telepon_contact_person').val(response.data.telepon_contact_person);
                        $('#keterangan').val(response.data.keterangan);
                        $('#inlineForm').modal('show');
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Gagal mengambil data Supplier',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });

        $(document).on('click', '.btn-supplier-delete', function(e) {
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
                        url: "/supplier/" + id,
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
