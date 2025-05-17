<?php
require_once __DIR__ . '/config/config.php';

try {
    $config = Config::get('database');
    $dsn = "mysql:host={$config['host']};dbname={$config['name']};charset={$config['charset']}";
    
    $conn = new PDO($dsn, $config['user'], $config['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    error_log("Erro na conexão: " . $e->getMessage());
    die("Erro ao conectar ao banco de dados");
}