<?php

declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';


use App\Helpers\Criptografia;
use Phinx\Seed\AbstractSeed;

class UsuarioSeeder extends AbstractSeed
{
    public function run(): void
    {
        $criptografar = new Criptografia();
        $data = [
            [
                'usuario_id' => 1,
                'tipo_usuario' => 'medico',
                'primeiro_nome' => 'João',
                'ultimo_nome' => 'Silva',
                'genero' => 1,
                'imagem_perfil' => null,
                'cpf' => $criptografar->encriptarDado('12345678901'),
                'telefone' => '11999999999',
                'email' => 'joao.silva@example.com',
                'senha' => password_hash('senha123', PASSWORD_BCRYPT),
                'data_cadastro' => date('Y-m-d H:i:s'),
                'data_atualizacao' => date('Y-m-d H:i:s'),
                'consentimento_lgpd' => true
            ],
            [
                'usuario_id' => 2,
                'tipo_usuario' => 'paciente',
                'primeiro_nome' => 'Maria',
                'ultimo_nome' => 'Oliveira',
                'genero' => 2,
                'imagem_perfil' => null,
                'cpf' => $criptografar->encriptarDado('98765432100'),
                'telefone' => '11988888888',
                'email' => 'maria.oliveira@example.com',
                'senha' => password_hash('senha456', PASSWORD_BCRYPT),
                'data_cadastro' => date('Y-m-d H:i:s'),
                'data_atualizacao' => date('Y-m-d H:i:s'),
                'consentimento_lgpd' => true
            ],
        ];

        $this->table('usuario')->insert($data)->saveData();
    }
}
