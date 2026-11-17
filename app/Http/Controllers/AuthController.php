<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /* ---------------------------------------------------------------
       Telas
       --------------------------------------------------------------- */

    public function index()
    {
        return view('login');
    }

    public function indexcadastro()
    {
        return view('cadastro');
    }

    /* ---------------------------------------------------------------
       UC17 - Cadastrar-se como cliente
       Insere o usuario e associa o perfil Cliente (id 3) na mesma
       transacao. Se qualquer insercao falhar, nada e gravado.
       --------------------------------------------------------------- */
    public function cadastrar(Request $request)
    {

        $dados = $request->validate([
            'nome'      => 'required|string|max:100',
            'email'     => 'required|email|max:50',
            'data_nasc' => 'required|date|before:today',
            'sexo'      => 'required|in:M,F',
            'cpf'       => 'required|digits:11',
            'senha'     => 'required|string|min:6|confirmed',
        ], [
            'senha.confirmed' => 'A confirmação da senha não confere.',
        ]);

        $duplicado = DB::selectOne(
            'SELECT email, cpf FROM usuario WHERE email = ? OR cpf = ?',
            [$dados['email'], $dados['cpf']]
        );

        if ($duplicado) {
            $campo = $duplicado->email === $dados['email'] ? 'email' : 'cpf';
            $mensagem = $campo === 'email'
                ? 'Este e-mail já está cadastrado.'
                : 'Este CPF já está cadastrado.';

            return back()->withErrors([$campo => $mensagem])->withInput($request->except('senha', 'senha_confirmation'));
        }

        DB::transaction(function () use ($dados) {
            $id = DB::selectOne('SELECT COALESCE(MAX(id), 0) + 1 AS novo_id FROM usuario')->novo_id;

            DB::insert(
                'INSERT INTO usuario (id, nome, email, data_nasc, sexo, cpf, ativo, senha)
                 VALUES (?, ?, ?, ?, ?, ?, TRUE, ?)',
                [
                    $id,
                    $dados['nome'],
                    $dados['email'],
                    $dados['data_nasc'],
                    $dados['sexo'],
                    $dados['cpf'],
                    password_hash($dados['senha'], PASSWORD_BCRYPT),
                ]
            );

            DB::insert(
                'INSERT INTO usuario_perfil_possui (data_atribuicao, ativo, fk_perfil_id, fk_usuario_id)
                 VALUES (CURRENT_TIMESTAMP, TRUE, 3, ?)',
                [$id]
            );
        });

        return redirect('/login')->with('sucesso', 'Cadastro realizado com sucesso. Faça login para continuar.');
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