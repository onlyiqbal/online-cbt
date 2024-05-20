@extends('layouts.app')
@section('content')
    <div class="main-content container-fluid">
        <section class="section">
            <a href="{{ route('majors.index') }}" class="btn icon icon-left btn-primary"><i data-feather="arrow-left"></i>
               Kembali</a>
            <div class="ml-4 mr-4 mt-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Jurusan</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="form_majors" class="form form-horizontal">
                                @csrf
                                @method('PUT')
                                <input type="hidden" id="id" value="{{$class->id}}"/>
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Jurusan</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" name="major" value="{{$class->major}}" class="form-control" placeholder="Kelas"
                                                        id="first-name-icon">
                                                    <div class="form-control-icon">
                                                        <i data-feather="list"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="major-error"></span>
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
        $('#form_majors').on('submit', function(e) {
            e.preventDefault();
            formData = new FormData(this);
            let id   = $('#id').val(); 
            $('#major-error').text('');

            $.ajax({
            url: "/majors/"+ id,
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success == true) {
                    window.location.href = "{{route('majors.index')}}";
                    sessionStorage.setItem('success', response.message);
                }
            },
            error: function(response) {
                $('#major-error').text(response.responseJSON.errors.major);
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
