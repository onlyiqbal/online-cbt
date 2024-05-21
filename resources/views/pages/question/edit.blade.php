@extends('layouts.app')
@section('content')
<div class="main-content container-fluid" style="margin-top: -30px !important">
    <section class="section">
        <a href="{{ route('question.index') }}" class="btn icon icon-left btn-primary"><i data-feather="arrow-left"></i>
            Kembali</a>
        <div class="ml-4 mr-4 mt-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Update Soal Ujian</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form id="form_question" class="form form-horizontal" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Guru Pengajar</label>
                                        <div class="form-group has-icon-left mt-2">
                                            <div class="position-relative">
                                                <select class="form-control" name="user_id">
                                                    @foreach ($user as $item)
                                                    <option value="{{ $item->id }}" {{$item->id ==
                                                        $question->user_id ? 'selected' : ''}}>{{ $item->fullname }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                                <div class="form-control-icon">
                                                    <i data-feather="users"></i>
                                                </div>
                                            </div>
                                            <span class="text-danger" id="user_id-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Type</label>
                                        <div class="form-group has-icon-left mt-2">
                                            <div class="position-relative">
                                                <select class="form-control" id="type" name="type" disabled>
                                                    <option value="pilgan" {{"pilgan"==$question->type ? 'selected' :
                                                        ''}}>Pilihan Ganda</option>
                                                    <option value="essay" {{"essay"==$question->type ? 'selected' :
                                                        ''}}>Essay</option>
                                                </select>
                                                <div class="form-control-icon">
                                                    <i data-feather="type"></i>
                                                </div>
                                            </div>
                                            <p><small class="text-muted">Pilih tipe soal sebelum mengisi
                                                    soal.</small>
                                            </p>
                                            <span class="text-danger" id="type-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Mata Pelajaran</label>
                                        <div class="form-group has-icon-left mt-2">
                                            <div class="position-relative">
                                                <select class="form-control" name="mapel_id" disabled>
                                                    @foreach ($mapel as $item)
                                                    <option value="{{ $item->id }}" {{$item->id == $question->mapel_id ?
                                                        'selected' : ''}}>{{ $item->mapel }}
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
                                    <div class="col-md-6">
                                        <label>Kelas</label>
                                        <div class="form-group has-icon-left mt-2">
                                            <div class="position-relative">
                                                <select class="form-control" name="class_room_id" disabled>
                                                    @foreach ($class as $item)
                                                    <option value="{{ $item->id }}" {{$item->id ==
                                                        $question->class_room_id ? 'selected' : ''}}>{{ $item->name }}
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
                                    <div class="col-md-6">
                                        <label>Jurusan</label>
                                        <div class="form-group has-icon-left mt-2">
                                            <div class="position-relative">
                                                <select class="form-control" name="major_id" disabled>
                                                    @foreach ($major as $item)
                                                    <option value="{{ $item->id }}" {{$item->id == $question->major_id ?
                                                        'selected' : ''}}>{{ $item->major }}
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
                                    <div class="col-12 d-flex justify-content-end ">
                                        <a href="{{route('list-detail-question',$question->id)}}"
                                            class="btn btn-success me-1 mb-1"><i data-feather="save"></i>Update Detail
                                            Soal</a>
                                        <button type="submit" class="btn btn-primary me-1 mb-1"><i
                                                data-feather="save"></i>Submit</button>
                                        <button type="reset" class="btn btn-light-secondary me-1 mb-1">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="application/javascript">
    $(document).ready(function() {

            $('#form_question').on('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Yakin Menyimpan Data?',
                    text: "Mungkin ada beberapa data yang tidak bisa di edit!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var id = "{{$question->id}}";
                        formData = new FormData(this);
                        var soal_data = $('#form_question').serializeArray();
                        $.each(soal_data, function(key, input) {
                            formData.append(input.name, input.value);
                        });

                        $('#user_id-error').text('');
                        $('#type-error').text('');
                        $('#mapel_id-error').text('');
                        $('#class_room_id-error').text('');
                        $('#major_id-error').text('');

                        $.ajax({
                            url: "/question/"+id,
                            type: "POST",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                if (response.success == true) {
                                    window.location.href =
                                        "{{ route('question.index') }}";
                                    sessionStorage.setItem('success', response.message);
                                }
                            },
                            error: function(response) {
                                $('#user_id-error').text(response.responseJSON.errors
                                    .user_id);
                                $('#type-error').text(response.responseJSON.errors
                                .type);
                                $('#mapel_id-error').text(response.responseJSON.errors
                                    .mapel_id);
                                $('#class_room_id-error').text(response.responseJSON
                                    .errors
                                    .class_room_id);
                                $('#major_id-error').text(response.responseJSON.errors
                                    .major_id);
                                $.notify(
                                '<strong>Warning</strong> Isian tidak valid !', {
                                    allow_dismiss: false,
                                    type: 'danger'
                                });
                            }
                        });
                        Swal.fire(
                            'Berhasil!',
                            'Data berhasil di simpan.',
                            'success'
                        )
                    }
                })
            })
        })
</script>
@endsection