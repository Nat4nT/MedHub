<?php

namespace Api\Services;

use Api\Models\AutorizacaoAcessoModel;

class MedicoService
{

    public function solicitar_acesso($medico_id, $paciente_id)
    {
        $data = [
            "medico_id" => $medico_id,
            "paciente_id" => $paciente_id
        ];

        if ($medico_id && $paciente_id) {
            (new AutorizacaoAcessoModel())->AddData($data);
            return ['code' => 200, 'message' => 'Solicitação enviada!'];
        } else {
            return ['code' => 400, 'message' => "Dados Invalidos"];
        }
    }
}
