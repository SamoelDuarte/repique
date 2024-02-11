<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\EventsController;
use App\Models\ChatBot;
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
Route::prefix('/admin')->controller(AdminController::class)->group(function () {
    Route::get('/login', 'login')->name('admin.login');
    Route::get('/sair', 'sair')->name('admin.sair');
    Route::get('/senha', 'password')->name('admin.password');
    Route::post('/attempt', 'attempt')->name('admin.attempt');
});

Route::prefix('/')->controller(AdminController::class)->group(function () {
    Route::get('/', 'login');
});

Route::prefix('/events')->controller(EventsController::class)->group(function () {
    Route::post('/', 'index')->name('admin.events.index');
    Route::get('/teste', 'teste');
});


Route::middleware('auth.admin')->group(function () {


Route::prefix('/admin')->controller(DeviceController::class)->group(function () {
    Route::post('/dashborad', 'index')->name('admin.dashboard');
});
    Route::prefix('/dispositivo')->controller(DeviceController::class)->group(function () {
        Route::get('/', 'index')->name('admin.device.index');
        Route::get('/novo', 'create')->name('admin.device.create');
        Route::get('/getDevices', 'getDevices');
        Route::post('/updateStatus', 'updateStatus');
        Route::post('/updateName', 'updateName');
    });

    Route::prefix('/chat-bot')->controller(ChatBotController::class)->group(function () {
        Route::get('/', 'index')->name('admin.chatbot.index');
    });
});
