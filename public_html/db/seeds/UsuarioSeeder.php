<?php
declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class UsuarioSeeder extends AbstractSeed
{
    public function run(): void
    {
        $data = [
            [
                'tipo_usuario' => 'paciente',
                'primeiro_nome' => 'João',
                'ultimo_nome' => 'Silva',
                'genero' => 1,
                'imagem_perfil' => null,
                'cpf' => '12345678901',
                'telefone' => '11999999999',
                'email' => 'joao.silva@example.com',
                'senha' => password_hash('senha123', PASSWORD_DEFAULT), // cuidado, pode querer hashear
                'data_cadastro' => date('Y-m-d H:i:s'),
                'data_atualizacao' => date('Y-m-d H:i:s'),
                'consentimento_lgpd' => 1,
            ],
        ];

        $this->table('usuario')->insert($data)->saveData();
    }
}
