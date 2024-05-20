@extends('layouts.app')
@section('content')
    <div class="main-content container-fluid" style="margin-top: -30px !important">
        <section class="section">
            @can('peserta-create')
                <a href="{{ route('participant.index') }}" class="btn icon icon-left btn-primary"><i
                        data-feather="arrow-left"></i>
                    Kembali</a>
            @endcan
            <div class="ml-4 mr-4 mt-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Peserta</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="form_participant" enctype="multipart/form-data" class="form form-horizontal">
                                @csrf
                                @method('PUT')
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Nama Lengkap</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" name="fullname" value="{{ $participant->fullname }}"
                                                        class="form-control" placeholder="Name" id="first-name-icon">
                                                    <div class="form-control-icon">
                                                        <i data-feather="user"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="fullname-error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Username</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" name="name" class="form-control"
                                                        value="{{ $participant->user->name }}" placeholder="Username"
                                                        id="first-name-icon">
                                                    <div class="form-control-icon">
                                                        <i data-feather="user-plus"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="username-error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Email</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="email" name="email" class="form-control"
                                                        value="{{ $participant->user->email }}" placeholder="Email"
                                                        id="first-name-icon">
                                                    <div class="form-control-icon">
                                                        <i data-feather="mail"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="email-error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Kelas</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <select name="class" class="form-control">
                                                        <option value="">Pilih Kelas</option>
                                                        @foreach ($class as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $item->id == $participant->class_room_id ? 'selected' : '' }}>
                                                                {{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="form-control-icon">
                                                        <i data-feather="trello"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="class-error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Jurusan</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <select name="major" class="form-control">
                                                        <option value="">Pilih Jurusan</option>
                                                        @foreach ($major as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ $item->id == $participant->major_id ? 'selected' : '' }}>
                                                                {{ $item->major }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="form-control-icon">
                                                        <i data-feather="trello"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="major-error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Jenis Kelamin</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <select name="jenkel" class="form-control">
                                                        <option value="">Pilih Jenis Kelamin</option>
                                                        <option value="male"
                                                            {{ $participant->jen_kel == 'male' ? 'selected' : '' }}>Laki
                                                            Laki</option>
                                                        <option value="female"
                                                            {{ $participant->jen_kel == 'female' ? 'selected' : '' }}>
                                                            Perempuan</option>
                                                    </select>
                                                    <div class="form-control-icon">
                                                        <i data-feather="repeat"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="jenkel-error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Nama Sekolah</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input name="shcool_name" type="text" name="password"
                                                        class="form-control" value="{{ $participant->shcool_name }}"
                                                        placeholder="Nama Sekolah">
                                                    <div class="form-control-icon">
                                                        <i data-feather="hard-drive"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="shcool_name-error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Photo</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="file" name="photo" class="form-control"
                                                        placeholder="Photo" id="photo" onchange="previewImage();">
                                                    <div class="form-control-icon">
                                                        <i data-feather="file"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <span class="text-danger" id="photo-error"></span>
                                            <img id="image-preview" style="width: 150px; display: none"
                                                alt="image preview" />
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
        </section>
    </div>
    <script type="application/javascript">
        $(document).ready(function() {
            $('#form_participant').on('submit', function(e) {
                e.preventDefault();
                var id = "{{ $participant->id }}";
                formData = new FormData(this);
                $('#fullname-error').text('');
                $('#username-error').text('');
                $('#email-error').text('');
                $('#class-error').text('');
                $('#major-error').text('');
                $('#jenkel-error').text('');
                $('#shcool_name-error').text('');
                $('#photo-error').text('');

                $.ajax({
                    url: "/participant/"+id,
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success == true) {
                            window.location.href = "{{ route('participant.index') }}";
                            sessionStorage.setItem('success', response.message);
                        }
                    },
                    error: function(response) {
                        $('#fullname-error').text(response.responseJSON.errors.fullname);
                        $('#username-error').text(response.responseJSON.errors.name);
                        $('#email-error').text(response.responseJSON.errors.email);
                        $('#class-error').text(response.responseJSON.errors.class);
                        $('#major-error').text(response.responseJSON.errors.major);
                        $('#jenkel-error').text(response.responseJSON.errors.jenkel);
                        $('#shcool_name-error').text(response.responseJSON.errors.shcool_name);
                        $('#photo-error').text(response.responseJSON.errors.photo);
                        $.notify('<strong>Warning</strong> Isian tidak valid !', {
                            allow_dismiss: false,
                            type: 'danger'
                        });
                    }
                });
            })

            var img = "{{ $participant->photo }}";

            if ($('#photo').val() == '') {
                document.getElementById("image-preview").style.display = "block";
                if (img == '') {
                    document.getElementById("image-preview").src = "{{ asset('img/image/imagePlaceholder.png') }}";
                } else {
                    document.getElementById("image-preview").src =
                        "{{ Storage::url('public/images/photo_participant/') . $participant->photo }}";
                }
            } else {
                $('#image-preview').empty();
            }
        })

        function previewImage() {
            document.getElementById("image-preview").style.display = "block";
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("photo").files[0]);

            oFReader.onload = function(oFREvent) {
                document.getElementById("image-preview").src = oFREvent.target.result;
            };
        };
    </script>
@endsection
