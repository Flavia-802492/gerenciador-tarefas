<?php
require_once __DIR__ . '/../bd/database.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $db->prepare("DELETE FROM tarefas WHERE id=? AND usuario_id=?");
    $stmt->execute([$id, $_SESSION['user_id']]);
}

header('Location: list_task.php?deleted=1');
exit;