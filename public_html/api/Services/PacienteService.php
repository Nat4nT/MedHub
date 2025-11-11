<?php 

namespace Api\Services;

use Api\Models\PacienteModel;

class PacienteService{
    public function buscarSolicitacoes($paciente_id){

        $solicitacoes = (new PacienteModel($paciente_id))->buscarSolicitacoes();
        if ($solicitacoes) {
            return ["code" => 200, "message" => "Solicitações encontradas", "data" => $solicitacoes];
        } else {
            return ["code" => 200, "message" => "Não há Solicitações", "data" => []];
        }
    }
}