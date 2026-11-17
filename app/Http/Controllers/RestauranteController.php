<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class RestauranteController extends Controller
{
    public function index()
    {
        $restaurantes = DB::select(
            'SELECT id, nome, rua, bairro, numero, telefone, email, horario_funcionamento
               FROM restaurante
              ORDER BY id'
        );

        $alimentos = DB::select(
            'SELECT id, nome, preco, fk_restaurante_id
               FROM alimento
              ORDER BY fk_restaurante_id, nome'
        );

        // Agrupa o cardápio por restaurante: [restaurante_id => [alimentos...]]
        $cardapio = [];
        foreach ($alimentos as $a) {
            $cardapio[$a->fk_restaurante_id][] = $a;
        }

        return view('restaurantes.index', compact('restaurantes', 'cardapio'));
    }
}
