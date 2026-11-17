<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\RestauranteController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\ViagemController;
use Illuminate\Support\Facades\Route;


/* Acesso */
Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/* Rotas que exigem sessão ativa */
Route::middleware('autenticado')->group(function () {

    // Painel (qualquer perfil logado)
    Route::get('/painel', [DashboardController::class, 'index'])->name('dashboard');

    // UC19 - Acompanhar pedido (Cliente)
    Route::middleware('perfil:Cliente')->group(function () {
        Route::get('/meus-pedidos', [PedidoController::class, 'meusPedidos'])->name('meus.pedidos');
    });

    // UC15 - Listar pedidos e UC16 - Monitorar rota (Administrador e Operador)
    Route::middleware('perfil:Administrador,Operador')->group(function () {
        Route::get('/pedidos', [PedidoController::class, 'todos']);
        Route::get('/monitor', [ViagemController::class, 'monitorar']);
    });


    Route::middleware('perfil:Administrador')->group(function () {
        Route::get('/frota', [VeiculoController::class, 'index']);
        Route::get('/frota/{id}', [VeiculoController::class, 'show'])->whereNumber('id');
    });

    Route::middleware('perfil:Administrador,Cliente')->group(function () {
        Route::get('/restaurantes', [RestauranteController::class, 'index']);
    });
});

