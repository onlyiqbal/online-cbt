@extends('layouts.app-exam')
@section('content')
    <section class="section mt-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center"><b>Identitas Peserta</b></h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <b>
                                    <p>Nomor Ujian</p>
                                </b>
                            </div>
                            <div class="col-6">
                                <b>
                                    <p>: {{ $cekujian->kode_ujian }}</p>
                                </b>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <b>
                                    <p>Nama</p>
                                </b>
                            </div>
                            <div class="col-6">
                                <b>
                                    <p>: {{ $cekujian->fullname }}</p>
                                </b>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <b>
                                    <p>Nama Sekolah</p>
                                </b>
                            </div>
                            <div class="col-6">
                                <b>
                                    <p>: {{ $cekujian->shcool_name }}</p>
                                </b>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <b>
                                    <p>Kelas</p>
                                </b>
                            </div>
                            <div class="col-6">
                                <b>
                                    <p>: {{ $cekujian->name }}</p>
                                </b>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <b>
                                    <p>Jurusan</p>
                                </b>
                            </div>
                            <div class="col-6">
                                <b>
                                    <p>: {{ $cekujian->major }}</p>
                                </b>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <b>
                                    <p>Nama Ujian</p>
                                </b>
                            </div>
                            <div class="col-6">
                                <b>
                                    <p>: {{ $cekujian->nama_ujian }}</p>
                                </b>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <b>
                                    <p>Mapel</p>
                                </b>
                            </div>
                            <div class="col-6">
                                <b>
                                    <p>: {{ $cekujian->mapel }}</p>
                                </b>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4><b>Deskripsi</b></h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <b>
                                    <p>{!! $cekujian->description !!}</p>
                                </b>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('start-exam') }}" class="btn btn-success">Kerjakan Ujian</a>
                </div>
            </div>
        </div>
    </section>
    <script>
        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            history.go(1);
        }
    </script>
@endsection
