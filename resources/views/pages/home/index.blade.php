@extends('layouts.app')
@section('content')
    <div class="main-content container-fluid">
        <div class="page-title">
            <h3>Dashboard</h3>
        </div>
        <section class="section">
            <div class="row mb-2">
                <div class="col-12 col-md-3">
                    <div class="card card-statistic">
                        <div class="card-body p-0">
                            <div class="d-flex flex-column">
                                <div class='px-3 py-3 d-flex justify-content-between'>
                                    <h3 class='card-title'>User</h3>
                                    <div class="card-right d-flex align-items-center">
                                        <p>{{ $user }} </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card card-statistic">
                        <div class="card-body p-0">
                            <div class="d-flex flex-column">
                                <div class='px-3 py-3 d-flex justify-content-between'>
                                    <h3 class='card-title'>Guru</h3>
                                    <div class="card-right d-flex align-items-center">
                                        <p>{{ $guru }} </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card card-statistic">
                        <div class="card-body p-0">
                            <div class="d-flex flex-column">
                                <div class='px-3 py-3 d-flex justify-content-between'>
                                    <h3 class='card-title'>Siswa</h3>
                                    <div class="card-right d-flex align-items-center">
                                        <p>{{ $siswa }} </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <div class="card card-statistic">
                        <div class="card-body p-0">
                            <div class="d-flex flex-column">
                                <div class='px-3 py-3 d-flex justify-content-between'>
                                    <h3 class='card-title'>Ujian</h3>
                                    <div class="card-right d-flex align-items-center">
                                        <p>{{ $ujian }} </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <div class="card mt-2">
                <div class="card-header">
                    Data Peserta
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
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div> --}}
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

            // $('#table_participant-session').DataTable({
            //     processing: true,
            //     serverside: true,
            //     ajax: {
            //         url: "{{ route('participant-session.index') }}",
            //         type: 'GET'
            //     },
            //     responsive: true,
            //     columns: [{
            //             data: 'DT_RowIndex',
            //         },
            //         {
            //             data: 'exam_id',
            //         },
            //         {
            //             data: 'ujian',
            //         },
            //         {
            //             data: 'participant_id',
            //         },
            //         {
            //             data: 'exam_session_id',
            //         },
            //         {
            //             data: 'status',
            //         },
            //     ]
            // })
        })
    </script>
@endsection
