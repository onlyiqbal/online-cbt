@extends('layouts.app')
@section('content')
<div class="main-content container-fluid">
   <section class="section">
      <a href="{{ route('question.index') }}" class="btn icon icon-left btn-primary"><i data-feather="arrow-left"></i>
         Kembali</a>
      <div class="card mt-2">
         <div class="card-header">
            Data Detail Soal Ujian
         </div>
         <div class="card-body">
            <table class='table table-light' id="table_question" style="width: 100%">
               <thead>
                  <tr>
                     <th>No</th>
                     <th>Gambar</th>
                     <th>Soal</th>
                     <th>Pilihan 1</th>
                     <th>Pilihan 2</th>
                     <th>Pilihan 3</th>
                     <th>Pilihan 4</th>
                     <th>Pilihan 5</th>
                     <th>Kunci</th>
                  </tr>
               </thead>
               <tbody>

               </tbody>
            </table>
         </div>
      </div>
   </section>
</div>
<script type="application/javascript">
   $(document).ready(function() {
            var url = "{{route('view-detail-question',$id)}}";

            $('#table_question').DataTable({
                processing: true,
                serverside: true,
                ajax: {
                    url: url,
                    type: 'GET'
                },
                responsive: true,
                columns: [{
                        data: 'DT_RowIndex',
                    },
                    {
                        data: 'image',
                    },
                    {
                        data: 'question',
                    },
                    {
                        data: 'choice_1',
                    },
                    {
                        data: 'choice_2',
                    },
                    {
                        data: 'choice_3',
                    },
                    {
                        data: 'choice_4',
                    },
                    {
                        data: 'choice_5',
                    },
                    {
                        data: 'key',
                    },
                ]
            })
        })
</script>
@endsection