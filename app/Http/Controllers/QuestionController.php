<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Mapel, ClassRoom, Major, Question, DetailQuestion, Teacher};
use DB;
use DataTables;
use Auth;
use Storage;
use Image;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\imports\QuestionImport;
use Excel;
use Response;

class QuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:soal-list|soal-create|soal-edit|soal-delete', ['only' => ['index', 'store', 'generateid']]);
        $this->middleware('permission:soal-create', ['only' => ['create', 'store', 'generateid']]);
        $this->middleware('permission:soal-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:soal-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {

        $page = 'question';
        if (Auth::user()->hasRole('guru')) {
            $question = Question::where('user_id', Auth::user()->id)->get();
        } else {
            $question = Question::all();
        }
        $auth = Auth::user();

        if ($request->ajax()) {
            return DataTables::of($question)
                ->addColumn('kode_soal', function ($row) {
                    return $row->id;
                })
                ->addColumn('type', function ($row) {
                    if ($row->type == 'pilgan') {
                        return 'Pilihan Ganda';
                    } else {
                        return 'Essay';
                    }

                })
                ->addColumn('guru', function ($row) {
                    return $row->guru->teacher->fullname;
                })
                ->addColumn('kelas', function ($row) {
                    return $row->kelas->name;
                })
                ->addColumn('mapel', function ($row) {
                    return $row->mapel->mapel;
                })
                ->addColumn('jurusan', function ($row) {
                    return $row->major->major;
                })
                ->addColumn('action', function ($row) use ($auth) {
                    $button = '';
                    if ($auth->can('soal-edit')) {
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="' . route('question.edit', $row->id) . '"><i class="fas fa-edit text-secondary"></i></a>';
                    }
                    if ($auth->can('soal-list')) {
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="' . route('list-detail-question', $row->id) . '"><i class="fas fa-eye text-secondary"></i></a>';
                    }
                    if ($auth->can('soal-delete')) {
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="javascrip:void(0)" onclick="deleteItem(this)" data-name="' . $row->id . '" data-id="' . $row->id . '"><i class="fas fa-trash-alt text-danger"></i></a>';
                    }
                    return $button;
                })
                ->rawColumns(['photo', 'action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('pages.question.index', compact('page', 'question'));

    }

    public function create()
    {

        $page = 'question';
        if (Auth::user()->hasRole('guru')) {
            $user = User::role(['guru'])
                ->leftjoin('teachers', 'teachers.user_id', 'users.id')
                ->select('users.id', 'fullname')
                ->where('users.id', Auth::user()->id)->get();
        } else {

            $user = User::role(['guru'])->leftjoin('teachers', 'teachers.user_id', 'users.id')->select('users.id', 'fullname')->get();
        }
        $mapel = Mapel::all();
        $class = ClassRoom::all();
        $major = Major::all();
        return view('pages.question.create', compact('page', 'user', 'mapel', 'class', 'major'));
    }

    public function store(Request $request)
    {

        if ($request->file('import')) {

            $this->validate($request, [
                'user_id' => 'required',
                'type' => 'required',
                'mapel_id' => 'required',
                'class_room_id' => 'required',
                'major_id' => 'required',
                'import' => 'required|max:50000|mimes:xlsx,application/csv,application/excel,application/vnd.ms-excel, application/vnd.msexcel'
            ], [
                'user_id.required' => 'Guru tidak boleh Kosong !',
                'type.required' => 'Type tidak boleh kosong !',
                'mapel_id.required' => 'Mapel tidak boleh kosong !',
                'class_room_id.required' => 'Kelas tidak boleh kosong !',
                'major_id.required' => 'Jurusan tidak tidak boleh kosong !',
            ]);

        } else {

            $this->validate($request, [
                'user_id' => 'required',
                'type' => 'required',
                'mapel_id' => 'required',
                'class_room_id' => 'required',
                'major_id' => 'required',
                'data.*.question' => 'required',
                'data.*.choice_1' => 'required',
                'data.*.choice_2' => 'required',
                'data.*.choice_3' => 'required',
                'data.*.choice_4' => 'required',
                'data.*.choice_5' => 'required',
                'data.*.key' => 'required',
                'data.*.image' => 'image|mimes:jpeg,png,jpg,svg|max:2048|nullable',
            ], [
                'user_id.required' => 'Guru tidak boleh Kosong !',
                'type.required' => 'Type tidak boleh kosong !',
                'mapel_id.required' => 'Mapel tidak boleh kosong !',
                'class_room_id.required' => 'Kelas tidak boleh kosong !',
                'major_id.required' => 'Jurusan tidak tidak boleh kosong !',
                'data.*.question.required' => 'Soal tidak boleh kosong !',
                'data.*.choice_1.required' => 'Pilihan 1 tidak boleh kosong !',
                'data.*.choice_2.required' => 'Pilihan 2 tidak boleh kosong !',
                'data.*.choice_3.required' => 'Pilihan 3 tidak boleh kosong !',
                'data.*.choice_4.required' => 'Pilihan 4 tidak boleh kosong !',
                'data.*.choice_5.required' => 'Pilihan 5 tidak boleh kosong !',
                'data.*.key.required' => 'Kunci Jawaban tidak boleh kosong !',
                'data.*.image.mimes' => 'File tidak valid!',
                'data.*.image.max' => 'File tidak boleh lebih dari 2 MB!',
            ]);
        }


        $mapel = Mapel::findOrFail($request->mapel_id);
        $prefix = substr($mapel->mapel, 0, 3);
        $major = Major::findOrFail($request->major_id);
        $prefix .= substr($major->major, 0, 3);
        $class = ClassRoom::findOrFail($request->class_room_id);
        $prefix .= substr($class->name, 0, 3);

        $data = $request->all();

        $id = IdGenerator::generate([
            'table' => 'questions',
            'length' => 11,
            'prefix' => "$prefix-",
            'reset_on_prefix_change' => true
        ]);

        DB::beginTransaction();
        try {
            $question = Question::create([
                'id' => $id,
                'type' => $data['type'],
                'mapel_id' => $data['mapel_id'],
                'class_room_id' => $data['class_room_id'],
                'major_id' => $data['major_id'],
                'user_id' => $data['user_id']
            ]);

            if ($request->file('import')) {

                Excel::import(new QuestionImport($id), request()->file('import'));
            } else {

                foreach ($data['data'] as $key => $value) {
                    $value['question_id'] = $id;
                    if (count($value) == 9) {
                        $file = $value['image'];
                        $file_name = time() . str_replace(" ", "", $file->getClientOriginalName());
                        $imageFile = Image::make($file->getRealPath());
                        $imageFile->resize(1200, 1200);
                        $file->storeAs('public/images/' . $id . $request->type . '', $file_name);
                        $value['image'] = $file_name;
                    }

                    $detail_questions = DetailQuestion::create($value);
                }
            }


            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Question create successfully !'
            ]);
        } catch (Exception $e) {

            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Question create failed !'
            ]);
        }
    }

    public function edit(Request $request, $id)
    {

        $page = 'question';
        $question = Question::findOrFail($id);
        $user = User::role(['guru'])->leftjoin('teachers', 'teachers.user_id', 'users.id')->select('users.id', 'fullname')->get();
        $mapel = Mapel::all();
        $class = ClassRoom::all();
        $major = Major::all();

        return view('pages.question.edit', compact('page', 'question', 'id', 'user', 'mapel', 'class', 'major'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'user_id' => 'required',
        ], [
            'user_id.required' => 'Guru tidak boleh Kosong !',
        ]);

        $question = Question::findOrFail($id);
        $data = $request->all();
        $question->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Question update successfully !'
        ]);
    }

    public function listDetailQuestion(Request $request, $id)
    {

        $page = 'question';
        $question = Question::findOrFail($id);

        $detail_questions = DetailQuestion::where('question_id', $id)->get();
        $auth = Auth::user();

        if ($request->ajax()) {
            return DataTables::of($detail_questions)
                ->addColumn('image', function ($row) use ($id, $question) {
                    if (is_null($row->image)) {
                        $url = asset('img/imagePlaceholder.png');
                    } else {
                        $url = Storage::url('public/images/' . $id . '/' . $row->image);
                    }
                    return $image = '<img src="' . $url . '" class="rounded" style="width: 70px; height: 70px;">';
                })
                ->addColumn('question', function ($row) {
                    return $row->question;
                })
                ->addColumn('choice_1', function ($row) {
                    if ($row->choice_1 == 'NULL') {
                        return 'Essay(null value)';
                    }
                    return $row->choice_1;
                })
                ->addColumn('choice_2', function ($row) {
                    if ($row->choice_2 == 'NULL') {
                        return 'Essay(null value)';
                    }
                    return $row->choice_2;
                })
                ->addColumn('choice_3', function ($row) {
                    if ($row->choice_3 == 'NULL') {
                        return 'Essay(null value)';
                    }
                    return $row->choice_3;
                })
                ->addColumn('choice_4', function ($row) {
                    if ($row->choice_4 == 'NULL') {
                        return 'Essay(null value)';
                    }
                    return $row->choice_4;
                })
                ->addColumn('choice_5', function ($row) {
                    if ($row->choice_5 == 'NULL') {
                        return 'Essay(null value)';
                    }
                    return $row->choice_5;
                })
                ->addColumn('key', function ($row) {
                    if ($row->key == 'null') {
                        return 'Essay(null value)';
                    }
                    return $row->key;
                })
                ->addColumn('action', function ($row) use ($auth) {
                    $button = '';
                    if ($auth->can('soal-edit')) {
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="' . route('edit-detail-question', $row->id) . '"><i class="fas fa-edit text-secondary"></i></a>';
                    }
                    if ($auth->can('soal-delete')) {
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="javascrip:void(0)" onclick="deleteItem(this)" data-name="' . $row->id . '" data-id="' . $row->id . '"><i class="fas fa-trash-alt text-danger"></i></a>';
                    }
                    return $button;
                })
                ->rawColumns(['image', 'question', 'action'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('pages.question.list-detail-question', compact('page', 'detail_questions', 'id'));
    }

    public function addDetailQuestion($id)
    {

        $page = 'question';
        $question = Question::find($id);
        return view('pages.question.create-detail-question', compact('page', 'question'));
    }

    public function createDetailQuestion(Request $request, $id)
    {

        $question = Question::findOrFail($id);
        if ($question->type == 'essay') {
            $this->validate($request, [
                'data.*.question' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,svg|max:2048|nullable',
            ], [
                'data.*.question.required' => 'Soal tidak boleh kosong !',
                'data.*.image.image' => 'File tidak valid!',
                'data.*.image.mimes' => 'File tidak valid!',
                'data.*.image.max' => 'File tidak boleh lebih dari 2 MB!',
            ]);
        } elseif ($question->type == 'pilgan') {
            $this->validate($request, [
                'data.*.question' => 'required',
                'data.*.choice_1' => 'required',
                'data.*.choice_2' => 'required',
                'data.*.choice_3' => 'required',
                'data.*.choice_4' => 'required',
                'data.*.choice_5' => 'required',
                'data.*.key' => 'required',
                'data.*.image' => 'image|mimes:jpeg,png,jpg,svg|max:2048|nullable',
            ], [
                'data.*.question.required' => 'Soal tidak boleh kosong !',
                'data.*.choice_1.required' => 'Pilihan 1 tidak boleh kosong !',
                'data.*.choice_2.required' => 'Pilihan 2 tidak boleh kosong !',
                'data.*.choice_3.required' => 'Pilihan 3 tidak boleh kosong !',
                'data.*.choice_4.required' => 'Pilihan 4 tidak boleh kosong !',
                'data.*.choice_5.required' => 'Pilihan 5 tidak boleh kosong !',
                'data.*.key.required' => 'Kunci Jawaban tidak boleh kosong !',
                'data.*.image.image' => 'File tidak valid!',
                'data.*.image.mimes' => 'File tidak valid!',
                'data.*.image.max' => 'File tidak boleh lebih dari 2 MB!',
            ]);
        }

        $data = $request->all();

        DB::beginTransaction();
        try {
            foreach ($data['data'] as $value) {
                $value['question_id'] = $id;
                if (count($value) == 9) {
                    $file = $value['image'];
                    $file_name = time() . str_replace(" ", "", $file->getClientOriginalName());
                    $imageFile = Image::make($file->getRealPath());
                    $imageFile->resize(1200, 1200);
                    $file->storeAs('public/images/' . $id . $request->type . '', $file_name);
                    $value['image'] = $file_name;
                }

                $detail_questions = DetailQuestion::create($value);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Detail Question create successfully !'
            ]);
        } catch (Exception $e) {

            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Detail Question create failed !'
            ]);
        }

    }

    public function editDetailQuestion($id)
    {

        $page = 'question';
        $question = DetailQuestion::find($id);
        $type = Question::findOrFail($question->question_id);
        $display = 'block';
        if ($type->type == 'essay') {
            $display = 'none';
        }
        return view('pages.question.edit-detail-question', compact('page', 'question', 'display'));
    }

    public function updateDetailQuestion(Request $request, $id)
    {

        $detail_questions = DetailQuestion::find($id);
        $question = Question::find($detail_questions->question_id);

        if ($question->type == 'essay') {
            $this->validate($request, [
                'question' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,svg|max:2048|nullable',
            ], [
                'question.required' => 'Soal tidak boleh kosong !',
                'image.image' => 'File tidak valid!',
                'image.mimes' => 'File tidak valid!',
                'image.max' => 'File tidak boleh lebih dari 2 MB!',
            ]);
        } elseif ($question->type == 'pilgan') {
            $this->validate($request, [
                'question' => 'required',
                'choice_1' => 'required',
                'choice_2' => 'required',
                'choice_3' => 'required',
                'choice_4' => 'required',
                'choice_5' => 'required',
                'key' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,svg|max:2048|nullable',
            ], [
                'question.required' => 'Soal tidak boleh kosong !',
                'choice_1.required' => 'Pilihan 1 tidak boleh kosong !',
                'choice_2.required' => 'Pilihan 2 tidak boleh kosong !',
                'choice_3.required' => 'Pilihan 3 tidak boleh kosong !',
                'choice_4.required' => 'Pilihan 4 tidak boleh kosong !',
                'choice_5.required' => 'Pilihan 5 tidak boleh kosong !',
                'key.required' => 'Kunci Jawaban tidak boleh kosong !',
                'image.image' => 'File tidak valid!',
                'image.mimes' => 'File tidak valid!',
                'image.max' => 'File tidak boleh lebih dari 2 MB!',
            ]);
        }
        if ($request->file('image')) {
            $file = $request->file('image');
            $file_name = time() . str_replace(" ", "", $file->getClientOriginalName());
            $imageFile = Image::make($file->getRealPath());
            $imageFile->resize(1200, 1200);
            $file->storeAs('public/images/' . $question->id . $question->type . '', $file_name);
            $data['image'] = $file_name;
        }

        $detail_questions->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Detail Question create successfully !'
        ]);
    }

    public function deleteDetailQuestion($id)
    {

        $detail_questions = DetailQuestion::findOrFail($id);
        $question = Question::find($detail_questions->question_id);

        if ($detail_questions) {
            $url = Storage::delete('public/images/' . $question->id . $question->type . '/' . $detail_questions->image);
            $detail_questions->delete();
            return response()->json([
                'success' => true,
                'message' => 'Detail Question delete successfully !'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Detail Question delete failed !'
            ]);
        }
    }

    public function destroy($id)
    {

        $question = Question::findOrFail($id);

        if ($question) {
            $file = Storage::allFiles('public/images/' . $id . $question->type . '/');
            Storage::delete($file);
            $question->delete();
            return response()->json([
                'success' => true,
                'message' => 'Question delete successfully !'
            ]);
        } else {

            return response()->json([
                'success' => false,
                'message' => 'Question delete failed !'
            ]);
        }
    }

    public function templateQuestion()
    {

        // $file = asset('template-excel/template-question.xlsx');
        $file = public_path('template-excel/template-question.xlsx');

        return Response::download($file);
    }
}