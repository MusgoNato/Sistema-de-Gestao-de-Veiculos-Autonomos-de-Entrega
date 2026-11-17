<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //

    public function index(Request $request)
    {
        $data  = $request->input('data', date('Y-m-d'));
        $ontem = date('Y-m-d', strtotime($data . ' -1 day'));

        $kpis = DB::selectOne("
            SELECT
                (SELECT COUNT(*) FROM veiculo WHERE status = 'ativo')  AS veiculos_ativos,
                (SELECT COUNT(*) FROM veiculo)                         AS veiculos_total,
                (SELECT COUNT(*) FROM entrega WHERE data_saida = ?)    AS entregas_dia,
                (SELECT COUNT(*) FROM entrega WHERE data_saida = ?)    AS entregas_ontem,
                (SELECT COUNT(*) FROM pedido
                WHERE status IN ('aberto', 'em_preparo', 'em_transito')) AS pedidos_aberto,
                (SELECT COALESCE(SUM(valor_total), 0) FROM pedido
                WHERE data = ? AND status = 'entregue')              AS faturamento_dia,
                (SELECT COALESCE(SUM(valor_total), 0) FROM pedido
                WHERE data = ? AND status = 'entregue')              AS faturamento_ontem
        ", [$data, $ontem, $data, $ontem]);

        $kpis->var_entregas   = $this->variacao($kpis->entregas_dia, $kpis->entregas_ontem);
        $kpis->var_faturamento = $this->variacao($kpis->faturamento_dia, $kpis->faturamento_ontem);

        $ultimos = DB::select("
            SELECT p.id, p.status, r.nome AS restaurante
            FROM pedido p
            JOIN restaurante r ON r.id = p.fk_restaurante_id
            ORDER BY p.id DESC
            LIMIT 5
        ");

        $rotas = DB::select("
            SELECT v.id, v.hora_saida, ve.placa, ve.modelo,
                COUNT(e.id) AS total_entregas,
                COALESCE(SUM(CASE WHEN e.status = 'entregue' THEN 1 ELSE 0 END), 0) AS concluidas
            FROM viagem v
            JOIN veiculo ve ON ve.id = v.fk_veiculo_id
        LEFT JOIN entrega e  ON e.fk_viagem_id = v.id
            WHERE v.status = 'em_andamento'
            GROUP BY v.id, v.hora_saida, ve.placa, ve.modelo
            ORDER BY v.hora_saida
        ");

        return view('dashboard', compact('kpis', 'ultimos', 'rotas', 'data'));
    }

    /* Variação percentual; null quando não há base de comparação */
    private function variacao($atual, $anterior): ?float
    {
        if ($anterior == 0) {
            return null;
        }
        return round((($atual - $anterior) / $anterior) * 100, 1);
    }
}
