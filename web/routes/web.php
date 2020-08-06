<?php

use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});
// アロー使えない
Route::get('/{any?}', function() {
    return view('index');
})->where('any', '.+');
