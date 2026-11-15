<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'index'])->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::get('/cadastro', [AuthController::class, 'indexcadastro'])->name('register');
Route::post('/cadastro', [AuthController::class, 'cadastrar'])->name('register.cadastrar');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::get('/painel', [DashboardController::class, 'index'])->name('dashboard');