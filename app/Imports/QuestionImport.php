<?php

namespace App\Imports;

use App\Models\DetailQuestion;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionImport implements ToModel,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function  __construct($id)
    {
        $this->id = $id;
    }

    public function model(array $row)
    {
        return new DetailQuestion([
            'question_id' => $this->id,
            'question'    => $row['soal'],
            'choice_1'    => $row['pilihan1'],
            'choice_2'    => $row['pilihan2'],
            'choice_3'    => $row['pilihan3'],
            'choice_4'    => $row['pilihan4'],
            'choice_5'    => $row['pilihan5'],
            'key'         => $row['key'],
        ]);
    }
}
