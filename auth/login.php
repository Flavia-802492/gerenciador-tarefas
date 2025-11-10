<?php
session_start();
require_once '../bd/database.php';

$errors = [];
$success = '';

if (isset($_SESSION['logout_message'])) {
    $success = $_SESSION['logout_message'];
    unset($_SESSION['logout_message']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($email))
        $errors[] = "O campo Email é obrigatório.";
    if (empty($senha))
        $errors[] = "O campo Senha é obrigatório.";

    if (empty($errors)) {
        $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nome'] = $user['nome'];
            header("refresh:2;url=../index.php");
            $success = "Login realizado com sucesso! Redirecionando...";
        } else {
            $errors[] = "Email ou senha inválidos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
            padding: 30px 35px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 25px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 12px;
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

        #login-form {
            display:
                <?= $success ? 'none' : 'block' ?>
            ;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Login</h2>

        <?php foreach ($errors as $err): ?>
            <div class="message error"><?= htmlspecialchars($err) ?></div>
        <?php endforeach; ?>

        <?php if ($success): ?>
            <div id="success-msg" class="message success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form id="login-form" method="POST">
            <input type="email" name="email" placeholder="Digite seu email"
                value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
            <input type="password" name="senha" placeholder="Digite sua senha">
            <div class="btn-container">
                <button type="submit">Fazer Login</button>
                <a href="register.php" class="link-login">Não tem conta? Cadastre-se</a>
            </div>
        </form>
    </div>

    <script>
        const successMsg = document.getElementById('success-msg');
        const loginForm = document.getElementById('login-form');

        if (successMsg) {
            setTimeout(() => {
                successMsg.style.display = 'none';
                loginForm.style.display = 'block';
            }, 2000);
        }
    </script>
</body>

</html>