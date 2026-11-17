<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function index()
    {
        return view('login');
    }

    /* ---------------------------------------------------------------
       UC01 - Autenticar no sistema
       Chaves de sessao usadas: usuario_id e usuario_nome, as mesmas
       lidas pelo middleware 'autenticado' e 'perfil'.
       --------------------------------------------------------------- */
    public function login(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'senha' => 'required|string',
        ]);

        $email = $request->input('email');
        $senha = $request->input('senha');

        $usuario = DB::selectOne(
            'SELECT id, nome, senha, ativo FROM usuario WHERE email = ?',
            [$email]
        );

        if (!$usuario || !$usuario->ativo || !password_verify($senha, $usuario->senha)) {
            return back()
                ->withErrors(['email' => 'E-mail ou senha inválidos, ou usuário suspenso.'])
                ->withInput(['email' => $email]);
        }

        $request->session()->regenerate();
        session([
            'usuario_id'   => $usuario->id,
            'usuario_nome' => $usuario->nome,
        ]);

        $perfis = DB::select(
            'SELECT p.nome
            FROM usuario_perfil_possui upp
            JOIN perfil p ON p.id = upp.fk_perfil_id
            WHERE upp.fk_usuario_id = ? AND upp.ativo = TRUE',
            [$usuario->id]
        );

        session(['perfis' => array_column($perfis, 'nome')]);

        return redirect('/painel');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect('/login');
    }
}