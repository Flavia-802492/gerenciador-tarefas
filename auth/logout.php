<?php
require_once __DIR__ . '/../bd/config.php';
session_start();

$logout_message = "Você saiu com sucesso!";

session_destroy();

session_start();
$_SESSION['logout_message'] = $logout_message;

header('Location: login.php');
exit;
