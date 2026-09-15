<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Limite Diário de Disparos de Newsletter (Gmail)
    |--------------------------------------------------------------------------
    |
    | Define a quantidade máxima de e-mails de newsletter transmitidos por dia,
    | respeitando os limites da conta do Gmail. Se o limite diário for atingido,
    | os jobs restantes são automaticamente adiados na fila para o dia seguinte.
    |
    */
    'daily_limit' => (int) env('NEWSLETTER_DAILY_LIMIT', 100),

    /*
    |--------------------------------------------------------------------------
    | Intervalo entre disparos na fila (em segundos)
    |--------------------------------------------------------------------------
    |
    | Intervalo suave entre disparos para não sobrecarregar a conexão SMTP.
    |
    */
    'delay_between_sends' => (int) env('NEWSLETTER_DELAY_BETWEEN_SENDS', 2),
];
