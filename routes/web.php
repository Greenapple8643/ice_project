<?php

use Illuminate\Support\Facades\Route;
use Filament\Http\Controllers\PagesController;
use Filament\Http\Controllers\ResourceController;

Route::get('/', function () {
    return view('welcome');

});


