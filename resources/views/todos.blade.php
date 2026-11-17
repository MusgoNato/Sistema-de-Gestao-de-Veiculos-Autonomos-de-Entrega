<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pedidos</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root{--bg:#0b0f14;--panel:#121821;--line:#1f2a36;--text:#e6edf3;--muted:#7d8b99;--accent:#c6ff3d;--accent-2:#2de2c4;--err:#ff5d5d;--warn:#ffb547}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Space Grotesk',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;padding:24px 32px}
        .mono{font-family:'JetBrains Mono',monospace}
        header{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:24px;flex-wrap:wrap}
        .uc{color:var(--muted);font-size:11px;font-family:'JetBrains Mono',monospace;margin-bottom:6px}
        h1{font-size:26px;letter-spacing:-.02em}
        p.sub{color:var(--muted);font-size:13px;margin-top:4px}
        .user{display:flex;align-items:center;gap:12px;font-size:13px}
        .user span{color:var(--muted)}
        .btn{background:none;color:var(--text);border:1px solid var(--line);border-radius:8px;padding:5px 9px;font:inherit;font-size:12px;cursor:pointer}
        .btn:hover{border-color:var(--accent-2)}
        a.voltar{color:var(--accent-2);font-size:13px;text-decoration:none}
        .card{background:var(--panel);border:1px solid var(--line);border-radius:14px;padding:18px;overflow-x:auto}
        table{width:100%;border-collapse:collapse;font-size:13px}
        th{text-align:left;color:var(--muted);font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.08em;padding:8px;border-bottom:1px solid var(--line)}
        td{padding:10px 8px;border-bottom:1px solid var(--line)}
        tr:last-child td{border-bottom:0}
        .tag{display:inline-block;padding:3px 9px;border-radius:99px;font-size:11px;font-family:'JetBrains Mono',monospace;border:1px solid}
        .ok{color:var(--accent);border-color:rgba(198,255,61,.35)}
        .info{color:var(--accent-2);border-color:rgba(45,226,196,.35)}
        .warn{color:var(--warn);border-color:rgba(255,181,71,.35)}
        .bad{color:var(--err);border-color:rgba(255,93,93,.35)}
        .vazio{color:var(--muted);text-align:center;padding:24px}
    </style>
</head>
<body>

<header>
    <div>
        <div class="uc">UC15 · GERENCIAR PEDIDOS</div>
        <h1>Todos os pedidos</h1>
        <p class="sub">Consulta de pedidos de todos os clientes</p>
    </div>

    <div class="user">
        <div>
            <b>{{ session('usuario_nome') }}</b><br>
            <span>{{ implode(', ', session('perfis', [])) }}</span>
        </div>
        <a class="voltar" href="/painel">Painel</a>
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="btn">Sair</button>
        </form>
    </div>
</header>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Pedido</th>
                <th>Data</th>
                <th>Cliente</th>
                <th>Restaurante</th>
                <th>Valor</th>
                <th>Pagamento</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pedidos as $p)
                @php
                    $classe = match ($p->status) {
                        'entregue'    => 'ok',
                        'cancelado'   => 'bad',
                        'em_transito' => 'info',
                        default       => 'warn',
                    };
                @endphp
                <tr>
                    <td class="mono">#{{ $p->id }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->data)->format('d/m/Y') }}</td>
                    <td>{{ $p->cliente }}</td>
                    <td>{{ $p->restaurante }}</td>
                    <td>R$ {{ number_format($p->valor_total, 2, ',', '.') }}</td>
                    <td>{{ str_replace('_', ' ', $p->pagamento) }}</td>
                    <td><span class="tag {{ $classe }}">{{ str_replace('_', ' ', $p->status) }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="vazio">Nenhum pedido cadastrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>