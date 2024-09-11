<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\StudentController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('jwt.verify')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);

    Route::group(['middleware' => ['admin']], function() {
        Route::prefix('teachers')->group(function () {
            Route::post('/add', [AdminController::class, 'addTeacher']);
            Route::put('/edit/{id}', [AdminController::class, 'editTeacher']);
            Route::delete('/delete/{id}', [AdminController::class, 'deleteTeacher']);
        });
    
        Route::prefix('students')->group(function () {
            Route::post('/add', [AdminController::class, 'addStudent']);
            Route::put('/edit/{id}', [AdminController::class, 'editStudent']);
            Route::delete('/delete/{id}', [AdminController::class, 'deleteStudent']);
        });
    });
    Route::prefix('teacher')->middleware(['auth:api', 'role:teacher'])->group(function () {
        Route::post('/assign-marks/{studentId}', [TeacherController::class, 'assignMarks']);
        Route::post('/assign-homework/{studentId}', [TeacherController::class, 'assignHomework']);
    });
    
    Route::prefix('student')->middleware(['auth:api', 'role:student'])->group(function () {
        Route::get('/homework', [StudentController::class, 'viewHomework']);
        Route::put('/update-homework/{homeworkId}', [StudentController::class, 'updateHomework']);
        Route::get('/performance', [StudentController::class, 'monitorPerformance']);
    });
});
