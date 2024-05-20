@extends('layouts.app')
@section('content')
    <div class="main-content container-fluid">
        <section class="section">
            <a href="{{ route('class.index') }}" class="btn icon icon-left btn-primary"><i data-feather="arrow-left"></i>
               Kembali</a>
            <div class="ml-4 mr-4 mt-2">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add New Class</h4>
                    </div>
                    <div class="card-content">
                        <div class="card-body">
                            <form id="form_class" class="form form-horizontal">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Kelas</label>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group has-icon-left">
                                                <div class="position-relative">
                                                    <input type="text" name="name" class="form-control" placeholder="Kelas"
                                                        id="first-name-icon">
                                                    <div class="form-control-icon">
                                                        <i data-feather="trello"></i>
                                                    </div>
                                                </div>
                                                <span class="text-danger" id="name-error"></span>
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
        $('#form_class').on('submit', function(e) {
            e.preventDefault();
            formData = new FormData(this);
            $('#name-error').text('');
            $('#major-error').text('');

            $.ajax({
            url: "{{ route('class.store') }}",
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success == true) {
                    window.location.href = "{{route('class.index')}}";
                    sessionStorage.setItem('success', response.message);
                }
            },
            error: function(response) {
                $('#name-error').text(response.responseJSON.errors.name);
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
