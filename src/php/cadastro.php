<?php
session_start();
require_once 'conexao.php';
header('Content-Type: application/json');

try {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $nome = trim($input['nome'] ?? '');
    $apelido = trim($input['apelido'] ?? '');
    $email = trim($input['email'] ?? '');
    $telefone = trim($input['telefone'] ?? '');
    $senha = $input['senha'] ?? '';
    
    if (!$nome || !$apelido || !$email || !$senha) {
        throw new Exception('Todos os campos obrigatórios devem ser preenchidos');
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception('E-mail inválido');
    }

    if (strlen($senha) < 6) {
        throw new Exception('A senha deve ter no mínimo 6 caracteres');
    }

    $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        throw new Exception('E-mail já cadastrado');
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, apelido, email, telefone, senha) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $apelido, $email, $telefone, $senhaHash]);

    echo json_encode([
        'success' => true,
        'message' => 'Cadastro realizado com sucesso!'
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}