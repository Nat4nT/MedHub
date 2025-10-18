<?php

namespace App\Services;

use App\Helpers\Criptografia;
use App\Models\MedicoModel;
use App\Models\PacienteModel;
use App\Models\UsuarioModel;
use App\Services\AutenticacaoService;

class CadastroService
{

    public function realizarCadastro(array $dados = [])
    {
        if (empty($dados)) {
            return ['status' => 401, 'mensagem' => "Dados Invalidos"];
        }


        $dadosUsuario = [
            'tipo_usuario' => $dados['tipo_usuario'],
            "primeiro_nome" => $dados["primeiro_nome"],
            'ultimo_nome' => $dados['ultimo_nome'],
            'genero' => $dados['genero'],
            'cpf' => (new Criptografia)->encriptarDado($dados['cpf']),
            'telefone' => $dados['telefone'],
            'email' => $dados['email'],
            'senha' => $dados['senha'],
            'consentimento_lgpd' => $dados['consentimento_lgpd']
        ];

        if (!is_null($dados['imagem_perfil'])) {
            $dadosUsuario['files'] = [$dados['imagem_perfil']];
        }


        $usuario = new UsuarioModel();

        if (!$usuario->buscarPorEmail($dados["email"])) {
            $senha_login = $dados['senha'];
            $dadosUsuario['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
            $id_novo_usuario = $usuario->AddData($dadosUsuario);

            if ($id_novo_usuario) {

                if ($dados['tipo_usuario'] === 'medico') {
                    $dadosTipoUsuario = [
                        'medico_id' => $id_novo_usuario,
                        'especialidade' => $dados['especialidade'],
                        'crm' => $dados['crm'],
                        'estado_atuacao' => $dados['estado_atuacao'],
                    ];
                    (new MedicoModel())->AddData($dadosTipoUsuario);
                } else {
                    $dadosTipoUsuario = [
                        'paciente_id' => $id_novo_usuario,
                        'data_nascimento' => $dados['data_nascimento'],
                        'peso' => $dados['peso'] ?? null,
                        'altura' => $dados['altura'] ?? null,
                        'desc_deficiencia' => $dados['desc_deficiencia'] ?? null,
                        'tipo_sanguineo' => $dados['tipo_sanguineo'] ?? null,
                        'alergias' => $dados['alergias'] ?? null
                    ];
                    (new PacienteModel())->addData($dadosTipoUsuario);
                }

                $token = (new AutenticacaoService())->realizarLogin($dados['email'], $senha_login);
                return ['status' => 200, 'mensagem' => "Cadastro realizado com sucesso", "token" => $token];
            } else {
                return ["status" => 500, 'mensagem' => 'Erro ao registrar usuario'];
            }
        } else {
            return ['status' => 401, 'mensagem' => "Email já cadastrado!"];
        }
    }
}
