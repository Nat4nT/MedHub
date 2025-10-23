<?php

namespace App\Services;

use App\Helpers\Criptografia;
use App\Models\EnderecoModel;
use App\Models\MedicoModel;
use App\Models\PacienteModel;
use App\Models\UsuarioModel;
use App\Services\AutenticacaoService;


class UsuarioService
{


    private function prepareUserData(array $dados): array
    {
        $dadosUsuario = [
            'tipo_usuario' => $dados['tipo_usuario'] ?? null,
            "primeiro_nome" => $dados["primeiro_nome"] ?? null,
            'ultimo_nome' => $dados['ultimo_nome'] ?? null,
            'genero' => $dados['genero'] ?? null,
            'cpf' => (new Criptografia())->encriptarDado($dados['cpf'] ?? ''),
            'telefone' => $dados['telefone'] ?? null,
            'email' => $dados['email'] ?? null,
            'consentimento_lgpd' => $dados['consentimento_lgpd'] ?? 0,
        ];

        if (isset($dados['imagem_perfil']) && !is_null($dados['imagem_perfil'])) {
            $dadosUsuario['files'] = $dados['imagem_perfil'];
        }
        if (isset($dados['senha']) && !empty($dados['senha'])) {
            $dadosUsuario['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        }

        return $dadosUsuario;
    }

    private function prepareAddressData($usuarioId, array $dadosEnderecoForm): array
    {
        return [
            'usuario_id' => $usuarioId,
            'rua' => $dadosEnderecoForm['rua'] ?? null,
            'bairro' => $dadosEnderecoForm['bairro'] ?? null,
            'numero' => $dadosEnderecoForm['numero'] ?? null,
            'cep' => $dadosEnderecoForm['cep'] ?? null,
            'cidade' => $dadosEnderecoForm['cidade'] ?? null,
            'estado' => $dadosEnderecoForm['estado'] ?? null,
            'complemento' => $dadosEnderecoForm['complemento'] ?? ''
        ];
    }

    private function prepareMedicoData($usuarioId, array $dados): array
    {
        return [
            'medico_id' => $usuarioId,
            'especialidade' => $dados['especialidade'] ?? null,
            'crm' => $dados['crm'] ?? null,
            'estado_atuacao' => $dados['estado_atuacao'] ?? null,
        ];
    }


    private function preparePacienteData(int $usuarioId, array $dados): array
    {
        $dados_usuario = [
            'paciente_id' => $usuarioId,
            'data_nascimento' => $dados['data_nascimento'] ?? null,
            'peso' => $dados['peso'] ?? null,
            'altura' => $dados['altura'] ?? null,
            'desc_deficiencia' => $dados['desc_deficiencia'] ?? null,
            'tipo_sanguineo' => $dados['tipo_sanguineo'] ?? null,
            'alergias' => $dados['alergias'] ?? null
        ];

        return $dados_usuario;
    }


    public function realizarCadastro(array $dados = []): array
    {
        $usuarioModel = new UsuarioModel();

        if (empty($dados)) {
            return ['code' => 401, 'message' => "Dados Inválidos"];
        }
        if ($usuarioModel->buscarPorEmail($dados["email"] ?? '')) {
            return ['code' => 401, 'message' => "Email já cadastrado!"];
        }

        $senhaLogin = $dados['senha'] ?? null;
        $dadosUsuario = $this->prepareUserData($dados);
        $idNovoUsuario = $usuarioModel->AddData($dadosUsuario);

        if (!$idNovoUsuario) {
            return ["code" => 500, 'message' => 'Erro ao registrar usuário'];
        }

        if (isset($dados['endereco'])) {
            $dadosEndereco = $this->prepareAddressData($idNovoUsuario, $dados['endereco']);

            (new EnderecoModel())->AddData($dadosEndereco);
        }

        if (($dados['tipo_usuario'] ?? '') === 'medico') {
            $dadosTipoUsuario = $this->prepareMedicoData($idNovoUsuario, $dados);
            (new MedicoModel())->AddData($dadosTipoUsuario);
        } else {
            $dadosTipoUsuario = $this->preparePacienteData($idNovoUsuario, $dados);
            (new PacienteModel())->addData($dadosTipoUsuario);
        }
        $token = (new AutenticacaoService())->realizarLogin($dados['email'], $senhaLogin);
        return ['code' => 200, 'message' => "Cadastro realizado com sucesso", "token" => $token];
    }

    public function buscarDados(string $tipo): array
    {
        $dados = (new UsuarioModel())->buscarUsuario($tipo);

        if ($dados && $dados['status'] == 1) {
            $coluna = $tipo . '_id';
            unset($dados['usuario_id'], $dados[$coluna], $dados['endereco_id']);

            $dados['cpf'] = (new Criptografia())->decriptarDado($dados['cpf']);

            return [
                "message" => 'Perfil encontrado',
                'data' => $dados,
                'code' => 200
            ];
        }

        return [
            "message" => 'Perfil não encontrado',
            'data' => [],
            'code' => 404
        ];
    }


    public function editarUsuario(object $dadosSessao, array $dadosFormulario): array
    {
        $usuarioId = $dadosSessao->usuario_id;
        $tipoUsuario = $dadosSessao->tipo_usuario;

        $dadosUsuario = $this->prepareUserData($dadosFormulario);

        if (isset($dadosFormulario['senha']) && empty($dadosFormulario['senha'])) {
            unset($dadosUsuario['senha']);
        }
        (new UsuarioModel($usuarioId))->editData($dadosUsuario);

        $dadosEndereco = $this->prepareAddressData($usuarioId, $dadosFormulario['endereco']);
        (new EnderecoModel($usuarioId))->editData($dadosEndereco);

        if ($tipoUsuario === 'medico') {
            $dadosTipoUsuario = $this->prepareMedicoData($usuarioId, $dadosFormulario);
            (new MedicoModel($usuarioId))->editData($dadosTipoUsuario);
        } else {
            $dadosTipoUsuario = $this->preparePacienteData($usuarioId, $dadosFormulario);
            (new PacienteModel($usuarioId))->editData($dadosTipoUsuario);
        }


        return [
            "message" => 'Perfil atualizado com sucesso',
            'code' => 200
        ];
    }


    public function desativar(int $idPerfil): array
    {
        if (empty($idPerfil)) {
            return [
                "message" => 'ID não informado',
                'code' => 400
            ];
        }
        $response = (new UsuarioModel($idPerfil))->desativarPerfil();
        if ($response) {
            return [
                "message" => 'Perfil desativado com sucesso',
                'code' => 200
            ];
        } else {
            return [
                "message" => 'Erro ao inativar perfil',
                'code' => 200
            ];
        }
    }
}
