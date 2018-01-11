<?php


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/threads','ThreadController@index');
Route::get('/threads/create','ThreadController@create');
Route::post('/threads','ThreadController@store');
Route::get('/threads/{channel}/{thread}', 'ThreadController@show');
Route::post('/threads/{channel}/{thread}/replies','ReplyController@store');
