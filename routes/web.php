<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\ChatBotController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\Events;
use App\Http\Controllers\EventsController;
use App\Http\Controllers\WebhookController;
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

Route::prefix('/webhook')->controller(WebhookController::class)->group(function () {
    Route::get('/send', 'send');
});

Route::get('/app-ads.txt', function () {
    echo "google.com, pub-5022908837658641, DIRECT, f08c47fec0942fa0";
});

Route::get('/politica', function () {
  return view('politica');
});

Route::prefix('/')->controller(AdminController::class)->group(function () {
    Route::get('/', 'login');
});

Route::prefix('/events')->controller(Events::class)->group(function () {
    Route::post('/', 'index')->name('admin.events.index');
    Route::get('/teste', 'teste');
});


Route::prefix('/app')->controller(AppController::class)->group(function () {
    Route::post('/', 'index');
    Route::post('/retorna_ultimos_calculos', 'ultimosCalculos');
    Route::post('/retorna_colaboradores', 'getColaboradores');
    Route::post('/insere_calculo', 'insertCalculo');
    Route::post('/retorna_calculo_admin', 'getCalculos');
    Route::post('/retorna_ultimos_calculos_repique', 'ultimosCalculosRepique');
    Route::post('/retorna_calculo_onda', 'dadosOnda');
    Route::post('/retorna_calculo_resumo_data', 'resumoData');
});


Route::middleware('auth.admin')->group(function () {


Route::prefix('/admin')->controller(DeviceController::class)->group(function () {
    Route::post('/dashborad', 'index')->name('admin.dashboard');
});
    
    Route::prefix('/chat-bot')->controller(ChatBotController::class)->group(function () {
        Route::get('/', 'index')->name('admin.chatbot.index');
    });

    Route::prefix('/dispositivo')->controller(DeviceController::class)->group(function () {
        Route::get('/', 'index')->name('admin.device.index');
        Route::get('/novo', 'create')->name('admin.device.create');
        Route::post('/delete', 'delete')->name('admin.device.delete');
        Route::get('/getDevices', 'getDevices');
        Route::post('/updateStatus', 'updateStatus');
        Route::post('/updateName', 'updateName');
        Route::get('/getStatus', 'getStatus');
    });
});


