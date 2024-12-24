<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CalculoController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('/')->controller(AdminController::class)->group(function () {
    Route::get('/', 'login')->name('admin.login');
    Route::get('/sair', 'sair')->name('admin.sair');
    Route::get('/senha', 'password')->name('admin.password');
    Route::post('/attempt', 'attempt')->name('admin.attempt');
});



Route::middleware('auth.admin')->group(function () {

    Route::prefix('/admin')->controller(AdminController::class)->group(function () {


        Route::prefix('/usuario')->controller(UserController::class)->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('usuario.index'); // Listar usuários
            Route::get('/getUser', [UserController::class, 'getUser'])->name('usuario.get'); // Listar usuários
            Route::post('/', [UserController::class, 'store']); // Adicionar usuário
            Route::get('/{id}', [UserController::class, 'show']); // Mostrar usuário específico
            Route::put('/{id}', [UserController::class, 'update']); // Editar usuário
            Route::get('/delete/{id}', [UserController::class, 'destroy']); // Excluir usuário
        });

        Route::prefix('/calculo')->controller(CalculoController::class)->group(function () {
            Route::get('/', [CalculoController::class, 'index'])->name('calculo.index'); // Listar usuários
            Route::get('/insert', [CalculoController::class, 'insert'])->name('calculo.insert');
            Route::post('/store', [CalculoController::class, 'store'])->name('calculo.store'); // Listar usuários
            Route::get('/{id}', [CalculoController::class, 'show'])->name('calculo.show');
            Route::delete('/{id}', [CalculoController::class, 'destroy'])->name('calculo.destroy');
        });

    });
});
