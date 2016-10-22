<?php

return [
    /*
     * The token provided by iugu.
     */
    'token' => env('IUGU_API_TOKEN'),
    'handlers' => [
        \Edbizarro\Slacker\Iugu\Handlers\IuguCustomerHandler::class,
    ],
];
