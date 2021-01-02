<?php

return [
    'root_domain' => env('SHORTY_DOMAIN', ''),
    'excluded_domains' => [env('APP_DOMAIN')],
    'root_route' => false,

    'actions' => [
        'PrepareRequestData' => \AusbildungMS\Shorty\Actions\Request\PrepareRequestData::class,
        'generateShort' => \AusbildungMS\Shorty\Actions\GenerateShortForLink::class,
    ]
];