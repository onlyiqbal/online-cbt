@extends('layouts.app')
@section('content')
    <div class="main-content container-fluid">
        <section class="section">
            @can('guru-create')
            <a href="{{ route('teachers.create') }}" class="btn icon icon-left btn-primary"><i data-feather="edit"></i>
                Tambah Guru</a>
            @endcan
            <div class="card mt-2">
                <div class="card-header">
                    Data Guru
                </div>
                <div class="card-body">
                    <table class='table table-light' id="table_teacher" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Photo</th>
                                <th>NIP</th>
                                <th>Nama Guru</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Jenis Kelamin</th>
                                <th>action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
    <script type="application/javascript">
        $(document).ready(function() {

            if (sessionStorage.getItem('success')) {
                let data = sessionStorage.getItem('success');
                $.notify(`<strong>Success</strong> ${data}</> !`, {
                    allow_dismiss: false,
                    type: 'success'
                });

                sessionStorage.clear();
            }

            $('#table_teacher').DataTable({
                processing: true,
                serverside: true,
                ajax: {
                    url: "{{ route('teachers.index') }}",
                    type: 'GET'
                },
                responsive: true,
                columns: [{
                        data: 'DT_RowIndex',
                    },
                    {
                        data: 'photo',
                    },
                    {
                        data: 'nip',
                    },
                    {
                        data: 'name',
                    },
                    {
                        data: 'email',
                    },
                    {
                        data: 'username',
                    },
                    {
                        data: 'jenkel',
                    },
                    {
                        data: 'action',
                    },
                ]
            })
        })

        function deleteItem(e) {
            let id = e.getAttribute('data-id');
            let name = e.getAttribute('data-name');
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: true
            });
            swalWithBootstrapButtons.fire({
                title: 'Are you sure?',
                text: "Do you want to delete " + name + "?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: "teachers/" + id,
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": 'DELETE',
                            },
                            success: function(data) {
                                if (data.success) {
                                    $.notify(`<strong>Success</strong> ${data.message}</> !`, {
                                        allow_dismiss: false,
                                        type: 'success'
                                    });
                                    var oTable = $('#table_teacher').DataTable(); //inialisasi datatable
                                    oTable.ajax.reload();; //reset datatable
                                }
                            }

                        });
                    }
                } else if (
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    swal.fire(
                        'Cancelled',
                        'Data is not deleted',
                        'error'
                    )
                }
            });
        }
    </script>
@endsection
