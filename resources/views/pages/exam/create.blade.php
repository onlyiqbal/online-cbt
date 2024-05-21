@extends('layouts.app')
@section('content')
<div class="main-content container-fluid" style="margin-top: -30px !important">
    <section class="section">
        <a href="{{ route('exam.index') }}" class="btn icon icon-left btn-primary"><i data-feather="arrow-left"></i>
            Kembali</a>
        <div class="ml-4 mr-4 mt-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Ujian</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form id="form_exam" class="form form-horizontal">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Guru</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <select class="form-control" name="user_id">
                                                    <option value="">Pilih Guru</option>
                                                    @foreach ($user as $item)
                                                    <option value="{{ $item->id }}">{{ $item->fullname }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="form-control-icon">
                                                    <i data-feather="users"></i>
                                                </div>
                                            </div>
                                            <span class="text-danger" id="user_id-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Mata Pelajaran</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <select class="form-control" name="mapel_id">
                                                    <option value="">Pilih Mata Pelajaran</option>
                                                    @foreach ($mapel as $item)
                                                    <option value="{{ $item->id }}">{{ $item->mapel }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <div class="form-control-icon">
                                                    <i data-feather="list"></i>
                                                </div>
                                            </div>
                                            <span class="text-danger" id="mapel_id-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Kelas</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <select class="form-control" name="class_room_id">
                                                    <option value="">Pilih Kelas</option>
                                                    @foreach ($class as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <div class="form-control-icon">
                                                    <i data-feather="trello"></i>
                                                </div>
                                            </div>
                                            <span class="text-danger" id="class_room_id-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Kategori Kelas</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <select class="form-control" name="major_id">
                                                    <option value="">Pilih Kategori Kelas</option>
                                                    @foreach ($major as $item)
                                                    <option value="{{ $item->id }}">{{ $item->major }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <div class="form-control-icon">
                                                    <i data-feather="trello"></i>
                                                </div>
                                            </div>
                                            <span class="text-danger" id="major_id-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Soal Pilihan Ganda</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group has-icon-left">
                                            <div class="input-group input-group-merge">
                                                <input id="soal_1" style="text-align: left" name="soal_1"
                                                    class="form-control" placeholder="Masukkan Kode Soal" type="text"
                                                    autocomplete="off">
                                                <div class="form-control-icon">
                                                    <i data-feather="server"></i>
                                                </div>
                                                <a href="javascript:void(0)" id="tombol-pilgan" data-id="pilgan"
                                                    class="search_button btn btn-outline-primary"><i
                                                        class="fas fa-search"></i></a>
                                            </div>
                                            <span class="text-danger" id="soal_1-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Soal Essay</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group has-icon-left">
                                            <div class="input-group input-group-merge">
                                                <input id="soal_2" style="text-align: left" name="soal_2"
                                                    class="form-control" placeholder="Masukkan Kode Soal" type="text"
                                                    autocomplete="off">
                                                <div class="form-control-icon">
                                                    <i data-feather="server"></i>
                                                </div>
                                                <a href="javascript:void(0)" id="tombol-essay" data-id="essay"
                                                    class="search_button btn btn-outline-primary"><i
                                                        class="fas fa-search"></i></a>
                                            </div>
                                            <span class="text-danger" id="soal_2-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Acak Soal</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <select class="form-control" name="random_question">
                                                    <option value="not_random">Tidak</option>
                                                    <option value="random">ya</option>
                                                </select>
                                                <div class="form-control-icon">
                                                    <i data-feather="refresh-ccw"></i>
                                                </div>
                                            </div>
                                            <span class="text-danger" id="random_question-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Description</label>
                                    </div>
                                    <div class="col-md-8">
                                        <textarea type="text" class="form-control mt-2" id="description"
                                            name="description"></textarea>
                                    </div>
                                    <div class="col-12 d-flex justify-content-end mt-2">
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
        @include('pages.exam.modal')
    </section>
</div>
<script type="application/javascript">
    $(document).ready(function() {
            $('#description').ckeditor();

            var table = $('#table_soal').DataTable();
            $('#tombol-pilgan').on('click', function() {
                table.destroy();
                let type = $(this).data('id');
                console.log(type);
                table = $('#table_soal').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "/get-question/"+type,
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
                            data: 'kode_soal',
                        },
                        {
                            data: 'type',
                        },
                        {
                            data: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                });
                $('#question').modal('show');
            })

            $('#tombol-essay').on('click', function() {
                table.destroy();
                let type = $(this).data('id');
                console.log(type);
                table = $('#table_soal').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "/get-question/"+type,
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
                            data: 'kode_soal',
                        },
                        {
                            data: 'type',
                        },
                        {
                            data: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                });
                $('#question').modal('show');
            })

            $('#form_exam').on('submit', function(e) {
                e.preventDefault();
                formData = new FormData(this);
                formData.append('description',$('#description').val())
                $('#mapel_id-error').text('');
                $('#class_room_id-error').text('');
                $('#major_id-error').text('');
                $('#random_question-error').text('');
                $('#user_id-error').text('');

                $.ajax({
                    url: "{{ route('exam.store') }}",
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success == true) {
                            window.location.href = "{{ route('exam.index') }}";
                            sessionStorage.setItem('success', response.message);
                        }
                        else{
                            $.notify(`<strong>Warning</strong> ${response.message}`, {
                            allow_dismiss: false,
                            type: 'danger'
                        });
                        }
                    },
                    error: function(response) {
                        $('#mapel_id-error').text(response.responseJSON.errors.mapel_id);
                        $('#class_room_id-error').text(response.responseJSON.errors.class_room_id);
                        $('#major_id-error').text(response.responseJSON.errors.major_id);
                        $('#random_question-error').text(response.responseJSON.errors.random_question);
                        $('#user_id-error').text(response.responseJSON.errors.user_id);
                        $.notify('<strong>Warning</strong> Isian tidak valid !', {
                            allow_dismiss: false,
                            type: 'danger'
                        });
                    }
                });
            })
        })

        function pilih_soal(e){

            let type = e.getAttribute('data-name');
            let id   = e.getAttribute('data-id');
            if(type == 'pilgan'){   
                $('#soal_1').val(id);
            }
            else{
                $('#soal_2').val(id);
            }

            $('#question').modal('hide');
        }

</script>
@endsection