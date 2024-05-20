@extends('layouts.app')
@section('content')
    <div class="main-content container-fluid" style="margin-top: -30px !important">
        <section class="section">
            <a href="{{ route('participant-session.index') }}" class="btn icon icon-left btn-primary"><i data-feather="arrow-left"></i>
                Kembali</a>
            <div class="ml-4 mr-4 mt-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Tambah Peserta Per Sesi</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="form_participant-session" class="form form-horizontal">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Sesi</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <select class="form-control" name="exam_session_id">
                                                        <option value="">Pilih Sesi</option>
                                                        @foreach ($sesi as $item)
                                                            <option value="{{ $item->id }}"><b>{{ $item->name }}</b>
                                                                ({{ $item->time_start }} - {{ $item->time_end }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <div class="form-control-icon">
                                                        <i data-feather="list"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="exam_session_id-error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Tanggal Ujian</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="date" name="date"  class="form-control"
                                                        id="first-name-icon">
                                                    <div class="form-control-icon">
                                                        <i data-feather="calendar"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="date-error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Kode Ujian</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="input-group input-group-merge">
                                                    <input id="exam_id" style="text-align: left" name="exam_id"
                                                        class="form-control" placeholder="Masukkan Kode Ujian"
                                                        type="hidden" autocomplete="off">
                                                    <input id="exam_name" style="text-align: left" name=""
                                                        class="form-control" placeholder="Masukkan Kode Ujian" type="text"
                                                        autocomplete="off" readonly>
                                                    <div class="form-control-icon">
                                                        <i data-feather="server"></i>
                                                    </div>
                                                    <a href="javascript:void(0)" id="cari-ujian"
                                                        class="search_button btn btn-outline-primary"><i
                                                            class="fas fa-search"></i></a>
                                                </div>
                                                <span class="text-danger" id="exam_id-error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="form-group mr-5">
                                                <div class="table-responsive">
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <label>Cari Berdasarkan Nomor Peserta</label>
                                                            <div class="form-group has-icon-left mt-2">
                                                                <div class="position-relative">
                                                                    <input id="cari-nomor-peserta" class="form-control"
                                                                        placeholder="Cari Nomor Peserta Ujian" type="text"
                                                                        autocomplete="off">
                                                                    <div class="form-control-icon">
                                                                        <i data-feather="search"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <label>Cari Berdasarkan Kelas</label>
                                                            <div class="form-group has-icon-left mt-2">
                                                                <div class="position-relative">
                                                                    <select class="form-control" id="kelas">
                                                                        <option value="">Pilih Kelas</option>
                                                                        @foreach ($class as $item)
                                                                            <option value="{{ $item->id }}">
                                                                                {{ $item->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="form-control-icon">
                                                                        <i data-feather="trello"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-3">
                                                            <label>Cari Berdasarkan Jurusan</label>
                                                            <div class="form-group has-icon-left mt-2">
                                                                <div class="position-relative">
                                                                    <select class="form-control" id="jurusan">
                                                                        <option value="">Pilih Jurusan</option>
                                                                        @foreach ($major as $item)
                                                                            <option value="{{ $item->id }}">
                                                                                {{ $item->major }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    <div class="form-control-icon">
                                                                        <i data-feather="trello"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <table class="table" style="width: 100%">
                                                        <thead class="bg-primary text-white">
                                                            <tr>
                                                                <th>
                                                                    <div class='form-check'>
                                                                        <div class="custom-control custom-checkbox">
                                                                            <input type="checkbox" id="check-all"
                                                                                class="form-check-input form-check-secondary"
                                                                                name="customCheck" id="customColorCheck3">
                                                                            <label class="form-check-label text-white"
                                                                                for="customColorCheck3">Pilih Semua</label>
                                                                        </div>
                                                                    </div>
                                                                </th>
                                                                <th>Nomor Peserta</th>
                                                                <th>Nama Peserta</th>
                                                                <th>Kelas</th>
                                                                <th>Jurusan</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="table_participant">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end ">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                            <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @include('pages.participant-session.modal')
        </section>
    </div>
    <script type="application/javascript">
        $(document).ready(function() {

            $('#check-all').click(function(event) {
                if (this.checked) {
                    $(':checkbox').each(function() {
                        this.checked = true;
                    });
                } else {
                    $(':checkbox').each(function() {
                        this.checked = false;
                    });
                }
            });

            var table = $('#table_ujian').DataTable();
            $('#cari-ujian').on('click', function() {
                table.destroy();
                table = $('#table_ujian').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('get-exam') }}",
                        type: 'GET',
                    },
                    "responsive": true,
                    "language": {
                        "oPaginate": {
                            "sNext": "<i class='fas fa-angle-right'>",
                            "sPrevious": "<i class='fas fa-angle-left'>",
                        },
                    },
                    columns: [{
                            data: 'kode_ujian',
                        },
                        {
                            data: 'name',
                        },
                        {
                            data: 'exam_date',
                        },
                        {
                            data: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                });
                $('#exam').modal('show');
            })

            function showParticipant(value) {
                var fieldHtml = `<tr>
                                    <td>
                                        <input type="checkbox" id="check-all" 
                                        class="form-check-input form-check-success" value="${value.id}"
                                        name="participant_id[]" id="customColorCheck3">
                                    </td>
                                    <td>${value.id}</td>
                                    <td>${value.fullname}</td>
                                    <td>${value.kelas}</td>
                                    <td>${value.major}</td>
                                </tr>`;

                $('#table_participant').append(fieldHtml);
            }

            $.get('/get-participant', function(data) {
                if (data == '') {
                    $('#table_participant').append(`<tr><td colspan="3">Data tidak ditemukan</td></tr>`);
                } else {
                    $.each(data, function(index, value) {
                        showParticipant(value);
                    });
                }
            });

            $('#cari-nomor-peserta').on('keyup', function() {
                var nomor_peserta = $('#cari-nomor-peserta').val();
                if (nomor_peserta == '') {
                    $('#table_participant').empty();

                    $.get('/get-participant', function(data) {
                        if (data == '') {
                            $('#table_participant').append(
                                `<tr><td colspan="3">Data tidak ditemukan</td></tr>`);
                        } else {
                            $.each(data, function(index, value) {
                                showParticipant(value);
                            });
                        }
                    });
                } else {
                    $.ajax({
                        url: "/get-participant/" + nomor_peserta,
                        type: "GET",
                        dataType: "json",
                        success: function(response) {
                            $('#table_participant').empty();

                            if (response == '') {
                                $('#table_participant').append(
                                    `<tr><td colspan="3">Data tidak ditemukan</td></tr>`);
                            } else {
                                $.each(response, function(index, value) {
                                    showParticipant(value);
                                });
                            }
                        },
                        error: function(response) {
                            $.notify('<strong>Warning</strong> Something Went Wrong!', {
                                allow_dismiss: false,
                                type: 'danger'
                            });
                        }
                    });
                }
            })

            $('#jurusan , #kelas').on('change', function() {

                var jurusan = $('#jurusan').val();
                var kelas = $('#kelas').val();

                $.ajax({
                    url: "{{ route('get-participant-by-select') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        jurusan: jurusan,
                        kelas: kelas,
                    },
                    success: function(response) {
                        $('#table_participant').empty();

                        if (response == '') {
                            $('#table_participant').append(
                                `<tr><td colspan="3">Data tidak ditemukan</td></tr>`);
                        } else {
                            $.each(response, function(index, value) {
                                showParticipant(value);
                            });
                        }
                    },
                    error: function(response) {
                        $.notify('<strong>Warning</strong> Something Went Wrong!', {
                            allow_dismiss: false,
                            type: 'danger'
                        });
                    }
                });
            })

            $('#form_participant-session').on('submit', function(e) {
                e.preventDefault();
                formData = new FormData(this);
                $('#exam_session_id-error').text('');
                $('#date-error').text('');
                $('#exam_id-error').text('');

                $.ajax({
                    url: "{{ route('participant-session.store') }}",
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success == true) {
                            window.location.href =
                            "{{ route('participant-session.index') }}";
                            sessionStorage.setItem('success', response.message);
                        }
                    },
                    error: function(response) {
                        $('#exam_session_id-error').text(response.responseJSON.errors
                            .exam_session_id);
                        $('#date-error').text(response.responseJSON.errors
                            .date);
                        $('#exam_id-error').text(response.responseJSON.errors.exam_id);
                        $.notify('<strong>Warning</strong> Isian tidak valid !', {
                            allow_dismiss: false,
                            type: 'danger'
                        });
                        if (response.responseJSON.errors.participant_id) {
                            $.notify(`<strong>Warning</strong> ${response.responseJSON.errors.participant_id}`, {
                                allow_dismiss: false,
                                type: 'danger'
                            });
                        }
                    }
                });
            })
        })

        function pilih_ujian(e) {
            var id = e.getAttribute('data-id');
            var kode = e.getAttribute('data-name');

            $('#exam_id').val(id);
            $('#exam_name').val(kode);
            $('#exam').modal('hide');
        }
    </script>
@endsection
