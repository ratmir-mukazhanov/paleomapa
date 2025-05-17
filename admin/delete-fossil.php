<?php
session_start();

// Verificar se o utilizador está autenticado e é um admin
if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: ../login/login.php");
    exit();
}

require_once "../services/dashboard_service.php";
$dashboardService = new DashboardService();

// Verificar se foi fornecido um ID de fóssil
if (!isset($_POST['fossil_id']) || empty($_POST['fossil_id'])) {
    header("Location: fossils-management.php?error=no_id");
    exit();
}

$fossilId = (int)$_POST['fossil_id'];

// Tentar excluir o fóssil
if ($dashboardService->deleteFossil($fossilId)) {
    // Redirecionar com mensagem de sucesso
    header("Location: fossils-management.php?success=1");
    exit();
} else {
    // Redirecionar com mensagem de erro
    header("Location: fossils-management.php?error=delete_failed");
    exit();
}