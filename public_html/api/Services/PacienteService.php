<?php

namespace Api\Services;

use Api\Models\AutorizacaoAcessoModel;
use Api\Models\PacienteModel;

class PacienteService
{
    public function buscarSolicitacoes($paciente_id)
    {

        $solicitacoes = (new PacienteModel($paciente_id))->buscarSolicitacoes();

        foreach ($solicitacoes as $solicitacao) {

            $data[] = [
                'solcitacao_id'=>$solicitacao['autorizacao_acesso_id'],
                'primeiro_nome'=> $solicitacao['primeiro_nome'],
                'ultimo_nome'=> $solicitacao['ultimo_nome'],
                'especialidade'=> $solicitacao['especialidade'],
                'crm'=> $solicitacao['crm'],
                'estado_atuacao'=> $solicitacao['estado_atuacao'],
                'genero'=> $solicitacao['genero'],
                'data_criacao'=> $solicitacao['data_criacao'],
    
            ];
        }


        if ($solicitacoes) {
            return ["code" => 200, "message" => "Solicitações encontradas", "data" => $data];
        } else {
            return ["code" => 200, "message" => "Não há Solicitações", "data" => []];
        }
    }

    public function negarSolicitacao($solicitacao_id)
    {
        $data['status'] = 3;
        (new AutorizacaoAcessoModel($solicitacao_id))->editData($data);
        return ["code" => 200, "message" => "Solicitação negada!"];
    }
    public function aceitarSolicitacao($solicitacao_id)
    {
        $data['status'] = 2;
        (new AutorizacaoAcessoModel($solicitacao_id))->editData($data);
        return ["code" => 200, "message" => "Solicitação aprovada!"];
    }
}
