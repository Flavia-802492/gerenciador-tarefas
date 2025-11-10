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
    <style>
        body {
            display: flex;
            font-family: 'Times New Roman', Times, serif;
            margin: 0;
            background: #f0f2f5;
        }

        .sidebar {
            width: 220px;
            background: #6a0dad;
            color: #fff;
            min-height: 100vh;
            padding: 30px 20px;
        }

        .sidebar a {
            display: block;
            color: #fff;
            text-decoration: none;
            padding: 15px 10px;
            margin-bottom: 10px;
            border-radius: 8px;
        }

        .sidebar a:hover {
            background: #520e8a;
        }

        .main {
            flex: 1;
            padding: 30px;
            background: #fff;
            border-radius: 8px;
            margin: 40px auto;
            max-width: 600px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
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
            border-radius: 8px;
            font-size: 15px;
            font-family: inherit;
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
            margin: 5px;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background: #520e8a;
        }

        .btn-cancel {
            background: #dc3545;
            text-decoration: none;
            color: #fff;
        }

        .btn-cancel:hover {
            background: #b71c1c;
        }

        .message {
            text-align: center;
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 8px;
            border: 1px solid;
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .success {
            background: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
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
</body>

</html>