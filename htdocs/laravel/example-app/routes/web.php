<?php
use App\Http\Controllers\Ctrl1;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::any('/', [Ctrl1::class, 'action1']);
