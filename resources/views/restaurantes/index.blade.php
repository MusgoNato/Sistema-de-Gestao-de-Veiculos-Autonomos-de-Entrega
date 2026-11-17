<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurantes</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root{--bg:#0b0f14;--panel:#121821;--line:#1f2a36;--text:#e6edf3;--muted:#7d8b99;--accent:#c6ff3d;--accent-2:#2de2c4;--err:#ff5d5d;--warn:#ffb547}
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:'Space Grotesk',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;padding:24px 32px}
        .mono{font-family:'JetBrains Mono',monospace}
        header{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:24px;flex-wrap:wrap}
        .uc{color:var(--muted);font-size:11px;font-family:'JetBrains Mono',monospace;margin-bottom:6px}
        h1{font-size:26px;letter-spacing:-.02em}
        h2{font-size:15px;margin-bottom:4px}
        p.sub{color:var(--muted);font-size:13px;margin-top:4px}
        .user{display:flex;align-items:center;gap:12px;font-size:13px}
        .user span{color:var(--muted)}
        .btn{background:none;color:var(--text);border:1px solid var(--line);border-radius:8px;padding:5px 9px;font:inherit;font-size:12px;cursor:pointer}
        .btn:hover{border-color:var(--accent-2)}
        a.voltar{color:var(--accent-2);font-size:13px;text-decoration:none}
        .grid{display:grid;grid-template-columns:1fr 1fr;gap:16px}
        .card{background:var(--panel);border:1px solid var(--line);border-radius:14px;padding:18px;overflow-x:auto}
        .endereco{color:var(--muted);font-size:12px;margin:8px 0 14px}
        .info-linha{display:flex;gap:16px;flex-wrap:wrap;font-size:12px;color:var(--muted);margin-bottom:14px}
        table{width:100%;border-collapse:collapse;font-size:13px}
        th{text-align:left;color:var(--muted);font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.08em;padding:8px;border-bottom:1px solid var(--line)}
        td{padding:10px 8px;border-bottom:1px solid var(--line)}
        tr:last-child td{border-bottom:0}
        .tag{display:inline-block;padding:3px 9px;border-radius:99px;font-size:11px;font-family:'JetBrains Mono',monospace;border:1px solid}
        .info{color:var(--accent-2);border-color:rgba(45,226,196,.35)}
        .vazio{color:var(--muted);text-align:center;padding:24px}
        @media(max-width:900px){.grid{grid-template-columns:1fr}}
    </style>
</head>
<body>

<header>
    <div>
        <div class="uc">UC09 · RESTAURANTES PARCEIROS</div>
        <h1>Restaurantes parceiros</h1>
        <p class="sub">Restaurantes disponíveis na plataforma e seus cardápios</p>
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

<div class="grid">
    @forelse ($restaurantes as $r)
        <div class="card">
            <h2>{{ $r->nome }}</h2>

            <div class="endereco">
                {{ $r->rua }}, {{ $r->numero }} · {{ $r->bairro }}
            </div>

            <div class="info-linha">
                <span>Telefone: <span class="mono">{{ $r->telefone }}</span></span>
                <span>E-mail: {{ $r->email }}</span>
                <span>Abre às: <span class="mono">{{ substr($r->horario_funcionamento, 0, 5) }}</span></span>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Alimento</th>
                        <th>Preço</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($cardapio[$r->id] ?? [] as $a)
                        <tr>
                            <td>{{ $a->nome }}</td>
                            <td class="mono">R$ {{ number_format($a->preco, 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="vazio">Cardápio sem itens cadastrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @empty
        <div class="card vazio" style="grid-column:1/-1">Nenhum restaurante cadastrado.</div>
    @endforelse
</div>

</body>
</html>