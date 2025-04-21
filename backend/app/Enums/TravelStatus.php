<?php

namespace App\Enums;

enum TravelStatus: int
{
    case SOLICITADO = 1;
    case APROVADO = 2;
    case CANCELADO = 3;
}
