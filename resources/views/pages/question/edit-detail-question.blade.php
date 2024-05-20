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
                        <h4 class="card-title">Update Detail Ujian</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="form_question" class="form form-horizontal" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="form-body">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group mr-5" style="margin-left: -30px">
                                                    <div class="table-responsive" style="margin-top: -35px; !important">
                                                        <table class="table">
                                                            <tbody id="table_soal">
                                                                <tr>
                                                                    <td>
                                                                        <label><b>Soal</b></label>
                                                                        <textarea type="text" class="form-control" id="soal" name="question">{{ $question->question }}</textarea>
                                                                        <span class="text-danger"
                                                                            id="question-error"></span><br>
                                                                        <label><b>Gambar</b></label>
                                                                        <div class="form-group has-icon-left mt-2">
                                                                            <div class="position-relative">
                                                                                <input type="file" name="image"
                                                                                    class="form-control" placeholder=""
                                                                                    id="first-name-icon">
                                                                                <div class="form-control-icon">
                                                                                    <i data-feather="upload"></i>
                                                                                </div>
                                                                            </div>
                                                                            <span class="text-danger"
                                                                                id="image-error"></span><br>
                                                                        </div>
                                                                        <label style="display:{{ $display }}"><b>Pilihan
                                                                                1</b></label>
                                                                        <textarea style="display:{{ $display }}" type="text" class="form-control mt-2 pilihan1" id=""
                                                                            name="choice_1">{{ $question->choice_1 }}</textarea>
                                                                        <span class="text-danger"
                                                                            id="choice_1-error"></span><br>
                                                                        <label style="display:{{ $display }}"><b>Pilihan
                                                                                2</b></label>
                                                                        <textarea style="display:{{ $display }}" type="text" class="form-control mt-2 pilihan2" id=""
                                                                            name="choice_2">{{ $question->choice_2 }}</textarea>
                                                                        <span class="text-danger"
                                                                            id="choice_2-error"></span><br>
                                                                        <label style="display:{{ $display }}"><b>Pilihan
                                                                                3</b></label>
                                                                        <textarea style="display:{{ $display }}" type="text" class="form-control mt-2 pilihan3" id=""
                                                                            name="choice_3">{{ $question->choice_3 }}</textarea>
                                                                        <span class="text-danger"
                                                                            id="choice_3-error"></span><br>
                                                                        <label style="display:{{ $display }}"><b>Pilihan
                                                                                4</b></label>
                                                                        <textarea style="display:{{ $display }}" type="text" class="form-control mt-2 pilihan4" id=""
                                                                            name="choice_4">{{ $question->choice_4 }}</textarea>
                                                                        <span class="text-danger"
                                                                            id="choice_4-error"></span><br>
                                                                        <label style="display:{{ $display }}"><b>Pilihan
                                                                                5</b></label>
                                                                        <textarea style="display:{{ $display }}" type="text" class="form-control mt-2 pilihan5" id=""
                                                                            name="choice_5">{{ $question->choice_5 }}</textarea>
                                                                        <span class="text-danger"
                                                                            id="choice_5-error"></span><br>
                                                                        <label
                                                                            style="display:{{ $display }}"><b>Jawaban</b></label>
                                                                        <select style="display:{{ $display }}"
                                                                            class="form-control mt-2 jawaban" name="key">
                                                                            <option value="a"
                                                                                {{ $question->key == 'a' ? 'selected' : '' }}>
                                                                                A</option>
                                                                            <option value="b"
                                                                                {{ $question->key == 'b' ? 'selected' : '' }}>
                                                                                B</option>
                                                                            <option value="c"
                                                                                {{ $question->key == 'c' ? 'selected' : '' }}>
                                                                                C</option>
                                                                            <option value="d"
                                                                                {{ $question->key == 'd' ? 'selected' : '' }}>
                                                                                D</option>
                                                                            <option value="e"
                                                                                {{ $question->key == 'e' ? 'selected' : '' }}>
                                                                                E</option>
                                                                        </select>
                                                                        <span class="text-danger"
                                                                            id="key-error"></span><br>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-end ">
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

            $('#form_question').on('submit', function(e) {
                e.preventDefault();
                formData = new FormData(this);
                formData.set('question', $('#soal').val());

                $('#question-error').text('');
                $('#image-error').text('');
                $('#choice_1-error').text('');
                $('#choice_2-error').text('');
                $('#choice_3-error').text('');
                $('#choice_4-error').text('');
                $('#choice_5-error').text('');
                $('#key-error').text('');

                var url = "{{ route('update-detail-question', $question->id) }}";

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
                                "{{ route('list-detail-question', $question->question_id) }}";
                            sessionStorage.setItem('success', response.message);
                        }
                    },
                    error: function(response) {
                        $('#question-error').text(response.responseJSON.errors
                            .question);
                        $('#image-error').text(response.responseJSON.errors
                            .image);
                        $('#choice_1-error').text(response.responseJSON.errors
                            .choice_1);
                        $('#choice_2-error').text(response.responseJSON
                            .errors
                            .choice_2);
                        $('#choice_3-error').text(response.responseJSON.errors
                            .choice_3);
                        $('#choice_4-error').text(response.responseJSON
                            .errors
                            .choice_4);
                        $('#choice_5-error').text(response.responseJSON.errors
                            .choice_5);
                        $('#key-error').text(response.responseJSON.errors
                            .key);
                        $.notify(
                            '<strong>Warning</strong> Isian tidak valid !', {
                                allow_dismiss: false,
                                type: 'danger'
                            });
                    }
                });
            })
        })
    </script>
@endsection
