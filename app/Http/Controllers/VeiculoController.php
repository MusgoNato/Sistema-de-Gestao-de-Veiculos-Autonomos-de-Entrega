<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class VeiculoController extends Controller
{
    // Listagem da frota
    public function index()
    {
        $veiculos = DB::select(
            'SELECT v.id, v.placa, v.modelo, v.ano, v.status, COUNT(c.id) AS cofres
               FROM veiculo v
          LEFT JOIN cofre c ON c.fk_veiculo_id = v.id
              GROUP BY v.id, v.placa, v.modelo, v.ano, v.status
              ORDER BY v.id'
        );

        return view('index', compact('veiculos'));
    }

    // Detalhe de um veículo: cofres e viagens
    public function show(int $id)
    {
        $veiculo = DB::selectOne(
            'SELECT id, placa, modelo, ano, status FROM veiculo WHERE id = ?',
            [$id]
        );

        abort_unless($veiculo, 404);

        $cofres = DB::select(
            'SELECT id, codigo, status FROM cofre WHERE fk_veiculo_id = ? ORDER BY codigo',
            [$id]
        );

        $viagens = DB::select(
            'SELECT id, data_saida, hora_saida, hora_retorno, status
               FROM viagem
              WHERE fk_veiculo_id = ?
              ORDER BY data_saida DESC, hora_saida DESC',
            [$id]
        );

        return view('show', compact('veiculo', 'cofres', 'viagens'));
    }
}