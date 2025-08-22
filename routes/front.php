<?php
use Illuminate\Support\Facades\Route;


Route::get('/', function () {return view('front.layouts.dashboard');});
Route::get('/contact', function () {return view('front.contact.contact');});
Route::get('/about', function () {return view('front.about.about');});


