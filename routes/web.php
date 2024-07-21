<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    UserController,
    RoleController,
    ParticipantController,
    ClassRoomController,
    MajorController,
    MapelController,
    ExamSessionController,
    QuestionController,
    ExamController,
    TeacherController,
    ParticipantSessionController,
    StartExamController,
    AnswerController,
    ProfilController,
    NilaiController,
    CacheController
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes(['register' => false]);

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('participant', ParticipantController::class);
    Route::resource('teachers', TeacherController::class);
    Route::resource('class', ClassRoomController::class);
    Route::resource('majors', MajorController::class);
    Route::resource('mapels', MapelController::class);
    Route::resource('exam-session', ExamSessionController::class);

    Route::get('add-detail-question/{id}', [QuestionController::class, 'addDetailQuestion'])->name('add-detail-question');
    Route::delete('delete-detail-question/{id}', [QuestionController::class, 'deleteDetailQuestion'])->name('delete-detail-question');
    Route::get('list-detail-question/{id}', [QuestionController::class, 'listDetailQuestion'])->name('list-detail-question');
    Route::get('view-detail-question/{id}', [QuestionController::class, 'viewDetailQuestion'])->name('view-detail-question');
    Route::post('create-detail-question/{id}', [QuestionController::class, 'createDetailQuestion'])->name('create-detail-question');
    Route::get('edit-detail-question/{id}', [QuestionController::class, 'editDetailQuestion'])->name('edit-detail-question');
    Route::put('update-detail-question/{id}', [QuestionController::class, 'updateDetailQuestion'])->name('update-detail-question');
    Route::get('template-question-excel', [QuestionController::class, 'templateQuestion'])->name('template-question-excel');
    Route::resource('question', QuestionController::class);

    Route::get('/get-question/{type}', [ExamController::class, 'getQuestion']);
    Route::resource('exam', ExamController::class);

    Route::get('/get-exam', [ParticipantSessionController::class, 'getExam'])->name('get-exam');
    Route::get('/get-participant', [ParticipantSessionController::class, 'getParticipant'])->name('get-participant');
    Route::get('/get-participant/{no_peserta}', [ParticipantSessionController::class, 'getParticipantbyNoPeserta'])->name('get-participant-by-no-peserta');
    Route::post('get-participant-by-select', [ParticipantSessionController::class, 'getParticipantBySelect'])->name('get-participant-by-select');
    // Route::resource('participant-session', ParticipantSessionController::class);
    Route::get('participant-session', [ParticipantSessionController::class, 'index'])->name('participant-session.index');
    Route::get('participant-session/create', [ParticipantSessionController::class, 'create'])->name('participant-session.create');
    Route::post('participant-session', [ParticipantSessionController::class, 'store'])->name('participant-session.store');
    Route::get('participant-session/{participant_session}/edit', [ParticipantSessionController::class, 'edit'])->name('participant-session.edit');
    Route::put('participant-session/{participant_session}', [ParticipantSessionController::class, 'update'])->name('participant-session.update');
    Route::delete('participant-session/{participant_session}', [ParticipantSessionController::class, 'destroy'])->name('participant-session.destroy');

    Route::get('/confirm', [StartExamController::class, 'confirm'])->name('confirm');
    Route::get('/start-exam', [StartExamController::class, 'showQuestion'])->name('start-exam');
    Route::post('/finish-exam', [StartExamController::class, 'finishExam'])->name('finish-exam');

    Route::get('list-ujian/{id}', [AnswerController::class, 'listUjian'])->name('answer.listujian');
    Route::get('koreksi-soal/{id_soal}/{id_participan_session}', [AnswerController::class, 'koreksiSoal'])->name('answer.koreksisoal');
    Route::post('koreksi', [AnswerController::class, 'koreksi'])->name('koreksi');
    Route::resource('answer', AnswerController::class);

    Route::get('/profile', [ProfilController::class, 'index'])->name('profil');
    Route::post('/profil/update', [ProfilController::class, 'updateProfile'])->name('profil.update');

    Route::resource('nilai', NilaiController::class);

    Route::resource('clear-cache', CacheController::class);
});