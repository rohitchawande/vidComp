<?php

use App\Http\Controllers\videoTranscoderController;
use App\Models\Transcodes;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});

Route::post('encodeVideo', [videoTranscoderController::class, 'gateway']);

Route::get('fetch', function () {
    return Transcodes::get();
});
