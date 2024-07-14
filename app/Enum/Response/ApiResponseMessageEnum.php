<?php

namespace App\Enums\Response;

enum ApiResponseMessageEnum: string
{
    case SUCCESS = 'Solicitação realizada com sucesso';
    case FAILURE = 'Houve um problema com sua solicitação. Por favor, tente novamente em alguns minutos';
}
