@extends('layouts.app')
@section('content')
    <div class="main-content container-fluid">
        <section class="section">
            <div class="card mt-2">
                <div class="card-header">
                   Hasil Ujian
                </div>
                <div class="card-body">
                    <table class='table table-light' id="table_participant-session" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Ujian</th>
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
                    url: "{{ route('answer.index') }}",
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

    </script>
@endsection
