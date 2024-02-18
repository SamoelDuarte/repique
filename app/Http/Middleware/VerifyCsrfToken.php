<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
       '/events',
       '/app/retorna_ultimos_calculos',
       '/app/retorna_colaboradores',
       '/app/insere_calculo',
       '/app/retorna_calculo_admin',
       '/app/retorna_ultimos_calculos_repique',
       '/app/retorna_calculo_onda'
    ];
}
