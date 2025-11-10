<?php
require_once __DIR__ . '/../bd/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$errors = [];
$success = '';

if (!isset($_GET['id'])) {
    header('Location: ../index.php');
    exit;
}

$id = intval($_GET['id']);

$stmt = $db->prepare("SELECT * FROM tarefas WHERE id = ? AND usuario_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$tarefa = $stmt->fetch();

if (!$tarefa) {
    header('Location: ../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $descricao = trim($_POST['descricao']);
    $status = $_POST['status'] ?? 'pendente';

    if (empty($titulo)) {
        $errors[] = "O campo Título é obrigatório.";
    }

    if (empty($errors)) {
        $stmt = $db->prepare("UPDATE tarefas SET titulo = ?, descricao = ?, status = ? WHERE id = ? AND usuario_id = ?");
        if ($stmt->execute([$titulo, $descricao, $status, $id, $_SESSION['user_id']])) {
            $success = "Tarefa atualizada com sucesso!";
            $tarefa['titulo'] = $titulo;
            $tarefa['descricao'] = $descricao;
            $tarefa['status'] = $status;
        } else {
            $errors[] = "Erro ao atualizar tarefa.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Editar Tarefa</title>
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
            color: #fff;
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
            padding: 30px;
        }

        .card {
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            margin: 40px auto;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #6a0dad;
        }

        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
            font-family: inherit;
        }

        textarea {
            resize: vertical;
            min-height: 80px;
        }

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }

        .btn {
            padding: 12px 20px;
            background: #6a0dad;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-family: inherit;
            transition: background 0.2s;
            margin: 5px;
        }

        .btn:hover {
            background: #520e8a;
        }

        .btn-cancel {
            background: #dc3545;
            text-decoration: none;
            display: inline-block;
            color: #fff;
        }

        .btn-cancel:hover {
            background: #c82333;
        }

        .message {
            text-align: center;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
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
        }
    </style>
    <?php if ($success): ?>
        <script>
            setTimeout(function () {
                window.location.href = 'list_task.php';
            }, 2000);
        </script>
    <?php endif; ?>
</head>

<body>
    <div class="sidebar">
        <h2>Menu</h2>
        <a href="../index.php">Home</a>
        <a href="add_task.php">Criar Tarefa</a>
        <a href="list_task.php">Listar Tarefas</a>
        <a href="../auth/logout.php">Sair</a>
    </div>

    <div class="main">
        <div class="card">
            <h2>Editar Tarefa</h2>

            <?php foreach ($errors as $err): ?>
                <div class="message error"><?= htmlspecialchars($err) ?></div>
            <?php endforeach; ?>

            <?php if ($success): ?>
                <div class="message success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>

            <form method="POST">
                <label for="titulo">Título</label>
                <input type="text" name="titulo" id="titulo" value="<?= htmlspecialchars($tarefa['titulo']) ?>">

                <label for="descricao">Descrição</label>
                <textarea name="descricao" id="descricao"><?= htmlspecialchars($tarefa['descricao']) ?></textarea>

                <label for="status">Status</label>
                <select name="status" id="status">
                    <option value="pendente" <?= $tarefa['status'] === 'pendente' ? 'selected' : '' ?>>Pendente</option>
                    <option value="concluida" <?= $tarefa['status'] === 'concluida' ? 'selected' : '' ?>>Concluída</option>
                </select>

                <div class="btn-container">
                    <button type="submit" class="btn">Atualizar Tarefa</button>
                    <a href="list_task.php" class="btn btn-cancel">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>