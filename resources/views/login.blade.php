<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login - Gestão de Veículos Autônomos de Entrega</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&amp;family=JetBrains+Mono:wght@400;600&amp;display=swap" rel="stylesheet">
<style>
:root{--bg:#0b0f14;--panel:#121821;--line:#1f2a36;--text:#e6edf3;--muted:#7d8b99;--accent:#c6ff3d;--accent-2:#2de2c4;--err:#ff5d5d}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Space Grotesk',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:grid;grid-template-columns:1.1fr 1fr}
.mono{font-family:'JetBrains Mono',monospace}
/* Lado visual */
.visual{position:relative;overflow:hidden;border-right:1px solid var(--line);padding:48px;display:flex;flex-direction:column;justify-content:space-between;
background:radial-gradient(circle at 30% 70%,rgba(45,226,196,.12),transparent 55%),var(--bg)}
.grid{position:absolute;inset:0;background-image:linear-gradient(var(--line) 1px,transparent 1px),linear-gradient(90deg,var(--line) 1px,transparent 1px);background-size:48px 48px;opacity:.5;mask-image:radial-gradient(circle at 50% 50%,#000 30%,transparent 80%)}
.brand{position:relative;display:flex;align-items:center;gap:12px;font-weight:700;font-size:20px}
.logo{width:36px;height:36px;border:2px solid var(--accent);border-radius:8px;display:grid;place-items:center}
.logo span{width:10px;height:10px;background:var(--accent);border-radius:50%;animation:pulse 2s infinite}
@keyframes pulse{50%{opacity:.3;transform:scale(.7)}}
svg.map{position:absolute;inset:0;width:100%;height:100%}
.route{fill:none;stroke:var(--accent-2);stroke-width:2;stroke-dasharray:6 8;animation:dash 20s linear infinite;opacity:.7}
@keyframes dash{to{stroke-dashoffset:-400}}
.node{fill:var(--accent)}
.headline{position:relative}
.headline h1{font-size:clamp(32px,4vw,52px);line-height:1.05;letter-spacing:-.02em;max-width:520px}
.headline h1 em{font-style:normal;color:var(--accent)}
.headline p{color:var(--muted);margin-top:16px;max-width:420px}
.stats{position:relative;display:flex;gap:32px;margin-top:32px}
.stat b{display:block;font-size:24px}.stat small{color:var(--muted);font-size:12px;text-transform:uppercase;letter-spacing:.1em}
.live{display:inline-flex;align-items:center;gap:8px;font-size:12px;color:var(--accent-2);border:1px solid var(--line);padding:6px 10px;border-radius:99px;background:rgba(18,24,33,.8)}
.live::before{content:"";width:8px;height:8px;border-radius:50%;background:var(--accent-2);animation:pulse 1.5s infinite}
/* Formulário */
.form-side{display:flex;align-items:center;justify-content:center;padding:48px 24px}
form{width:100%;max-width:400px}
.tag{font-size:12px;color:var(--accent);letter-spacing:.15em;text-transform:uppercase}
form h2{font-size:32px;margin:8px 0 6px}
form>p{color:var(--muted);margin-bottom:32px}
label{display:block;font-size:13px;color:var(--muted);margin-bottom:8px}
.field{margin-bottom:20px;position:relative}
input[type=email],input[type=password],input[type=text]{width:100%;padding:14px 16px;background:var(--panel);border:1px solid var(--line);border-radius:10px;color:var(--text);font:inherit;outline:none;transition:.2s}
input:focus{border-color:var(--accent);box-shadow:0 0 0 3px rgba(198,255,61,.15)}
.field.invalid input{border-color:var(--err)}
.error{color:var(--err);font-size:12px;margin-top:6px;display:none}
.field.invalid .error{display:block}
.toggle{position:absolute;right:12px;top:38px;background:none;border:0;color:var(--muted);cursor:pointer;font-size:12px}
.row{display:flex;justify-content:space-between;align-items:center;font-size:13px;margin-bottom:28px}
.row label{display:flex;gap:8px;align-items:center;margin:0;cursor:pointer}
input[type=checkbox]{accent-color:var(--accent)}
a{color:var(--accent-2);text-decoration:none}a:hover{text-decoration:underline}
.btn{width:100%;padding:15px;border:0;border-radius:10px;background:var(--accent);color:#0b0f14;font:700 15px 'Space Grotesk';cursor:pointer;transition:.2s;display:flex;justify-content:center;align-items:center;gap:10px}
.btn:hover{transform:translateY(-1px);box-shadow:0 8px 24px rgba(198,255,61,.25)}
.btn:disabled{opacity:.7;cursor:wait;transform:none}
.spin{width:16px;height:16px;border:2px solid #0b0f14;border-top-color:transparent;border-radius:50%;animation:r .7s linear infinite;display:none}
.loading .spin{display:block}@keyframes r{to{transform:rotate(360deg)}}
.divider{display:flex;align-items:center;gap:12px;color:var(--muted);font-size:12px;margin:24px 0}
.divider::before,.divider::after{content:"";flex:1;height:1px;background:var(--line)}
.btn-ghost{width:100%;padding:14px;border:1px solid var(--line);border-radius:10px;background:transparent;color:var(--text);font:500 14px 'Space Grotesk';cursor:pointer}
.btn-ghost:hover{border-color:var(--accent-2)}
.msg{margin-top:20px;padding:12px 14px;border-radius:10px;font-size:13px;display:none}
.msg.ok{display:block;background:rgba(45,226,196,.1);border:1px solid var(--accent-2);color:var(--accent-2)}
.msg.bad{display:block;background:rgba(255,93,93,.1);border:1px solid var(--err);color:var(--err)}
footer{margin-top:32px;font-size:12px;color:var(--muted);text-align:center}
@media(max-width:900px){body{grid-template-columns:1fr}.visual{display:none}}
</style>
</head>
<body>
<section class="visual" aria-hidden="true">
  <div class="grid"></div>
  <svg class="map" viewBox="0 0 600 800" preserveAspectRatio="xMidYMid slice">
    <path class="route" d="M60 700 C 160 600, 120 480, 260 440 S 420 300, 540 120"></path>
    <path class="route" d="M40 300 C 180 320, 300 260, 360 380 S 480 600, 580 640" style="stroke:var(--accent);opacity:.4"></path>
    <circle class="node" cx="260" cy="440" r="5"></circle><circle class="node" cx="540" cy="120" r="5"></circle><circle class="node" cx="360" cy="380" r="4"></circle>
  </svg>
  <div class="headline">
    <span class="live mono">142 VEÍCULOS EM ROTA</span>
    <h1 style="margin-top:20px">Controle total da sua frota <em>autônoma</em> de entregas.</h1>
    <p>Monitore rotas, baterias e entregas em tempo real a partir de um único painel.</p>
    <div class="stats mono">
      <div class="stat"><b>98,7%</b><small>Entregas no prazo</small></div>
      <div class="stat"><b>3.240</b><small>Pedidos hoje</small></div>
      <div class="stat"><b>0</b><small>Incidentes</small></div>
    </div>
  </div>
</section>

<main class="form-side">
   
  <form action="{{ route('login') }}" method="POST">
    @csrf
    <span class="tag mono">// Acesso ao sistema</span>
    <h2>Entrar</h2>
    <p>Use suas credenciais.</p>

    <div class="field" id="f-email">
      <label for="email">E-mail corporativo</label>
      <input type="email" name="email" id="email" placeholder="operador@empresa.com" autocomplete="username">
      <div class="error">Informe um e-mail válido.</div>
    </div>
    <div class="field" id="f-pass">
      <label for="pass">Senha</label>
      <input type="password" name="senha" id="pass" placeholder="••••••••" autocomplete="current-password">
      <div class="error">A senha deve ter pelo menos 6 caracteres.</div>
    </div>

    <button class="btn" id="submit" type="submit">
    <span class="spin"></span>
    <span id="btn-text">Acessar painel</span>
</button>
    <div class="msg" id="msg" role="status"></div>
    <footer class="mono">© 2026</footer>
  </form>
</main>

</body>