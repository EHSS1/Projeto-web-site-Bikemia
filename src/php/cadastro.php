<?php
header('Content-Type: application/json');
require 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Método não permitido']);
    exit;
}

try {
    $nome = trim($_POST['nome'] ?? '');
    $apelido = trim($_POST['apelido'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmar = $_POST['confirmar_senha'] ?? '';

    // Validação
    if (!$nome || !$apelido || !$email || !$senha || !$confirmar) {
        throw new Exception("Preencha todos os campos obrigatórios.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("E-mail inválido.");
    }

    if (strlen($senha) < 6) {
        throw new Exception("A senha deve ter no mínimo 6 caracteres.");
    }

    if ($senha !== $confirmar) {
        throw new Exception("As senhas não coincidem.");
    }

    // Verificar duplicidade de e-mail
    $verifica = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
    $verifica->execute([$email]);

    if ($verifica->rowCount() > 0) {
        throw new Exception("E-mail já cadastrado.");
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // Inserir no banco
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, apelido, email, telefone, senha, data_cadastro) 
                            VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->execute([$nome, $apelido, $email, $telefone, $senhaHash]);

    echo json_encode(['success' => true, 'message' => 'Cadastro salvo no banco de dados com sucesso!']);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>




