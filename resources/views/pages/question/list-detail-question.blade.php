@extends('layouts.app')
@section('content')
    <div class="main-content container-fluid">
        <section class="section">
            <a href="{{ route('question.edit',$id) }}" class="btn icon icon-left btn-primary"><i data-feather="arrow-left"></i>
               Kembali</a>
            <a href="{{ route('add-detail-question',$id) }}" class="btn icon icon-left btn-success"><i data-feather="edit"></i>
            Tambah Detail Soal Baru</a>
            <div class="card mt-2">
                <div class="card-header">
                    Data Detail Soal Ujian
                </div>
                <div class="card-body">
                    <table class='table table-light' id="table_question" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Soal</th>
                                <th>Pilihan 1</th>
                                <th>Pilihan 2</th>
                                <th>Pilihan 3</th>
                                <th>Pilihan 4</th>
                                <th>Pilihan 5</th>
                                <th>Kunci</th>
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

            var url = "{{route('list-detail-question',$id)}}";

            $('#table_question').DataTable({
                processing: true,
                serverside: true,
                ajax: {
                    url: url,
                    type: 'GET'
                },
                responsive: true,
                columns: [{
                        data: 'DT_RowIndex',
                    },
                    {
                        data: 'image',
                    },
                    {
                        data: 'question',
                    },
                    {
                        data: 'choice_1',
                    },
                    {
                        data: 'choice_2',
                    },
                    {
                        data: 'choice_3',
                    },
                    {
                        data: 'choice_4',
                    },
                    {
                        data: 'choice_5',
                    },
                    {
                        data: 'key',
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
                text: "Do you want to delete Question?",
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
                            url: "/delete-detail-question/" + id,
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
