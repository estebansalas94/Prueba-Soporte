<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Auth:Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas para las operaciones CRUD de las tareas (en API)

    Route::get('/tasks/index', [TaskController::class, 'index'])->name('task.index');
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::put('/tasks/{id}/complete', [TaskController::class, 'update']); 
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy']);
 // Ruta para eliminar una tarea (DELETE)

    // Ruta para la vista de tareas (en la web)
    Route::get('/tasks', function () {
        return view('tasks'); // Ruta para mostrar la vista de la lista de tareas
    })->name('tasks');

});

require __DIR__.'/auth.php';
