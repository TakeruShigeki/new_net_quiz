<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
require __DIR__.'/auth.php';
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('create_quiz', [QuizController::class, 'create'])
->name('createQuiz');
Route::get('mobile_quiz_index', [QuizController::class, 'mobileQuizIndex'])
->name('mobileQuizIndex');
Route::get('net_quiz_index', [QuizController::class, 'netQuizIndex'])
->name('netQuizIndex');
Route::post('store_quiz', [QuizController::class, 'store'])
->name('storeQuiz');
Route::get('mobile_quiz_show/{quiz}', [QuizController::class, 'mobileQuizShow'])
->name('mobileQuizShow');
Route::get('mobile_quiz_edit/{quiz}', [QuizController::class, 'mobileQuizEdit'])
->name('mobileQuizEdit');
Route::post('update_quiz/{quiz}', [QuizController::class, 'updateQuiz'])
->name('updateQuiz');
Route::post('check_answer/{quiz}', [QuizController::class, 'checkAnswer'])
->name('checkAnswer');
// ↓ajax
Route::get('ajax-Quiz-favorite-update/{quiz_id}/', [QuizController::class, 'ajaxQuizUpdate'])
->name('ajaxQuizUpdate');