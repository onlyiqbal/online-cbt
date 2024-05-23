@extends('layouts.app')
@section('content')
<div class="main-content container-fluid">
    <section class="section">
        <a href="{{ route('exam-session.index') }}" class="btn icon icon-left btn-primary"><i
                data-feather="arrow-left"></i>
            Kembali</a>
        <div class="ml-4 mr-4 mt-2">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Sesi Ujian</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form id="form_exam_session" class="form form-horizontal">
                            @csrf
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Sesi</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input type="text" name="name" class="form-control"
                                                    placeholder="Ex Sesi 1" id="first-name-icon">
                                                <div class="form-control-icon">
                                                    <i data-feather="clock"></i>
                                                </div>
                                            </div>
                                            <span class="text-danger" id="name-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Ujian Di Mulai</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input type="datetime" name="time_start" class="form-control"
                                                    placeholder="Ex 20:15" id="first-name-icon">
                                            </div>
                                            <span class="text-danger" id="time_start-error"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Ujian Berakhir</label>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group has-icon-left">
                                            <div class="position-relative">
                                                <input type="datetime" name="time_end" class="form-control"
                                                    placeholder="Ex 09:25" id="first-name-icon">
                                            </div>
                                            <span class="text-danger" id="time_end-error"></span>
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
        $('#form_exam_session').on('submit', function(e) {
            e.preventDefault();
            formData = new FormData(this);
            $('#name-error').text('');
            $('#time_start-error').text('');
            $('#time_end-error').text('');

            $.ajax({
            url: "{{ route('exam-session.store') }}",
            type: "POST",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.success == true) {
                    window.location.href = "{{route('exam-session.index')}}";
                    sessionStorage.setItem('success', response.message);
                }
            },
            error: function(response) {
                $('#name-error').text(response.responseJSON.errors.name);
                $('#time_start-error').text(response.responseJSON.errors.time_start);
                $('#time_end-error').text(response.responseJSON.errors.time_end);
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