<?php
// Configurações do ambiente
return [
    'database' => [
        'host' => 'localhost',
        'name' => 'grupo_bikemia',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4'
    ],
    'security' => [
        'csrf_token' => bin2hex(random_bytes(32)),
        'password_algo' => PASSWORD_DEFAULT
    ]
];