@extends('layouts.app')
@section('style')
    <style type="text/css">
        .scroll {
            max-height: 850px;
            overflow-y: auto;
        }

        #style-5::-webkit-scrollbar-track {
            -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
            background-color: #F5F5F5;
        }

        #style-5::-webkit-scrollbar {
            width: 10px;
            background-color: #F5F5F5;
        }

        #style-5::-webkit-scrollbar-thumb {
            background-color: #0ae;

            background-image: -webkit-gradient(linear, 0 0, 0 100%,
                    color-stop(.5, rgba(255, 255, 255, .2)),
                    color-stop(.5, transparent), to(transparent));
        }

    </style>
@endsection
@section('content')
    <div class="main-content container-fluid" style="margin-top: -30px !important">
        <section class="section">
            <div class="row">
                <div class="col-8">
                    <div class="card mt-2">
                        <div class="card-header">
                            <h4><b>Koreksi Ujian</b></h4>
                            <hr>
                        </div>
                        <div class="card-body scroll" id="style-5">
                            <form id="koreksi">
                                @csrf
                                @foreach ($soal as $soal)
                                    <div class="card-body">
                                        <b>Soal {{ $loop->iteration }}</b>
                                        <h5>
                                            <p>{!! $soal->question !!}</p>
                                        </h5>
                                        @if ($soal->image)
                                            <img src="{{ Storage::url('public/images/' . $soal->question_id . '/' . $soal->image) }}"
                                                class="rounded" style="width: 70px; height: 70px;">
                                        @endif
                                        @if ($soal->key != 'null' && $soal->key != '')
                                            <input type="hidden" value="{{ $soal->id }}"
                                                name="pilgan[{{ $loop->iteration }}][id]" />
                                            <div class="form-check form-check-primary">
                                                <input class="form-check-input" type="radio"
                                                    name="pilgan[{{ $loop->iteration }}][answer]" value="a" id=""
                                                    {{ $soal->answer == 'a' ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="Primary">
                                                    A. {{ $soal->choice_1 }}
                                                </label>&nbsp;
                                            </div>
                                            <div class="form-check form-check-primary">
                                                <input class="form-check-input" type="radio" value="b"
                                                    name="pilgan[{{ $loop->iteration }}][answer]" id=""
                                                    {{ $soal->answer == 'b' ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="Primary">
                                                    B. {{ $soal->choice_2 }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-primary">
                                                <input class="form-check-input" type="radio" value="c"
                                                    name="pilgan[{{ $loop->iteration }}][answer]" id=""
                                                    {{ $soal->answer == 'c' ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="Primary">
                                                    C. {{ $soal->choice_3 }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-primary">
                                                <input class="form-check-input" type="radio" value="d"
                                                    name="pilgan[{{ $loop->iteration }}][answer]" id=""
                                                    {{ $soal->answer == 'd' ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="Primary">
                                                    D. {{ $soal->choice_4 }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-primary">
                                                <input class="form-check-input" type="radio" value="e"
                                                    name="pilgan[{{ $loop->iteration }}][answer]" id=""
                                                    {{ $soal->answer == 'e' ? 'checked' : '' }} disabled>
                                                <label class="form-check-label" for="Primary">
                                                    E. {{ $soal->choice_5 }}
                                                </label>
                                            </div>
                                            <label><b>Jawaban {{ $soal->key }}</b></label>
                                        @else
                                            <div class="form-group with-title mb-3">
                                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" disabled>{{ $soal->answer }}</textarea>
                                            </div>
                                            <input type="hidden" name="koreksi[{{ $loop->iteration }}][id_soal]"
                                                value="{{ $soal->detail_questions_id }}" />
                                            <div class="form-check form-check-primary">
                                                <input class="form-check-input" type="radio" value="correct"
                                                    name="koreksi[{{ $loop->iteration }}][answer]"  {{ $soal->status == 'correct' ? 'checked' : '' }} id="">
                                                <label class="form-check-label" for="Primary">
                                                    Benar
                                                </label>
                                            </div>
                                            <div class="form-check form-check-primary">
                                                <input class="form-check-input" type="radio" value="wrong"
                                                    name="koreksi[{{ $loop->iteration }}][answer]"  {{ $soal->status == 'wrong' ? 'checked' : '' }} id="">
                                                <label class="form-check-label" for="Primary">
                                                    Salah
                                                </label>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                                @if ($soal->key == 'null' || $soal->key == '')
                                    <input type="hidden" name="exam_id" value="{{ $data->exam_id }}" />
                                    <input type="hidden" name="participant_id" value="{{ $participant->id }}" />
                                    <input type="hidden" name="participant_session_id" value="{{ $data->id }}" />
                                    <div class="col-12 d-flex justify-content-end ">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Koreksi</button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-header">
                            <h4><b>Detail Ujian</b>
                                <hr>
                            </h4>
                            <div class="row">
                                <div class="col-4">
                                    <p>Nama</p>
                                </div>
                                <div class="col-8">: <b>{{ $participant->fullname }}</b></div>
                                <div class="col-4">
                                    <p>Kelas</p>
                                </div>
                                <div class="col-8">: <b>{{ $participant->kelas->name }}</b></div>
                                <div class="col-4">
                                    <p>Jurusan</p>
                                </div>
                                <div class="col-8">: <b>{{ $participant->major->major }}</b></div>
                                <div class="col-4">
                                    <p>Jumlah soal</p>
                                </div>
                                <div class="col-8">: {{ $banyak_soal }}</div>
                                <div class="col-4">
                                    <p>Benar</p>
                                </div>
                                <div class="col-8">: {{ $benar }}</div>
                                <div class="col-4">
                                    <p>Salah</p>
                                </div>
                                <div class="col-8">: {{ $salah }}</div>
                                <div class="col-4">
                                    <p>Tidak di jawab</p>
                                </div>
                                <div class="col-8">: {{ $kosong }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script type="application/javascript">
        $(document).ready(function() {
            $('#koreksi').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData();
                var data = $('#koreksi').serializeArray();
                $.each(data, function(key, input) {
                    formData.append(input.name, input.value);
                });


                $.ajax({
                    url: "{{ route('koreksi') }}",
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success == true) {
                            window.location.href =
                                "{{ route('answer.index') }}";
                            sessionStorage.setItem('success', response.message);
                        } else {
                            $.notify(`<strong>Warning</strong><br> ${response.message} !`, {
                                allow_dismiss: false,
                                type: 'danger'
                            });
                        }
                    },
                    error: function(response) {
                        $.notify(`<strong>Warning</strong><br> ${response.message} !`, {
                            allow_dismiss: false,
                            type: 'danger'
                        });
                    }
                });
            })
        })
    </script>
@endsection
