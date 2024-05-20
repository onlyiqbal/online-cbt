@extends('layouts.app')
@section('content')
    <div class="main-content container-fluid">
        <section class="section">
            <div class="ml-4 mr-4 mt-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Update Profile</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="form_mapels" class="form form-horizontal">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Username</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <label>{{$user->name}}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4 mt-2">
                                            <label>Password</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="password" name="password" class="form-control" placeholder="********"
                                                        id="first-name-icon">
                                                    <div class="form-control-icon">
                                                        <i data-feather="lock"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="password-error"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Confirm Password</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="password" name="confirm_password" class="form-control" placeholder="********"
                                                        id="first-name-icon">
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
        $('#form_mapels').on('submit', function(e) {
            e.preventDefault();
            formData = new FormData(this);
            $('#password-error').text('');

            $.ajax({
            url: "{{ route('profil.update') }}",
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
                    $.notify(`<strong>Warning</strong> ${response.message}!`, { 
                    allow_dismiss: false,
                    type: 'danger'
                });
                }
            },
            error: function(response) {
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
