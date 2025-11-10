<?php
require_once __DIR__ . '/../bd/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

$stmt = $db->prepare("SELECT * FROM tarefas WHERE usuario_id = ? ORDER BY data_criacao DESC");
$stmt->execute([$_SESSION['user_id']]);
$tarefas = $stmt->fetchAll();

$success = '';
if (isset($_GET['deleted'])) {
    $success = "Tarefa deletada com sucesso!";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <title>Listar Tarefas</title>
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

        .sidebar-title {
            text-align: center;
            margin-bottom: 40px;
            color: white;
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

        .main-title {
            margin-top: 0;
            text-align: center;
            color: #6a0dad;
        }

        .message {
            text-align: center;
            padding: 12px;
            margin: 10px auto;
            max-width: 400px;
            border-radius: 8px;
            border: 1px solid #c3e6cb;
            background-color: #d4edda;
            color: #155724;
            font-size: 14px;
            transition: opacity 0.5s ease, max-height 0.5s ease, margin 0.5s ease, padding 0.5s ease;
            overflow: hidden;
        }

        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
        }

        .card.concluida {
            background: #e0e0e0;
            color: #555;
        }

        .card h3 {
            margin-top: 0;
            color: #333;
        }

        .card p {
            margin: 6px 0;
            line-height: 1.4;
        }

        .card .status {
            font-weight: bold;
            margin-top: 10px;
        }

        .card .data {
            font-size: 13px;
            color: #666;
        }

        .actions {
            margin-top: 12px;
            display: flex;
            gap: 10px;
        }

        .btn-edit,
        .btn-delete {
            flex: 1;
            padding: 10px 0;
            border-radius: 8px;
            color: #fff;
            text-decoration: none;
            font-size: 14px;
            text-align: center;
            transition: background 0.2s;
            display: inline-block;
        }

        .btn-edit {
            background: #28a745;
        }

        .btn-edit:hover {
            background: #218838;
        }

        .btn-delete {
            background: #dc3545;
        }

        .btn-delete:hover {
            background: #c82333;
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
</head>

<body>
    <div class="sidebar">
        <h2 class="sidebar-title">Menu</h2>
        <a href="../index.php">Home</a>
        <a href="add_task.php">Criar Tarefa</a>
        <a href="list_task.php">Listar Tarefas</a>
        <a href="../auth/logout.php">Sair</a>
    </div>

    <div class="main">
        <h2 class="main-title">Suas Tarefas</h2>

        <?php if ($success): ?>
            <div id="successMessage" class="message"><?= htmlspecialchars($success) ?></div>
            <script>
                setTimeout(function () {
                    const msg = document.getElementById('successMessage');
                    if (msg) {
                        msg.style.opacity = '0';
                        msg.style.maxHeight = '0';
                        msg.style.margin = '0';
                        msg.style.padding = '0';
                    }
                }, 2000);
            </script>
        <?php endif; ?>

        <?php if (empty($tarefas)): ?>
            <p style="text-align:center;">Nenhuma tarefa cadastrada.</p>
        <?php else: ?>
            <div class="cards-container">
                <?php foreach ($tarefas as $t): ?>
                    <div class="card <?= $t['status'] === 'concluida' ? 'concluida' : '' ?>">
                        <h3><?= htmlspecialchars($t['titulo']) ?></h3>
                        <p><?= nl2br(htmlspecialchars($t['descricao'])) ?></p>
                        <p class="status">Status: <?= ucfirst(htmlspecialchars($t['status'])) ?></p>
                        <p class="data">Criado em: <?= date('d/m/Y H:i', strtotime($t['data_criacao'])) ?></p>
                        <div class="actions">
                            <a class="btn-edit" href="edit_task.php?id=<?= $t['id'] ?>">Editar</a>
                            <a class="btn-delete" href="delete_task.php?id=<?= $t['id'] ?>&deleted=1"
                                onclick="return confirm('Deseja realmente deletar esta tarefa?')">Deletar</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>