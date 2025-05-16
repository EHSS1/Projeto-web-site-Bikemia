<?php
// src/php/login.php
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Security.php';

header('Content-Type: application/json');

try {
    Security::validateRequestMethod('POST');
    Security::validateCSRF();

    $input = json_decode(file_get_contents('php://input'), true);
    $usuario = Security::sanitize($input['usuario'] ?? '');
    $senha = $input['senha'] ?? '';

    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ? OR apelido = ?");
    $stmt->execute([$usuario, $usuario]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($senha, $user['senha'])) {
        throw new RuntimeException('Credenciais inválidas', 401);
    }

    Security::startSession($user);
    
    echo json_encode([
        'success' => true,
        'user' => [
            'id' => $user['id'],
            'apelido' => htmlspecialchars($user['apelido'])
        ]
    ]);

} catch (RuntimeException $e) {
    http_response_code($e->getCode());
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}



