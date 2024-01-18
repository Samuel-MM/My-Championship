<?php

use App\Http\Controllers\ChampionshipController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use APP\Http\Controllers\TesteController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::controller(ChampionshipController::class)->group(function () {
//     Route::get('/championship/history', 'show');
//     Route::post('/championship/history', 'store');
// });

Route::middleware('guest')->group(function () {
    Route::get('/championship/history', [ChampionshipController::class, 'showChampionshipHistory']);
    Route::post('/championship/new', [ChampionshipController::class, 'store']);
})->middleware(['auth', 'verified'])->name('dashboard');

// Route::post('championship/new', [ChampionshipController::class, 'store']);
// Route::get('championship/history', [ChampionshipController::class, 'index']);
