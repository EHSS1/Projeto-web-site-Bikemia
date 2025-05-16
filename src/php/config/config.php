<?php
// C:\xampp\htdocs\GrupoBikemia_Final\src\php\config\config.php
declare(strict_types=1);

class Config {
    private static array $settings = [];

    public static function get(string $key): mixed {
        return self::$settings[$key] ?? null;
    }

    public static function load(string $env = 'production'): void {
        $file = __DIR__ . "/{$env}.env.php";
        if (!file_exists($file)) {
            throw new RuntimeException("Ambiente {$env} não configurado");
        }
        self::$settings = require $file;
    }
}

// Carregar configurações
Config::load(getenv('ENVIRONMENT') ?: 'production');

// C:\xampp\htdocs\GrupoBikemia_Final\src\php\config\production.env.php
return [
    'database' => [
        'host' => 'localhost',
        'name' => 'grupo_bikemia',
        'user' => 'bikemia_user',
        'pass' => 'SenhaF0rte!2025',
        'charset' => 'utf8mb4'
    ],
    'security' => [
        'csrf_token' => 'Bikemia_CSRF_Prot_2025',
        'password_algo' => PASSWORD_ARGON2ID
    ]
];
?>