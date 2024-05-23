<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExamSession;
use Auth;
use DataTables;

class ExamSessionController extends Controller
{

    public function index(Request $request)
    {

        $page = 'sesi';
        $mapel = ExamSession::all();
        $auth = Auth::user();

        if ($request->ajax()) {
            return DataTables::of($mapel)
                ->addColumn('name', function ($row) {
                    return $row->name;
                })
                ->addColumn('time_start', function ($row) {
                    return $row->time_start;
                })
                ->addColumn('time_end', function ($row) {
                    return $row->time_end;
                })
                ->addColumn('action', function ($data) use ($auth) {
                    $button = '';
                    if ($auth->can('sesi-edit')) {
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="' . route('exam-session.edit', $data->id) . '"><i class="fas fa-edit text-secondary"></i></a>';
                    }
                    if ($auth->can('sesi-delete')) {
                        $button .= '&nbsp;&nbsp;';
                        $button .= '<a href="javascrip:void(0)" onclick="deleteItem(this)" data-name="' . $data->name . '" data-id="' . $data->id . '"><i class="fas fa-trash-alt text-danger"></i></a>';
                    }

                    return $button;
                })
                ->rawColumns(['action', 'role'])
                ->addIndexColumn()
                ->make(true);
        }

        return view('pages.exam-session.index', compact('page'));
    }

    public function create()
    {

        $page = 'sesi';
        return view('pages.exam-session.create', compact('page'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:exam_sessions',
            'time_start' => 'required',
            'time_end' => 'required',
        ], [
            'name.required' => 'Nama harus terisi !',
            'name.unique' => 'Nama sudah terdaftar !',
            'time_start.required' => 'Waktu start tidak boleh kosong!',
            'time_end.required' => 'Waktu Berakhir tidak boleh kosong!',
        ]);

        $data = $request->all();
        $sesi = ExamSession::create($data);

        if ($sesi) {

            return response()->json([
                'success' => true,
                'message' => 'Sesi Ujian created successfully !',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sesi Ujian created failed !',
            ]);
        }
    }

    public function edit($id)
    {

        $page = 'sesi';
        $exam_session = ExamSession::findOrFail($id);
        return view('pages.exam-session.edit', compact('page', 'exam_session'));
    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required|unique:exam_sessions,name,' . $id,
            'time_start' => 'required',
            'time_end' => 'required',
        ], [
            'name.required' => 'Nama harus terisi !',
            'name.unique' => 'Nama sudah terdaftar !',
            'time_start.required' => 'Waktu start tidak boleh kosong!',
            'time_end.required' => 'Waktu Berakhir tidak boleh kosong!',
        ]);

        $data = $request->all();
        $sesi = ExamSession::findOrFail($id);
        $sesi->update($data);

        if ($sesi) {

            return response()->json([
                'success' => true,
                'message' => 'Sesi Ujian update successfully !',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sesi Ujian update failed !',
            ]);
        }
    }

    public function destroy($id)
    {

        $sesi = ExamSession::findOrFail($id);
        $sesi->delete();

        if ($sesi) {

            return response()->json([
                'success' => true,
                'message' => 'Sesi Ujian delete successfully !',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Sesi Ujian delete failed !',
            ]);
        }

    }
}