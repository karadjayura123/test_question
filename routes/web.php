<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\SubtaskController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});



Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [TaskController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/generate-token', [ProfileController::class, 'generateToken'])->name('profile.generateToken');


    Route::post('/tasks', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks/update', [TaskController::class, 'update'])->name('tasks.update');
    Route::post('/task/delete', [taskController::class, 'delete'])->name('task.delete');
    Route::post('/tasks/filter', [taskController::class, 'filterTasks'])->name('tasks.filter');


    Route::post('/tasks/send', [SubtaskController::class, 'send'])->name('tasks.send');
    Route::get('/task/{taskId}', [SubtaskController::class, 'show'])->name('task.show');
    Route::post('/subtask/create', [SubtaskController::class, 'create'])->name('subtask.create');
    Route::get('/tasks/send', [SubtaskController::class, 'send'])->name('tasks.send');
    Route::get('/getModal', [SubtaskController::class, 'getModal'])->name('getModal');
    Route::post('/subtask/delete', [SubtaskController::class, 'delete'])->name('subtask.delete');
    Route::post('/subtask/update', [SubtaskController::class, 'update'])->name('subtask.update');


});


require __DIR__.'/auth.php';
