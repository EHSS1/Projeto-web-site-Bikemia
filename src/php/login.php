<?php
session_start();
require_once 'conexao.php';
header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    $usuario = trim($input['usuario'] ?? '');
    $senha = $input['senha'] ?? '';

    if (!$usuario || !$senha) {
        throw new Exception('Usuário e senha são obrigatórios');
    }

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ? OR apelido = ?");
    $stmt->execute([$usuario, $usuario]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($senha, $user['senha'])) {
        throw new Exception('Credenciais inválidas');
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['nome'];

    echo json_encode([
        'success' => true,
        'message' => 'Login realizado com sucesso!',
        'usuario' => [
            'id' => $user['id'],
            'nome' => $user['nome'],
            'apelido' => $user['apelido']
        ]
    ]);

} catch (Exception $e) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}