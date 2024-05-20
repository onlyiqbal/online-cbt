@extends('layouts.app')
@section('content')
    <div class="main-content container-fluid" style="margin-top: -30px !important">
        <section class="section">
            <a href="{{ route('teachers.index') }}" class="btn icon icon-left btn-primary"><i
                    data-feather="arrow-left"></i>
                Kembali</a>
            <div class="ml-4 mr-4 mt-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Guru</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="form_teachers" enctype="multipart/form-data" class="form form-horizontal">
                                @csrf
                                @method('PUT')
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>NIP</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" name="nip" class="form-control"
                                                        value="{{ $teachers->nip }}" placeholder="NIP" id="first-name-icon">
                                                    <div class="form-control-icon">
                                                        <i data-feather="briefcase"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="nip-error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Nama Lengkap</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" name="fullname" class="form-control"
                                                        value="{{ $teachers->fullname }}" placeholder="Name"
                                                        id="first-name-icon">
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
                                                    <input type="text" name="name" value="{{ $teachers->user->name }}"
                                                        class="form-control" placeholder="Username" id="first-name-icon">
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
                                                    <input type="email" name="email" value="{{ $teachers->user->email }}"
                                                        class="form-control" placeholder="Email" id="first-name-icon">
                                                    <div class="form-control-icon">
                                                        <i data-feather="mail"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="email-error"></span>
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
                                                            {{ $teachers->jen_kel == 'male' ? 'selected' : '' }}>Laki
                                                            Laki</option>
                                                        <option value="female"
                                                            {{ $teachers->jen_kel == 'female' ? 'selected' : '' }}>
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
            $('#form_teachers').on('submit', function(e) {
                e.preventDefault();
                var url = "{{route('teachers.update',$teachers->id)}}";
                formData = new FormData(this);
                $('#nip-error').text('');
                $('#fullname-error').text('');
                $('#username-error').text('');
                $('#email-error').text('');
                $('#jenkel-error').text('');
                $('#photo-error').text('');

                $.ajax({
                    url:url,
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success == true) {
                            window.location.href = "{{ route('teachers.index') }}";
                            sessionStorage.setItem('success', response.message);
                        }
                    },
                    error: function(response) {
                        $('#nip-error').text(response.responseJSON.errors.nip);
                        $('#fullname-error').text(response.responseJSON.errors.fullname);
                        $('#username-error').text(response.responseJSON.errors.name);
                        $('#email-error').text(response.responseJSON.errors.email);
                        $('#jenkel-error').text(response.responseJSON.errors.jenkel);
                        $('#photo-error').text(response.responseJSON.errors.photo);
                        $.notify('<strong>Warning</strong> Isian tidak valid !', {
                            allow_dismiss: false,
                            type: 'danger'
                        });
                    }
                });
            })

            var img = "{{ $teachers->photo }}";

            if ($('#photo').val() == '') {
                document.getElementById("image-preview").style.display = "block";
                if (img == '') {
                    document.getElementById("image-preview").src = "{{ asset('img/imagePlaceholder.png') }}";
                } else {
                    document.getElementById("image-preview").src =
                        "{{ Storage::url('public/images/photo_teacher/') . $teachers->photo }}";
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
