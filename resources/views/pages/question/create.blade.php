@extends('layouts.app')
@section('content')
    <div class="main-content container-fluid" style="margin-top: -30px !important">
        <section class="section">
            <a href="{{ route('question.index') }}" class="btn icon icon-left btn-primary"><i
                    data-feather="arrow-left"></i>
                Kembali</a>
            <div class="ml-4 mr-4 mt-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Soal Ujian Baru</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="form_question" class="form form-horizontal" enctype="multipart/form-data">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label>Guru Pengajar</label>
                                            <div class="form-group has-icon-left mt-2">
                                                <div class="position-relative">
                                                    <select class="form-control" name="user_id">
                                                        <option value="">Pilih Guru</option>
                                                        @foreach ($user as $item)
                                                            <option value="{{ $item->id }}">{{ $item->fullname }}
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
                                                    <select class="form-control" id="type" name="type">
                                                        <option value="" selected>Pilih Type Soal</option>
                                                        <option value="pilgan">Pilihan Ganda</option>
                                                        <option value="essay">Essay</option>
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
                                        <div class="col-md-6">
                                            <label>Kelas</label>
                                            <div class="form-group has-icon-left mt-2">
                                                <div class="position-relative">
                                                    <select class="form-control" name="class_room_id">
                                                        <option value="">Pilih Kelas</option>
                                                        @foreach ($class as $item)
                                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
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
                                                    <select class="form-control" name="major_id">
                                                        <option value="">Pilih Jurusan</option>
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
                                        <div class="col-md-6">
                                            <label>Import Excel</label>
                                            <div class="form-group has-icon-left mt-2">
                                                <div class="position-relative">
                                                    <input type="file" name="import" class="form-control"
                                                        placeholder="Jurusan" id="first-name-icon">
                                                    <div class="form-control-icon">
                                                        <i data-feather="upload"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="major-error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group has-icon-left mt-2">
                                                <a href="{{ route('template-question-excel') }}"
                                                    class="btn icon icon-left btn-success"><i data-feather="file-text"></i>
                                                    Template Excel</a>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mr-5" style="margin-left: -30px">
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <tbody id="table_soal">
                                                                <tr>
                                                                    <td>
                                                                        <label><b>Soal</b></label>
                                                                        <textarea type="text" class="form-control mt-2" id="soal" name="data[1][question]"></textarea>
                                                                        <label><b>Gambar</b></label>
                                                                        <div class="form-group has-icon-left mt-2">
                                                                            <div class="position-relative">
                                                                                <input type="file" name="data[1][image]"
                                                                                    class="form-control" placeholder=""
                                                                                    id="first-name-icon">
                                                                                <div class="form-control-icon">
                                                                                    <i data-feather="upload"></i>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <label><b>Pilihan 1</b></label>
                                                                        <textarea type="text" class="form-control mt-2 pilihan" id="" name="data[1][choice_1]"></textarea>
                                                                        <label><b>Pilihan 2</b></label>
                                                                        <textarea type="text" class="form-control mt-2 pilihan" id="" name="data[1][choice_2]"></textarea>
                                                                        <label><b>Pilihan 3</b></label>
                                                                        <textarea type="text" class="form-control mt-2 pilihan" id="" name="data[1][choice_3]"></textarea>
                                                                        <label><b>Pilihan 4</b></label>
                                                                        <textarea type="text" class="form-control mt-2 pilihan" id="" name="data[1][choice_4]"></textarea>
                                                                        <label><b>Pilihan 5</b></label>
                                                                        <textarea type="text" class="form-control mt-2 pilihan" id="" name="data[1][choice_5]"></textarea>
                                                                        <label><b>Jawaban</b></label>
                                                                        <select class="form-control mt-2 jawaban"
                                                                            name="data['1'][key]">
                                                                            <option value="">Pilih Jawaban</option>
                                                                            <option value="a">A</option>
                                                                            <option value="b">B</option>
                                                                            <option value="c">C</option>
                                                                            <option value="d">D</option>
                                                                            <option value="e">E</option>
                                                                        </select>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end ">
                                            <a href="javascript:void(0)" class="btn btn-warning me-1 mb-1 btn-add"><i
                                                    data-feather="plus"></i>Tambah Soal</a>&nbsp;
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
            $('#soal').ckeditor();
            var x = 1;
            $('.btn-add').click(function() {
                var type = $('#type').val();
                console.log(type);
                if (type == '') {
                    Swal.fire(
                        'Peringatan!',
                        'Pilih tipe soal dulu!',
                        'error'
                    )
                } else if (type == 'pilgan') {
                    var fieldHtml = `<tr><td>
                                        <label><b>Soal</b></label>
                                        <textarea type="text" class="form-control mt-2" id="soal${x}" name="data[${x}][question]"></textarea>
                                        <label><b>Gambar</b></label>
                                        <div class="form-group has-icon-left mt-2">
                                            <div class="position-relative">
                                                <input type="file" name="data[${x}][image]" class="form-control"
                                                    placeholder="" id="first-name-icon">
                                                <div class="form-control-icon">
                                                    <i data-feather="upload"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <label><b>Pilihan 1</b></label>
                                        <textarea type="text" class="form-control mt-2 pilihan" id="" name="data[${x}][choice_1]"></textarea>
                                        <label><b>Pilihan 2</b></label>
                                        <textarea type="text" class="form-control mt-2 pilihan" id="" name="data[${x}][choice_2]"></textarea>
                                        <label><b>Pilihan 3</b></label>
                                        <textarea type="text" class="form-control mt-2 pilihan" id="" name="data[${x}][choice_3]"></textarea>
                                        <label><b>Pilihan 4</b></label>
                                        <textarea type="text" class="form-control mt-2 pilihan" id="" name="data[${x}][choice_4]"></textarea>
                                        <label><b>Pilihan 5</b></label>
                                        <textarea type="text" class="form-control mt-2 pilihan" id="" name="data[${x}][choice_5]"></textarea>
                                        <label><b>Jawaban</b></label>
                                        <select class="form-control mt-2 jawaban" name="data[${x}][key]">
                                            <option value="">Pilih Jawaban</option>
                                            <option value="a">A</option>
                                            <option value="b">B</option>
                                            <option value="c">C</option>
                                            <option value="d">D</option>
                                            <option value="e">E</option>
                                        </select>
                                        <a href="javasript:void(0)" class="btn icon icon-left btn-danger mt-2 btn-delete"><i
                                                    data-feather="alert-circle"></i> Delete row</a>
                                    </td></tr>`;
                } else if (type == 'essay') {
                    var fieldHtml = `<tr><td>
                                        <label><b>Soal</b></label>
                                        <textarea type="text" class="form-control mt-2" id="soal${x}" name="data[${x}][question]"></textarea>
                                        <label><b>Gambar</b></label>
                                        <div class="form-group has-icon-left mt-2">
                                            <div class="position-relative">
                                                <input type="file" name="data[${x}][image]" class="form-control"
                                                    placeholder="" id="first-name-icon">
                                                <div class="form-control-icon">
                                                    <i data-feather="upload"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <label style="display:none"><b>Pilihan 1</b></label>
                                        <textarea style="display:none" type="text" class="form-control mt-2 pilihan" id="" name="data[${x}][choice_1]">NULL</textarea>
                                        <label style="display:none"><b>Pilihan 2</b></label>
                                        <textarea style="display:none" type="text" class="form-control mt-2 pilihan" id="" name="data[${x}][choice_2]">NULL</textarea>
                                        <label style="display:none"><b>Pilihan 3</b></label>
                                        <textarea style="display:none" type="text" class="form-control mt-2 pilihan" id="" name="data[${x}][choice_3]">NULL</textarea>
                                        <label style="display:none"><b>Pilihan 4</b></label>
                                        <textarea style="display:none" type="text" class="form-control mt-2 pilihan" id="" name="data[${x}][choice_4]">NULL</textarea>
                                        <label style="display:none"><b>Pilihan 5</b></label>
                                        <textarea style="display:none" type="text" class="form-control mt-2 pilihan" id="" name="data[${x}][choice_5]">NULL</textarea>
                                        <label style="display:none"><b>Jawaban</b></label>
                                        <select style="display:none" class="form-control mt-2 jawaban" name="data[${x}][key]">
                                            <option value="">Pilih Jawaban</option>
                                            <option value="a">A</option>
                                            <option value="b">B</option>
                                            <option value="c">C</option>
                                            <option value="d">D</option>
                                            <option value="e">E</option>
                                            <option value="null" selected>NULL</option>
                                        </select>
                                        <a href="javasript:void(0)" class="btn icon icon-left btn-danger mt-2 btn-delete"><i
                                                    data-feather="alert-circle"></i> Delete row</a>
                                    </td></tr>`;
                }

                $('#table_soal').append(fieldHtml);
                $('#soal' + x).ckeditor();
                x++
            });

            $('#type').on('change', function() {
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Mengubah tipe Soal akan mereset soal yang sudah ada!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, ubah tipe soal!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#table_soal').empty();
                    } else {
                        $('#type').val('');
                    }
                })
            })

            $(document).on('click', '.btn-delete', function() {

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Do you want to delete the column!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $(this).closest('tr').remove();
                        // x--
                    }
                })
            });

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
                            url: "{{ route('question.store') }}",
                            type: "POST",
                            data: formData,
                            cache: false,
                            contentType: false,
                            processData: false,
                            success: function(response) {
                                if (response.success == true) {
                                    Swal.fire(
                                        'Berhasil!',
                                        'Data berhasil di simpan.',
                                        'success'
                                    )
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
                                var error = response.responseJSON;
                                let arr_err = [];
                                $.each(error.errors, function(k, v) {
                                    arr_err.push(v + '<br/>');
                                });
                                $.notify(`<strong>Warning</strong><br> ${arr_err} !`, {
                                    allow_dismiss: false,
                                    type: 'danger'
                                });
                            }
                        });
                    }
                })
            })
        })
    </script>
@endsection
