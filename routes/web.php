<?php

use App\Http\Livewire\FindSeats;
use App\Http\Livewire\GenerateVenue;
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
    return redirect()->route('docs');
});

Route::get('/seats', FindSeats::class)->name('find-a-seat');

Route::get('/venue', GenerateVenue::class)->name('generate-venue');

Route::get('/docs', function() {
    return view('docs');
})->name('docs');
