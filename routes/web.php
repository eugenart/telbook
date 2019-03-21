<?php

Route::get('/', function () {
    return view('welcome');
});

//Auth::routes();

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::post('register', 'Auth\RegisterController@register');//->middleware('auth');
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');//->middleware('auth');

Route::get('/admin/{path}', 'HomeController@index')->where('path', '([A-z\d-\/_.]+)?')->middleware('auth');

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('listgroup', 'GetGroupController');
Route::resource('liststaff', 'GetStaffController');

Route::get('{path}', 'MainController@index')->where('path', '([A-z\d-\/_.]+)?');


