<?php
require_once __DIR__ . '/bd/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: auth/login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Home - Gerenciador de Tarefas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            display: flex;
            min-height: 100vh;
            background: #f5f5f5;
        }

        .sidebar {
            width: 220px;
            background: #6a0dad;
            color: #fff;
            padding: 30px 20px;
            box-sizing: border-box;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 40px;
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 15px 10px;
            margin-bottom: 10px;
            font-size: 18px;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .sidebar a:hover {
            background: #520e8a;
        }

        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }

        .home-container {
            text-align: center;
            background: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }

        h1 {
            color: #6a0dad;
            margin-bottom: 25px;
        }

        img {
            max-width: 300px;
            height: auto;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 14px 0;
            margin-bottom: 15px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            color: #fff;
            text-decoration: none;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-cadastrar {
            background: #6a0dad;
        }

        .btn-cadastrar:hover {
            background: #520e8a;
        }

        .btn-listar {
            background: #9b4de1;
        }

        .btn-listar:hover {
            background: #7d39b3;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                text-align: center;
            }

            .main {
                padding: 20px;
            }

            .home-container {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar">
        <h2>Menu</h2>
        <a href="index.php">Home</a>
        <a href="crud/add_task.php">Criar Tarefa</a>
        <a href="crud/list_task.php">Listar Tarefas</a>
        <a href="auth/logout.php">Sair</a>
    </div>

    <div class="main">
        <div class="home-container">
            <h1>Bem-vindo, <?= htmlspecialchars($_SESSION['nome']) ?>!</h1>

            <img src="assets/home_image.jpeg" alt="Imagem de boas-vindas">

            <a href="crud/add_task.php" class="btn btn-cadastrar">Cadastrar Tarefa</a>
            <a href="crud/list_task.php" class="btn btn-listar">Listar Tarefas</a>
        </div>
    </div>
</body>

</html>