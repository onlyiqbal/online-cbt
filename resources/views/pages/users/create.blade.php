@extends('layouts.app')
@section('content')
    <div class="main-content container-fluid">
        <section class="section">
            <a href="{{ route('users.index') }}" class="btn icon icon-left btn-primary"><i data-feather="arrow-left"></i>
               Kembali</a>
            <div class="ml-4 mr-4 mt-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add New User</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="form_user" class="form form-horizontal">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Name</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" name="name" class="form-control" placeholder="Name"
                                                        id="first-name-icon">
                                                    <div class="form-control-icon">
                                                        <i data-feather="user"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="name-error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Email</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="email" name="email" class="form-control"
                                                        placeholder="Email" id="first-name-icon">
                                                    <div class="form-control-icon">
                                                        <i data-feather="mail"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="email-error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Role</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <select name="role" class="form-control">
                                                        <option value="">Pilih Role</option>
                                                        @foreach ($roles as $data)
                                                            <option value="{{ $data->name }}">{{ $data->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <div class="form-control-icon">
                                                        <i data-feather="shield"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="role-error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Password</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="password" name="password" class="form-control"
                                                        placeholder="Password">
                                                    <div class="form-control-icon">
                                                        <i data-feather="lock"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="password-error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Konfirmasi Password</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="password" name="confirm-password" class="form-control"
                                                        placeholder="Konfirmasi Password">
                                                    <div class="form-control-icon">
                                                        <i data-feather="lock"></i>
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
        </section>
    </div>
    <script type="application/javascript">
    $(document).ready(function() {
        $('#form_user').on('submit', function(e) {
            e.preventDefault();
            formData = new FormData(this);
            $('#name-error').text('');
            $('#email-error').text('');
            $('#role-error').text('');
            $('#password-error').text('');

            $.ajax({
            url: "{{ route('users.store') }}",
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success == true) {
                    window.location.href = "{{route('users.index')}}";
                    sessionStorage.setItem('success', response.message);
                }
            },
            error: function(response) {
                $('#name-error').text(response.responseJSON.errors.name);
                $('#email-error').text(response.responseJSON.errors.email);
                $('#role-error').text(response.responseJSON.errors.role);
                $('#password-error').text(response.responseJSON.errors.password);
                $.notify('<strong>Warning</strong> Isian tidak valid !', { 
                    allow_dismiss: false,
                    type: 'danger'
                });
            }
        });
        })
    })
    </script>
@endsection
