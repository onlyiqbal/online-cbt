@extends('layouts.app-exam')
@section('content')
    <section class="section mt-4">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    @if (is_null($cekujian))
                        <div class="card-header">
                            <h4><b>Tidak Ada Ujian !</b></h4>
                        </div>
                    @else
                        <div class="card-header">
                            <h4><b>{{ $cekujian->nama_ujian }}</b></h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <p>Mata Pelajaran</p>
                                </div>
                                <div class="col-6">
                                    <p>: {{ $cekujian->mapel }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p>Sesi</p>
                                </div>
                                <div class="col-6">
                                    <p>: {{ $cekujian->sesi }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p>Mulai</p>
                                </div>
                                <div class="col-6">
                                    <p>: {{ $cekujian->time_start }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p>Selesai</p>
                                </div>
                                <div class="col-6">
                                    <p>: {{ $cekujian->time_end }}</p>
                                </div>
                            </div>
                            @if ($time > $cekujian->time_end)
                                <a href="#" class="btn btn-success disabled" onclick="return false;">Sesi Ujian Habis</a>
                            @elseif($time < $cekujian->time_start)
                                <a href="#" class="btn btn-success disabled" onclick="return false;">Sesi Ujian Belum di
                                    Mulai</a>
                            @else
                                <a href="{{ route('confirm') }}" class="btn btn-success">Kerjakan Ujian</a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            if (sessionStorage.getItem('success')) {
                let data = sessionStorage.getItem('success');
                $.notify(`<strong>Success</strong> ${data}</> !`, {
                    allow_dismiss: false,
                    type: 'success'
                });

                sessionStorage.clear();
            }
        })

        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            history.go(1);
        };
    </script>
@endsection
