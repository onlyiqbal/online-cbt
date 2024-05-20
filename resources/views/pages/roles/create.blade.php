@extends('layouts.app')
@section('content')
    <div class="main-content container-fluid">
        <section class="section">
            <a href="{{ route('roles.index') }}" class="btn icon icon-left btn-primary"><i data-feather="arrow-left"></i>
                Kembali</a>
            <div class="ml-4 mr-4 mt-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add New Role</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="form_roles" class="form form-horizontal">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <label>Name</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" name="name" class="form-control"
                                                        placeholder="Role Name" id="first-name-icon">
                                                    <div class="form-control-icon">
                                                        <i data-feather="shield"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="name-error"></span>
                                                <span class="text-danger" id="permission-error"></span>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-2">
                                                <label>Aktifkan semua permission</label>
                                            </div>
                                            <div class="col-md-8">
                                                <li class="d-inline-block me-2 mb-1">
                                                    <div class='form-check'>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" id="check-all"
                                                                class="form-check-input form-check-success"
                                                                name="customCheck" id="customColorCheck3">
                                                            <label class="form-check-label"
                                                                for="customColorCheck3">Aktifkan</label>
                                                        </div>
                                                    </div>
                                                </li>
                                            </div>
                                            <div class="row">
                                                @foreach ($permissions as $value)
                                                    <div class="col-lg-3 col-md-6">
                                                        <div class="card card-stats shadow-none">
                                                            <div class="card-body">
                                                                <div class="row justify-content-md-center">
                                                                    <table class="table table-hover table-bordered">
                                                                        <tr>
                                                                            <td>
                                                                                <li class="d-inline-block">
                                                                                    <div class='form-check'>
                                                                                        <div
                                                                                            class="custom-control custom-checkbox">
                                                                                            <input type="checkbox"
                                                                                                value="{{ $value->id }}"
                                                                                                id="permission"
                                                                                                name="permission[]"
                                                                                                class="form-check-input form-check-success"
                                                                                                name="customCheck"
                                                                                                id="customColorCheck2">
                                                                                            <label class="form-check-label"
                                                                                                for="customColorCheck2">{{ $value->name }}</label>
                                                                                        </div>
                                                                                    </div>
                                                                                </li>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="col-12 d-flex justify-content-end ">
                                                <button type="submit" class="btn btn-primary me-1 mb-1">Submit</button>
                                                <button type="reset"
                                                    class="btn btn-light-secondary me-1 mb-1">Reset</button>
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

            $('#check-all').click(function(event) {
                if (this.checked) {
                    $(':checkbox').each(function() {
                        this.checked = true;
                    });
                } else {
                    $(':checkbox').each(function() {
                        this.checked = false;
                    });
                }
            });

            $('#form_roles').on('submit', function(e) {
                e.preventDefault();
                formData = new FormData(this);
                $('#name-error').text('');
                $('#permission-error').text('');

                $.ajax({
                    url: "{{ route('roles.store') }}",
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.success == true) {
                            window.location.href = "{{ route('roles.index') }}";
                            sessionStorage.setItem('success', response.message);
                        }
                    },
                    error: function(response) {
                        $('#name-error').text(response.responseJSON.errors.name);
                        $('#permission-error').text(response.responseJSON.errors.permission);
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
