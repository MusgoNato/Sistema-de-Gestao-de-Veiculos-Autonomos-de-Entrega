<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Cadastro | Gestão de Veículos Autônomos de Entrega</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&amp;family=JetBrains+Mono:wght@400;600&amp;display=swap" rel="stylesheet">
<style>
:root{--bg:#0b0f14;--panel:#121821;--line:#1f2a36;--text:#e6edf3;--muted:#7d8b99;--accent:#c6ff3d;--accent-2:#2de2c4;--err:#ff5d5d}
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Space Grotesk',sans-serif;background:var(--bg);color:var(--text);min-height:100vh;display:grid;grid-template-columns:1fr 1.1fr}
.mono{font-family:'JetBrains Mono',monospace}
.visual{position:relative;overflow:hidden;border-right:1px solid var(--line);padding:48px;display:flex;flex-direction:column;justify-content:space-between;background:radial-gradient(circle at 70% 30%,rgba(198,255,61,.08),transparent 55%),var(--bg)}
.grid{position:absolute;inset:0;background-image:linear-gradient(var(--line) 1px,transparent 1px),linear-gradient(90deg,var(--line) 1px,transparent 1px);background-size:48px 48px;opacity:.5;mask-image:radial-gradient(circle,#000 30%,transparent 80%)}
.brand{position:relative;display:flex;align-items:center;gap:12px;font-weight:700;font-size:20px}
.logo{width:36px;height:36px;border:2px solid var(--accent);border-radius:8px;display:grid;place-items:center}
.logo span{width:10px;height:10px;background:var(--accent);border-radius:50%;animation:pulse 2s infinite}
@keyframes pulse{50%{opacity:.3;transform:scale(.7)}}
.headline{position:relative}
.headline h1{font-size:clamp(30px,3.6vw,48px);line-height:1.05;letter-spacing:-.02em;max-width:480px}
.headline h1 em{font-style:normal;color:var(--accent)}
.headline p{color:var(--muted);margin-top:16px;max-width:420px}
.steps{position:relative;list-style:none;margin-top:32px;display:grid;gap:14px}
.steps li{display:flex;gap:14px;align-items:flex-start;color:var(--muted);font-size:14px}
.steps b{color:var(--text);display:block}
.steps .n{flex:none;width:28px;height:28px;border:1px solid var(--line);border-radius:6px;display:grid;place-items:center;font-size:12px;color:var(--accent-2);background:var(--panel)}
.foot{position:relative;font-size:12px;color:var(--muted)}
.form-side{display:flex;align-items:center;justify-content:center;padding:48px 24px}
form{width:100%;max-width:480px}
.tag{font-size:12px;color:var(--accent);letter-spacing:.15em;text-transform:uppercase}
form h2{font-size:32px;margin:8px 0 6px}
form>p{color:var(--muted);margin-bottom:28px}
.row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.field{margin-bottom:16px}
label{display:block;font-size:13px;color:var(--muted);margin-bottom:6px}
input,select{width:100%;padding:12px 14px;background:var(--panel);border:1px solid var(--line);border-radius:8px;color:var(--text);font:inherit;outline:none;transition:border-color .2s}
input:focus,select:focus{border-color:var(--accent-2)}
.field.invalid input,.field.invalid select{border-color:var(--err)}
.msg{font-size:12px;color:var(--err);margin-top:4px;min-height:14px}
.meter{display:flex;gap:4px;margin-top:8px}.meter i{flex:1;height:4px;background:var(--line);border-radius:2px;transition:background .2s}
.check{display:flex;gap:10px;align-items:flex-start;font-size:13px;color:var(--muted);margin:6px 0 18px}
.check input{width:auto;margin-top:3px;accent-color:var(--accent)}
a{color:var(--accent-2);text-decoration:none}a:hover{text-decoration:underline}
button{width:100%;padding:14px;background:var(--accent);color:#0b0f14;border:0;border-radius:8px;font:inherit;font-weight:700;cursor:pointer;transition:transform .1s,opacity .2s}
button:hover{opacity:.9}button:active{transform:scale(.99)}button:disabled{opacity:.5;cursor:wait}
.alt{text-align:center;color:var(--muted);font-size:14px;margin-top:20px}
.toast{margin-top:16px;padding:12px 14px;border-radius:8px;font-size:14px;display:none}
.toast.ok{display:block;background:rgba(198,255,61,.1);border:1px solid var(--accent);color:var(--accent)}
@media(max-width:900px){body{grid-template-columns:1fr}.visual{display:none}.row{grid-template-columns:1fr}}
</style>
</head>
<body>
<aside class="visual">
  <div class="grid"></div>
  <div class="headline">
    <h1>Entre para a <em>central de comando</em> da sua frota.</h1>
    <p>Crie sua conta e comece a monitorar veículos autônomos, rotas e entregas em tempo real.</p>
    <ul class="steps">
      <li><span class="n mono">01</span><div><b>Crie sua conta</b>Dados básicos e da sua empresa.</div></li>
      <li><span class="n mono">02</span><div><b>Aprovação do acesso</b>Um administrador valida seu perfil.</div></li>
      <li><span class="n mono">03</span><div><b>Comande a frota</b>Acompanhe rotas, baterias e entregas.</div></li>
    </ul>
  </div>
  <div class="foot mono">© 2026</div>
</aside>
<main class="form-side">
  <form action="{{ route('register') }}" method="POST">
    @csrf
    <span class="tag mono">// novo operador</span>
    <h2>Criar conta</h2>
    <p>Preencha os dados abaixo para solicitar acesso.</p>
    <div class="row">
      <div class="field"><label for="nome">Nome completo</label><input id="nome" autocomplete="name" placeholder="Ana Souza"><div class="msg"></div></div>
      <div class="field"><label for="empresa">Empresa</label><input id="empresa" autocomplete="organization" placeholder="Logística XYZ"><div class="msg"></div></div>
    </div>
    <div class="row">
      <div class="field"><label for="email">E-mail corporativo</label><input id="email" type="email" autocomplete="email" placeholder="ana@empresa.com"><div class="msg"></div></div>
      <div class="field"><label for="tel">Telefone</label><input id="tel" type="tel" autocomplete="tel" placeholder="(65) 99999-9999"><div class="msg"></div></div>
    </div>
      <div class="field"><label for="senha">Senha</label><input id="senha" type="password" autocomplete="new-password" placeholder="Mín. 8 caracteres"><div class="meter"><i></i><i></i><i></i><i></i></div><div class="msg"></div></div>
      <div class="field"><label for="conf">Confirmar senha</label><input id="conf" type="password" autocomplete="new-password" placeholder="Repita a senha"><div class="msg"></div></div>
    </div>
    <label class="check"><input type="checkbox" id="termos"><span>Li e aceito os <a href="#">Termos de Uso</a> e a <a href="#">Política de Privacidade</a>.</span></label>
    <div class="msg" id="termosMsg" style="margin:-12px 0 12px"></div>
    <button type="submit" id="btn">Criar conta</button>
    <div class="toast" id="toast"></div>
    <p class="alt">Já tem conta? <a href="login-frota-autonoma.html">Entrar</a></p>
  </form>
</main>
<script>
const $=id=>document.getElementById(id);
const tel=$('tel');
tel.addEventListener('input',()=>{let d=tel.value.replace(/\D/g,'').slice(0,11);
  tel.value=d.length>6?`(${d.slice(0,2)}) ${d.slice(2,d.length-4)}-${d.slice(-4)}`:d.length>2?`(${d.slice(0,2)}) ${d.slice(2)}`:d;});
const score=s=>[s.length>=8,/[A-Z]/.test(s)&&/[a-z]/.test(s),/\d/.test(s),/[^A-Za-z0-9]/.test(s)].filter(Boolean).length;
const colors=['#ff5d5d','#ffb13d','#2de2c4','#c6ff3d'];
$('senha').addEventListener('input',e=>{const n=score(e.target.value);document.querySelectorAll('.meter i').forEach((b,i)=>b.style.background=i<n?colors[n-1]:'')});
function set(id,msg){const f=$(id).closest('.field');f.classList.toggle('invalid',!!msg);f.querySelector('.msg').textContent=msg||'';return !msg}
$('f').addEventListener('submit',e=>{e.preventDefault();
  const v=id=>$(id).value.trim();let ok=true;
  ok&=set('nome',v('nome').split(/\s+/).length<2?'Informe nome e sobrenome.':'');
  ok&=set('empresa',v('empresa')?'':'Informe a empresa.');
  ok&=set('email',/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v('email'))?'':'E-mail inválido.');
  ok&=set('tel',v('tel').replace(/\D/g,'').length>=10?'':'Telefone inválido.');
  ok&=set('perfil',v('perfil')?'':'Selecione um perfil.');
  ok&=set('senha',score($('senha').value)<3?'Use 8+ caracteres, maiúsculas, números ou símbolos.':'');
  ok&=set('conf',$('conf').value&&$('conf').value===$('senha').value?'':'As senhas não coincidem.');
  const t=$('termos').checked;$('termosMsg').textContent=t?'':'É preciso aceitar os termos.';ok&=t;
  if(!ok)return;
  const b=$('btn');b.disabled=true;b.textContent='Criando conta…';
  setTimeout(()=>{b.disabled=false;b.textContent='Criar conta';const m=$('toast');m.className='toast ok';
    m.textContent=`Conta criada, ${v('nome').split(' ')[0]}! Seu acesso será liberado após aprovação.`;$('f').reset();
    document.querySelectorAll('.meter i').forEach(b=>b.style.background='')},1200);
});
</script>


</body>