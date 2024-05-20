@extends('layouts.app')
@section('content')
    <div class="main-content container-fluid">
        <section class="section">
            @can('peserta-sesi-create')
            <a href="{{ route('participant-session.create') }}" class="btn icon icon-left btn-primary"><i data-feather="edit"></i>
                Tambah Sesi Peserta</a>
            @endcan
            <div class="card mt-2">
                <div class="card-header">
                    Data Sesi Peserta
                </div>
                <div class="card-body">
                    <table class='table table-light' id="table_participant-session" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Ujian</th>
                                <th>Tanggal Ujian</th>
                                <th>Nama Ujian</th>
                                <th>Peserta Ujian</th>
                                <th>Sesi</th>
                                <th>Status</th>
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

            $('#table_participant-session').DataTable({
                processing: true,
                serverside: true,
                ajax: {
                    url: "{{ route('participant-session.index') }}",
                    type: 'GET'
                },
                responsive: true,
                columns: [{
                        data: 'DT_RowIndex',
                    },
                    {
                        data: 'exam_id',
                    },
                    {
                        data: 'date',
                    },
                    {
                        data: 'ujian',
                    },
                    {
                        data: 'participant_id',
                    },
                    {
                        data: 'exam_session_id',
                    },
                    {
                        data: 'status',
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
                text: "Do you want to delete participant ?",
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
                            url: "participant-session/" + id,
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "_method": 'DELETE',
                            },
                            success: function(data) {
                                if (data.success) {
                                    $.notify(`<strong>Success</strong> </> !`, {
                                        allow_dismiss: false,
                                        type: 'success'
                                    });
                                    var oTable = $('#table_participant-session').DataTable(); //inialisasi datatable
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
