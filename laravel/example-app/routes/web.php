<?php

use App\Http\Middleware\RequestTrace;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => [RequestTrace::class]], function () {
    Route::get('/', function () {
        return view('welcome');
    });
    Route::get('/http', function () {
        \Log::info("Access to 'https://example.com'");
        \Http::get('https://example.com');

        return view('welcome');
    });
    Route::get('/db', function () {
        \Log::info("Run quey 'select now'");
        \DB::select('select now() as n');

        return view('welcome');
    });
});
