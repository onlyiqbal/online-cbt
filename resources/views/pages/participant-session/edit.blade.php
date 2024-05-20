@extends('layouts.app')
@section('content')
    <div class="main-content container-fluid" style="margin-top: -30px !important">
        <section class="section">
            <a href="{{ route('participant-session.index') }}" class="btn icon icon-left btn-primary"><i data-feather="arrow-left"></i>
                Kembali</a>
            <div class="ml-4 mr-4 mt-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Peserta Per Sesi</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="form_participant-session" class="form form-horizontal">
                                @csrf
                                @method('PUT')
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
                                                            <option value="{{ $item->id }}" {{ $item->id == $data->exam_session_id ? 'selected' : ''}}><b>{{ $item->name }}</b>
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
                                                    <input type="date" name="date" value="{{$data->date}}"  class="form-control"
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
                                                        class="form-control" value="{{$data->ujian->id}}" placeholder="Masukkan Kode Ujian"
                                                        type="hidden" autocomplete="off">
                                                    <input id="exam_name" value="{{$data->ujian->kode_ujian}}" style="text-align: left" name=""
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
                                        <div class="col-md-4">
                                            <label>Nama Peserta</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" name="" value="{{$data->peserta->fullname}}" class="form-control" placeholder=""
                                                        id="first-name-icon" readonly>
                                                    <div class="form-control-icon">
                                                        <i data-feather="user"></i>
                                                    </div>
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

            $('#form_participant-session').on('submit', function(e) {
                e.preventDefault();
                formData = new FormData(this);
                var url = "{{route('participant-session.update',$data->id)}}";
                $('#exam_session_id-error').text('');
                $('#exam_id-error').text('');

                $.ajax({
                    url: url,
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
                        $('#exam_id-error').text(response.responseJSON.errors.exam_id);
                        $.notify('<strong>Warning</strong> Isian tidak valid !', {
                            allow_dismiss: false,
                            type: 'danger'
                        });
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
