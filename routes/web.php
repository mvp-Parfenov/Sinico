<?php

Route::get('/', 'HomeController@index')->name('home');

Route::get('/register', 'Auth\RegisterController@register')->name('cabinet');

Route::get('/verify/{token}', 'Auth\RegisterController@verify')->name('register.verify');