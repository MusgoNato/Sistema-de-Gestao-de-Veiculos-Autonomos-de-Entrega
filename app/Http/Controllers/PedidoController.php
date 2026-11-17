<?php

// app/Http/Controllers/PedidoController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    // UC19 - Acompanhar pedido (Cliente)
    public function meusPedidos()
    {
        $pedidos = DB::select(
            'SELECT p.id, p.data, p.status, p.valor_total, r.nome AS restaurante
               FROM pedido p
               JOIN restaurante r ON r.id = p.fk_restaurante_id
              WHERE p.fk_usuario_id = ?
              ORDER BY p.data DESC, p.id DESC',
            [session('usuario_id')]
        );

        return view('meus', compact('pedidos'));
    }

    // UC15 - Listar pedidos (Administrador e Operador, somente leitura)
    public function todos()
    {
        $pedidos = DB::select(
            'SELECT p.id, p.data, p.status, p.valor_total, p.pagamento,
                    u.nome AS cliente, r.nome AS restaurante
               FROM pedido p
               JOIN usuario u    ON u.id = p.fk_usuario_id
               JOIN restaurante r ON r.id = p.fk_restaurante_id
              ORDER BY p.data DESC, p.id DESC'
        );

        return view('todos', compact('pedidos'));
    }
}