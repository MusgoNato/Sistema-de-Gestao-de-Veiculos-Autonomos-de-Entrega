<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard | Gestão de Veículos Autônomos de Entrega</title>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&amp;family=JetBrains+Mono:wght@400;600&amp;display=swap" rel="stylesheet">
<style>
:root{--bg:#0b0f14;--panel:#121821;--panel2:#0f141b;--line:#1f2a36;--text:#e6edf3;--muted:#7d8b99;--accent:#c6ff3d;--accent-2:#2de2c4;--err:#ff5d5d;--warn:#ffb547}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Space Grotesk',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:grid;grid-template-columns:250px 1fr}
.mono{font-family:'JetBrains Mono',monospace}
aside{border-right:1px solid var(--line);padding:20px 14px;position:sticky;top:0;height:100vh;overflow:auto;background:var(--panel2)}
.brand{display:flex;align-items:center;gap:10px;font-weight:700;font-size:18px;margin-bottom:20px}
.logo{width:32px;height:32px;border:2px solid var(--accent);border-radius:8px;display:grid;place-items:center}
.logo span{width:9px;height:9px;background:var(--accent);border-radius:50%;animation:pulse 2s infinite}
@keyframes pulse{50%{opacity:.3;transform:scale(.7)}}
.role{display:flex;gap:4px;background:var(--panel);border:1px solid var(--line);border-radius:10px;padding:4px;margin-bottom:18px}
.role button{flex:1;background:none;border:0;color:var(--muted);font:inherit;font-size:12px;padding:7px 4px;border-radius:7px;cursor:pointer}
.role button.on{background:var(--accent);color:#0b0f14;font-weight:700}
nav small{display:block;color:var(--muted);font-size:10px;letter-spacing:.12em;text-transform:uppercase;margin:14px 8px 6px}
nav a{display:flex;justify-content:space-between;gap:8px;padding:8px 10px;border-radius:8px;color:var(--text);text-decoration:none;font-size:14px;cursor:pointer}
nav a:hover{background:var(--panel)}nav a.on{background:var(--panel);box-shadow:inset 2px 0 0 var(--accent)}
nav a i{font-style:normal;color:var(--muted);font-size:10px;font-family:'JetBrains Mono',monospace}
main{padding:24px 32px;min-width:0}
header{display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;gap:16px}
header h1{font-size:26px;letter-spacing:-.02em}header p{color:var(--muted);font-size:13px;margin-top:4px}
.user{display:flex;align-items:center;gap:10px;font-size:13px}
.av{width:36px;height:36px;border-radius:50%;background:var(--accent-2);color:#0b0f14;display:grid;place-items:center;font-weight:700}
.grid{display:grid;gap:16px}.g4{grid-template-columns:repeat(4,1fr)}.g2{grid-template-columns:2fr 1fr}.g2e{grid-template-columns:1fr 1fr}
.card{background:var(--panel);border:1px solid var(--line);border-radius:14px;padding:18px;min-width:0}
.card h3{font-size:14px;margin-bottom:14px;display:flex;justify-content:space-between;align-items:center}
.card h3 i{font-style:normal;color:var(--muted);font-size:11px;font-family:'JetBrains Mono',monospace}
.kpi b{font-size:28px;display:block}.kpi small{color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:.1em}
.kpi em{font-style:normal;font-size:12px;color:var(--accent)}
table{width:100%;border-collapse:collapse;font-size:13px}
th{text-align:left;color:var(--muted);font-weight:500;font-size:11px;text-transform:uppercase;letter-spacing:.08em;padding:8px;border-bottom:1px solid var(--line)}
td{padding:10px 8px;border-bottom:1px solid var(--line)}
.tag{display:inline-block;padding:3px 9px;border-radius:99px;font-size:11px;font-family:'JetBrains Mono',monospace;border:1px solid}
.ok{color:var(--accent);border-color:rgba(198,255,61,.35)}.info{color:var(--accent-2);border-color:rgba(45,226,196,.35)}
.warn{color:var(--warn);border-color:rgba(255,181,71,.35)}.bad{color:var(--err);border-color:rgba(255,93,93,.35)}
.btn{background:var(--accent);color:#0b0f14;border:0;border-radius:8px;padding:9px 14px;font:inherit;font-weight:700;font-size:13px;cursor:pointer}
.btn.ghost{background:none;color:var(--text);border:1px solid var(--line);font-weight:500}
.btn.sm{padding:5px 9px;font-size:12px}.btn.danger{background:none;border:1px solid rgba(255,93,93,.4);color:var(--err)}
form.f{display:grid;grid-template-columns:1fr 1fr;gap:12px}
label{font-size:12px;color:var(--muted);display:grid;gap:6px}
input,select{background:var(--bg);border:1px solid var(--line);color:var(--text);border-radius:8px;padding:9px 10px;font:inherit;font-size:13px}
input:focus,select:focus{outline:none;border-color:var(--accent-2)}
.full{grid-column:1/-1}.row{display:flex;gap:8px;align-items:center;flex-wrap:wrap}
.map{position:relative;height:320px;border-radius:10px;overflow:hidden;background:var(--bg);border:1px solid var(--line)}
.map:before{content:"";position:absolute;inset:0;background-image:linear-gradient(var(--line) 1px,transparent 1px),linear-gradient(90deg,var(--line) 1px,transparent 1px);background-size:32px 32px;opacity:.6}
.map svg{position:absolute;inset:0;width:100%;height:100%}
.route{fill:none;stroke:var(--accent-2);stroke-width:2;stroke-dasharray:6 7;animation:dash 18s linear infinite}
@keyframes dash{to{stroke-dashoffset:-400}}
.bars{display:flex;align-items:flex-end;gap:8px;height:160px}
.bars div{flex:1;background:linear-gradient(var(--accent-2),rgba(45,226,196,.15));border-radius:6px 6px 0 0;position:relative}
.bars div span{position:absolute;bottom:-18px;left:0;right:0;text-align:center;font-size:10px;color:var(--muted)}
.steps{display:flex;gap:6px;margin:12px 0}.steps div{flex:1;height:6px;border-radius:3px;background:var(--line)}.steps div.on{background:var(--accent)}
.vault{display:grid;grid-template-columns:repeat(3,1fr);gap:10px}
.vault button{aspect-ratio:1;border-radius:10px;border:1px solid var(--line);background:var(--bg);color:var(--text);font:inherit;cursor:pointer;display:grid;place-items:center;font-size:12px}
.vault button.mine{border-color:var(--accent);color:var(--accent)}.vault button.open{background:rgba(198,255,61,.12)}
.vault button:disabled{opacity:.35;cursor:not-allowed}
.menu{display:grid;gap:8px}.menu div{display:flex;justify-content:space-between;align-items:center;padding:10px;border:1px solid var(--line);border-radius:8px}
.toast{position:fixed;bottom:24px;right:24px;background:var(--panel);border:1px solid var(--accent);padding:12px 16px;border-radius:10px;font-size:13px;transform:translateY(120px);transition:.3s;z-index:9}
.toast.show{transform:none}
.uc{color:var(--muted);font-size:11px;font-family:'JetBrains Mono',monospace;margin-bottom:6px}
.hidden{display:none}
@media(max-width:1000px){body{grid-template-columns:1fr}aside{position:static;height:auto}.g4{grid-template-columns:1fr 1fr}.g2,.g2e{grid-template-columns:1fr}form.f{grid-template-columns:1fr}}
</style>
</head>
<body>
<aside>
    <div class="brand">
        <div class="logo"><span></span></div> Frota Autônoma
    </div>

    <nav>
        <small>Geral</small>
        <a href="/painel">Visão geral</a>

        @if (array_intersect(['Administrador', 'Operador'], session('perfis', [])))
            <a href="/monitor">Monitorar rotas <i>UC16</i></a>
            <a href="/pedidos">Pedidos <i>UC15</i></a>
            <a href="/restaurantes">Restaurantes<i>UC09</i></a>
        @endif

        @if (in_array('Cliente', session('perfis', [])))
            <a href="/meus-pedidos">Meus pedidos <i>UC19</i></a>
        @endif

        @if (in_array('Administrador', session('perfis', [])))
            <small>Administração</small>
            <a href="/frota">Frota <i>UC06</i></a>
        @endif
    </nav>
</aside>
<main>
  <header>
    <div>
        <div class="uc">PAINEL</div>
        <h1>Visão geral</h1>
        <p>Indicadores da operação</p>
    </div>
    <div class="user">
        <div>
            <b>{{ session('usuario_nome') }}</b><br>
            <span style="color:var(--muted)">{{ implode(', ', session('perfis', [])) }}</span>
        </div>
        <form method="POST" action="/logout">
            @csrf
            <button type="submit" class="btn ghost sm">Sair</button>
        </form>
    </div>
</header>
  <section id="view">
  <div class="grid g4">
    <div class="card kpi">
        <small>Veículos ativos</small>
        <b>{{ $kpis->veiculos_ativos }}/{{ $kpis->veiculos_total }}</b>
    </div>

    <div class="card kpi">
        <small>Entregas do dia</small>
        <b>{{ $kpis->entregas_dia }}</b>
        <em>
            @if ($kpis->var_entregas !== null)
                {{ $kpis->var_entregas >= 0 ? '+' : '' }}{{ $kpis->var_entregas }}% vs dia anterior
            @else
                sem base de comparação
            @endif
        </em>
    </div>

    <div class="card kpi">
        <small>Pedidos em aberto</small>
        <b>{{ $kpis->pedidos_aberto }}</b>
    </div>

    <div class="card kpi">
        <small>Faturamento do dia</small>
        <b>R$ {{ number_format($kpis->faturamento_dia, 2, ',', '.') }}</b>
        <em>
            @if ($kpis->var_faturamento !== null)
                {{ $kpis->var_faturamento >= 0 ? '+' : '' }}{{ $kpis->var_faturamento }}% vs dia anterior
            @else
                sem base de comparação
            @endif
        </em>
    </div>
</div>

<div class="grid g2" style="margin-top:16px">

    <div class="card">
        <h3>Rotas ativas <i>UC16</i></h3>
        @forelse ($rotas as $r)
            <div class="row" style="justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line)">
                <span class="mono">{{ $r->placa }}</span>
                <span style="font-size:12px;color:var(--muted)">{{ $r->modelo }} · saiu às {{ substr($r->hora_saida, 0, 5) }}</span>
                <span class="tag info">{{ $r->concluidas }}/{{ $r->total_entregas }} entregas</span>
            </div>
        @empty
            <p style="color:var(--muted);font-size:13px">Nenhuma viagem em andamento.</p>
        @endforelse
    </div>

    <div class="card">
        <h3>Últimos pedidos <i>UC15</i></h3>
        @forelse ($ultimos as $p)
            @php
                $classe = match ($p->status) {
                    'entregue'    => 'ok',
                    'cancelado'   => 'bad',
                    'em_transito' => 'info',
                    default       => 'warn',
                };
            @endphp
            <div class="row" style="justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--line)">
                <span class="mono">#{{ $p->id }}</span>
                <span style="font-size:12px;color:var(--muted)">{{ $p->restaurante }}</span>
                <span class="tag {{ $classe }}">{{ str_replace('_', ' ', $p->status) }}</span>
            </div>
        @empty
            <p style="color:var(--muted);font-size:13px">Nenhum pedido cadastrado.</p>
        @endforelse
    </div>

</div>
</main>
<div class="toast" id="toast"></div>
</body>