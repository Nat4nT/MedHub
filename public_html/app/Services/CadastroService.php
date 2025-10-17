<?php

namespace App\Helpers;

use App\Models\UsuarioModel;
use App\Services\AutenticacaoService;

class CadastroService
{

    public function realizarCadastro(array $dados = [])
    {
        if (empty($dados)) {
            return ['status' => 401, 'mensagem' => "Dados Invalidos"];
        }

        $usuario = new UsuarioModel();
        if(!$usuario->buscarPorEmail($dados["email"])) {
            $senha_login = $dados['senha'];
            $dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
            $usuario->AddData($dados);
            $token = (new AutenticacaoService())->realizarLogin($dados['email'], $senha_login);
            return ['status'=>200,'mensagem'=>"Cadastro realizado com sucesso","token"=> $token];
        }else{
            return ['status' => 401, 'mensagem' => "Email já cadastrado!"];

        }
    }
}
