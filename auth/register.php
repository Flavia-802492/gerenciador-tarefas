<?php
require_once '../bd/database.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];
    $confirm_senha = $_POST['confirm_senha'];

    if (empty($nome))
        $errors[] = "O campo Nome é obrigatório.";
    if (empty($email))
        $errors[] = "O campo Email é obrigatório.";
    if (empty($senha))
        $errors[] = "O campo Senha é obrigatório.";
    if (empty($confirm_senha))
        $errors[] = "O campo Confirmar Senha é obrigatório.";

    if (!empty($senha) && strlen($senha) < 6)
        $errors[] = "A senha deve ter no mínimo 6 caracteres.";

    if ($senha !== $confirm_senha)
        $errors[] = "As senhas não coincidem.";

    if (empty($errors)) {
        $stmt = $db->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors[] = "Email já cadastrado.";
        }
    }

    if (empty($errors)) {
        $hashed = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (nome, email, senha) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $email, $hashed]);

        $_SESSION['user_id'] = $db->lastInsertId();
        $_SESSION['nome'] = $nome;

        $success = "Cadastro realizado com sucesso! Redirecionando...";
        header("refresh:2;url=../index.php");
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            background: #f0f2f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 25px 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        input {
            justify-content: center;
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .btn-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        button {
            justify-content: center;
            width: 100%;
            padding: 12px;
            background: #6a0dad;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.2s;
        }

        button:hover {
            background: #520e8a;
        }

        .link-login {
            color: #6a0dad;
            text-decoration: none;
            font-size: 14px;
        }

        .link-login:hover {
            text-decoration: underline;
        }

        .message {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            font-size: 14px;
            border: 1px solid;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Cadastro</h2>
        <?php foreach ($errors as $err): ?>
            <div class="message error"><?= htmlspecialchars($err) ?></div>
        <?php endforeach; ?>
        <?php if ($success): ?>
            <div class="message success"><?= htmlspecialchars($success) ?></div>
        <?php else: ?>
            <form method="POST">
                <input type="text" name="nome" placeholder="Digite seu nome"
                    value="<?= isset($nome) ? htmlspecialchars($nome) : '' ?>">
                <input type="email" name="email" placeholder="Digite seu email"
                    value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
                <input type="password" name="senha" placeholder="Digite sua senha (mínimo 6 caracteres)">
                <input type="password" name="confirm_senha" placeholder="Confirme sua senha">
                <div class="btn-container">
                    <button type="submit">Cadastrar</button>
                    <a href="login.php" class="link-login">Já tem conta? Faça login</a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>