<?php

// app/Http/Controllers/ViagemController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ViagemController extends Controller
{
    // UC16 - Monitorar rota (Administrador e Operador)
    public function monitorar()
    {
        $viagens = DB::select(
            "SELECT v.id, v.data_saida, v.hora_saida, v.status,
                    ve.placa, ve.modelo,
                    COUNT(e.id) AS total_entregas,
                    COALESCE(SUM(CASE WHEN e.status = 'entregue' THEN 1 ELSE 0 END), 0) AS concluidas
               FROM viagem v
               JOIN veiculo ve ON ve.id = v.fk_veiculo_id
          LEFT JOIN entrega e  ON e.fk_viagem_id = v.id
              WHERE v.status = 'em_andamento'
              GROUP BY v.id, v.data_saida, v.hora_saida, v.status, ve.placa, ve.modelo
              ORDER BY v.hora_saida"
        );

        return view('monitor', compact('viagens'));
    }
}