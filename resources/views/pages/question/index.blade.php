@extends('layouts.app')
@section('content')
<div class="main-content container-fluid">
    <section class="section">
        @can('soal-create')
        <a href="{{ route('question.create') }}" class="btn icon icon-left btn-primary"><i data-feather="edit"></i>
            Buat Soal Ujian Baru</a>
        @endcan
        <div class="card mt-2">
            <div class="card-header">
                Data Soal Ujian
            </div>
            <div class="card-body">
                <table class='table table-light' id="table_question" style="width: 100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Soal</th>
                            <th>Type</th>
                            <th>Guru</th>
                            <th>Mata Pelajaran</th>
                            <th>Kelas</th>
                            <th>Kategori Kelas</th>
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

            $('#table_question').DataTable({
                processing: true,
                serverside: true,
                ajax: {
                    url: "{{ route('question.index') }}",
                    type: 'GET'
                },
                responsive: true,
                columns: [{
                        data: 'DT_RowIndex',
                    },
                    {
                        data: 'kode_soal',
                    },
                    {
                        data: 'type',
                    },
                    {
                        data: 'guru',
                    },
                    {
                        data: 'mapel',
                    },
                    {
                        data: 'kelas',
                    },
                    {
                        data: 'jurusan',
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
                            url: "question/" + id,
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
                                    var oTable = $('#table_question').DataTable(); //inialisasi datatable
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