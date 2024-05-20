@extends('layouts.app-exam')
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
    <section class="section mt-4" onmousedown="WhichButton(event)">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    {{-- <div class="card-header">
                    </div> --}}
                    <div class="card-body scroll" id="style-5">
                        <form id="ujian">
                            @csrf
                            <div class="row">
                                @if (!$pilgan->isEmpty())
                                    <h3>Soal Pilihan Ganda</h3>
                                    @foreach ($pilgan as $soal)
                                        <div class="card-body">
                                            <b>Soal {{ $loop->iteration }}</b>
                                            <h5>
                                                <p>{!! $soal->question !!}</p>
                                            </h5>
                                            @if ($soal->image)
                                                <img src="{{ Storage::url('public/images/' . $soal->question_id . '/' . $soal->image) }}"
                                                    class="rounded" style="width: 70px; height: 70px;">
                                            @endif
                                            <input type="hidden" value="{{$soal->id}}" name="pilgan[{{ $loop->iteration }}][id]" />
                                            <div class="form-check form-check-primary">
                                                <input class="form-check-input" type="radio" value="a"
                                                    name="pilgan[{{ $loop->iteration }}][answer]" id="">
                                                <label class="form-check-label" for="Primary">
                                                    A. {{ $soal->choice_1 }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-primary">
                                                <input class="form-check-input" type="radio" value="b"
                                                    name="pilgan[{{ $loop->iteration }}][answer]" id="">
                                                <label class="form-check-label" for="Primary">
                                                    B. {{ $soal->choice_2 }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-primary">
                                                <input class="form-check-input" type="radio" value="c"
                                                    name="pilgan[{{ $loop->iteration }}][answer]" id="">
                                                <label class="form-check-label" for="Primary">
                                                    C. {{ $soal->choice_3 }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-primary">
                                                <input class="form-check-input" type="radio" value="d"
                                                    name="pilgan[{{ $loop->iteration }}][answer]" id="">
                                                <label class="form-check-label" for="Primary">
                                                    D. {{ $soal->choice_4 }}
                                                </label>
                                            </div>
                                            <div class="form-check form-check-primary">
                                                <input class="form-check-input" type="radio" value="e"
                                                    name="pilgan[{{ $loop->iteration }}][answer]" id="">
                                                <label class="form-check-label" for="Primary">
                                                    E. {{ $soal->choice_5 }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                @endif
                                @if (!$essay->isEmpty())
                                    <hr>
                                    <h3>Soal Essay</h3>
                                    @foreach ($essay as $soal)
                                        <div class="card-body">
                                            <b>Soal {{ $loop->iteration }}</b>
                                            <h5>
                                                <p>{!! $soal->question !!}</p>
                                            </h5>
                                            @if ($soal->image)
                                                <img src="{{ Storage::url('public/images/' . $soal->question_id . '/' . $soal->image) }}"
                                                    class="rounded" style="width: 70px; height: 70px;">
                                            @endif
                                            <input type="hidden" value="{{$soal->id}}" name="essay[{{ $loop->iteration }}][id]" />
                                            <div class="form-group with-title mb-3">
                                                <textarea class="form-control" name="essay[{{ $loop->iteration }}][answer]" id="exampleFormControlTextarea1"
                                                    rows="3"></textarea>
                                                <label>Isi Jawaban di sini</label>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h4><b>Sisa Waktu Mengerjakan Ujian</b>
                            <hr>
                            <p id="countdown"></p>
                        </h4>
                        <div class="alert alert-danger"><i data-feather="alert-circle"></i>Perhatian ! Jika Klik tombol
                            REFRESH & CLOSE maka jawaban akan hilang ! .
                        </div>
                    </div>
                    <a href="javascript:void(0)" onclick="simpanJawaban()" class="btn btn-success">Selesaikan Ujian</a>
                    {{-- <div class="card-body">
                        <div class="row">

                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </section>
    @include('pages.start-exam.modal');
    <script>
        $(document).ready(function() {

        })
        // addEventListener("click", function() {
        //     var
        //         el = document.documentElement,
        //         rfs =
        //         el.requestFullScreen ||
        //         el.webkitRequestFullScreen ||
        //         el.mozRequestFullScreen;
        //     rfs.call(el);
        // });
        var countdown = "{{ $cekujian->time_end }}";
        let today = new Date().toISOString().slice(0, 10)
        var countDownDate = new Date(`${today} ${countdown}`).getTime();
        var x = setInterval(function() {
            var now = new Date().getTime();
            var distance = countDownDate - now;
            // var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            document.getElementById("countdown").innerHTML = hours + "Jam " +
                minutes + "Menit " + seconds + "Detik ";
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("countdown").innerHTML = "Waktu Habis";
                jawabanSelesai();
            }
        }, 1000);


        $(window).keydown(function(event) {
            if (event.keyCode == 116 || event.keyCode == 115 || event.keyCode == 27 || event.keyCode == 123) {
                event.preventDefault();
                return false;

            }
        });

        // $(window).bind('beforeunload', function() {
        //     return 'Are you sure you want to leave?';
        // });

        function WhichButton(event) {
            if (event.button === 2) {
                alert("Klik Kanan di batasi !")
                return false;
            }
        }

        if (performance.navigation.type == performance.navigation.TYPE_RELOAD) {
            console.info("This page is reloaded");

        } else {
            console.info("This page is not reloaded");
        }

        document.addEventListener("visibilitychange", function() {
            console.log(document.hidden);
            if (document.hidden == true) {
                // $('#modalAlert').modal('show');
            }
        })

        history.pushState(null, null, location.href);
        window.onpopstate = function() {
            history.go(1);
        };

        function simpanJawaban() {

            Swal.fire({
                title: 'Apakah kamu yakin ?',
                text: "Periksa jawaban sebelum menyelesaikan ujian !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya,Selesaikan!'
            }).then((result) => {
                if (result.isConfirmed) {
                    jawabanSelesai();
                    Swal.fire(
                        'Berhasil!',
                        'Anda telah menyelesaikan ujian.',
                        'success'
                    )
                }
            })

        }


        function jawabanSelesai() {
            var participant_id = "{{$cekujian->participant_id }}";
            var participant_session_id  = "{{$cekujian->id_participant_sessions}}";
            var formData = new FormData();
            var data = $('#ujian').serializeArray();
            $.each(data, function(key, input) {
                formData.append(input.name, input.value);
            });
            formData.append('participant_id',participant_id);
            formData.append('id_participant_sessions',participant_session_id);

            $.ajax({
                url: "{{ route('finish-exam') }}",
                type: "POST",
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(response) {
                    if (response.success == true) {
                        window.location.href = "/";
                        sessionStorage.setItem('success', response.message);
                    }
                    else{
                        window.location.href = "/";
                        sessionStorage.setItem('success', response.message);
                    }
                },
                error: function(response) {
                    window.location.href = "/";
                        sessionStorage.setItem('success', response.message);
                }
            });
        }
    </script>
@endsection
