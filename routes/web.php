<?php

use App\Http\Controllers\BatteryController;
use App\Http\Controllers\InssController;
use App\Http\Controllers\IrpfController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Resources\BankResource;
use App\Http\Resources\UserResource;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    if (Auth::check())
        return redirect('/app');
    else
        return redirect()->route('login');
})->name('index');

Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::post("/login", [LoginController::class, 'confirmWeb'])->name('loginPost');

Route::post("/logout", [LoginController::class, 'invalidateWeb'])->name('logout');

Route::get('/api/csrf', function(){ return csrf_token(); });

/**
 * API routes (authenticated)
 */
Route::middleware(['authorizeActiveUser'])->prefix('api')->group(function(){
    // Dados de referência
    Route::get('/whoami', fn (Request $request) => new UserResource($request->user()));
    
    // Bancos
    Route::get('/banks/dd', fn () => BankResource::collection(Bank::onlyActive()->get()));

    // Baterias
    Route::get('/batteries', [BatteryController::class, 'index']);
    Route::get('/batteries/dd', [BatteryController::class, 'dropdownIndex']);
    Route::get('/battery/{battery}', [BatteryController::class, 'show']);
    Route::post('/batteries', [BatteryController::class, 'store']);

    // Cargos
    Route::get('/jobs', [JobController::class, 'index']);
    Route::get('/jobs/dd', [JobController::class, 'dropdownIndex']);
    Route::get('/job/{job}', [JobController::class, 'show']);
    Route::post('/job/new', [JobController::class, 'store']);
    Route::post('/job/{job}', [JobController::class, 'update']);

    // INSS
    Route::get('/inss/unique', [InssController::class, 'index']);
    Route::post('/inss/unique', [InssController::class, 'store']);

    // Projeto
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::get('/projects/dd', [ProjectController::class, 'dropdownIndex']);
    Route::get('/project/{project}', [ProjectController::class, 'show']);
    Route::post('/project/new', [ProjectController::class, 'store']);
    Route::post('/project/{project}', [ProjectController::class, 'update']);
    Route::delete('/project/{project}', [ProjectController::class, 'deactivate']);

    // Pessoa Física
    Route::get('/person', [PersonController::class, 'index']);
    Route::get('/person/{person}', [PersonController::class, 'show']);
    Route::post('/person/new', [PersonController::class, 'store']);
    Route::post('/person/{person}', [PersonController::class, 'update']);
    Route::delete('/person/{person}', [PersonController::class, 'deactivate']);

    Route::get('/irpf', [IrpfController::class, 'index']);
    Route::get('/irpf/{irpf}', [IrpfController::class, 'show']);
    Route::post('/irpf', [IrpfController::class, 'store']);
    Route::post('/irpf/{irpf}', [IrpfController::class, 'update']);

    /**
     * API routes exclusive for admins
     */
    Route::middleware('authorizeOnlyAdmin')->group(function(){
        
        // Usuários
        Route::get('/users', [UserController::class, 'index']);
        Route::get('/user/{user}', [UserController::class, 'show']);
        Route::post('/user/new', [UserController::class, 'store']);
        Route::post('/user/{user}', [UserController::class, 'update']);

    });
});