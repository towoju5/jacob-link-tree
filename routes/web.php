<?php

use Controller\Links;
use Controller\AuthController;
use Controller\HomeController;
use Pecee\SimpleRouter\SimpleRouter as Route;

Route::get('/', [HomeController::class, 'index']);


Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::post('/link', [Links::class, 'store']);
Route::get('/links', [Links::class, 'get']);
Route::put('/link', [Links::class, 'update']);
Route::get('/link/{linkId}', [Links::class, 'show']);
Route::delete('/link/{linkId}', [Links::class, 'destroy']);