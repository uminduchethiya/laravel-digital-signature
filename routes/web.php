<?php

use App\Http\Controllers\SignatureController;
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
Route::get('/signature', [SignatureController::class, 'index'])->name('signature.index');
Route::post('/signature/store', [SignatureController::class, 'store'])->name('signature.store');