@extends('layouts.app')
@section('content')
    <div class="main-content container-fluid">
        <section class="section">
            <div class="card mt-2">
                <div class="card-header">
                    <h4>Nilai Ujian</h4>
                </div>
                <div class="row">
                    <div class="col-md-4">
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <input class="form-control" id="date" name="date" type="date">
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="class" name="class" data-toggle="select">
                                <option value="" selected>Select Kelas</option>
                                @foreach ($class as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="major" name="major" data-toggle="select">
                                <option value="" selected>Select Kategori Kelas</option>
                                @foreach ($major as $item)
                                    <option value="{{ $item->id }}">{{ $item->major }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <select class="form-control" id="exam" name="exam" data-toggle="select">
                                <option value="" selected>Select Ujian</option>
                                @foreach ($exam as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" align="center">
                            <button type="button" name="filter" id="filter" class="btn btn-primary">Filter</button>
                            <button type="button" name="reset" id="reset" class="btn btn-danger">Reset</button>
                        </div>
                    </div>
                    <div class="col-md-4">
                    </div>
                </div>
                <div class="card-body">
                    <table class='table table-light' id="table_nilai" style="width: 100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Ujian</th>
                                <th>Kelas</th>
                                <th>Kategori Kelas</th>
                                <th>Jawaban Benar Pilgan</th>
                                <th>Nilai Pilgan</th>
                                <th>Jawaban Benar Essay</th>
                                <th>Nilai Essay</th>
                                <th>Nilai</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </section>
    </div>
    <script type="application/javascript">
    $(document).ready(function() {
            fill_datatable();

            function fill_datatable(filter_date = '', filter_class = '', filter_major = '', filter_exam = '') {
                $('#table_nilai').DataTable({
                    processing: true,
                    serverside: true,
                    paging: false,
                    dom: 'Blfrtip',
                    buttons: [
                            {
                                extend: 'pdf',
                                orientation: 'portrait',
                                pageSize: 'LEGAL',
                                download: 'open',
                                exportOptions: {
                                    columns: ':visible',
                                }
                            },
                            {
                                extend: 'excel',
                                orientation: 'portrait',
                                pageSize: 'LEGAL',
                                download: 'open',
                                exportOptions: {
                                    columns: ':visible',
                                }
                            },
                            {
                                extend: 'print',
                                orientation: 'portrait',
                                pageSize: 'LEGAL',
                                download: 'open',
                                exportOptions: {
                                    columns: ':visible',
                                },
                                footer: true,
                                autoPrint: true
                            },
                        'copy', 'colvis'
                    ],
                    ajax: {
                        url: "{{ route('nilai.index') }}",
                        data: {
                            filter_date: filter_date,
                            filter_class: filter_class,
                            filter_exam: filter_exam,
                            filter_major: filter_major,
                        }
                        // type: 'GET'
                    },
                    responsive: true,
                    columns: [{
                            data: 'DT_RowIndex',
                        },
                        {
                            data: 'nama',
                        },
                        {
                            data: 'ujian',
                        },
                        {
                            data: 'kelas',
                        },
                        {
                            data: 'jurusan',
                        },
                        {
                            data: 'jawaban_pilgan',
                        },
                        {
                            data: 'nilai_pilgan',
                        },
                        {
                            data: 'jawaban_essay',
                        },
                        {
                            data: 'nilai_essay',
                        },
                        {
                            data: 'nilai_rata_rata',
                        },
                    ]
                })
            }

            $('#filter').click(function() {
                var filter_date = $('#date').val();
                var filter_class = $('#class').val();
                var filter_major = $('#major').val();
                var filter_exam = $('#exam').val();

                if (filter_date != '' && filter_class != '' && filter_class != '' && filter_exam != '') {
                    $('#table_nilai').DataTable().destroy();
                    fill_datatable(filter_date, filter_class, filter_major, filter_exam);
                } else {
                    $.notify(`<strong>warning</strong> Select filter option</> !`, {
                        allow_dismiss: false,
                        type: 'warning'
                    });
                }
            });
            $('#reset').click(function() {
                $('#date').val('');
                $('#class').val('');
                $('#major').val('');
                $('#exam').val('');
                $('#table_nilai').DataTable().destroy();
                fill_datatable();
            });

            if (sessionStorage.getItem('success')) {
                let data = sessionStorage.getItem('success');
                $.notify(`<strong>Success</strong> ${data}</> !`, {
                    allow_dismiss: false,
                    type: 'success'
                });

                sessionStorage.clear();
            }
        });
</script>
@endsection
