<?php
session_start();

// Verificar se o utilizador está autenticado e é um admin
if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    // Redirecionar para a página de login se não estiver autenticado como admin
    header("Location: ../login/login.php");
    exit();
}

require_once "../services/dashboard_service.php";
$dashboardService = new DashboardService();

$totalFosseis = $dashboardService->getTotalFosseis();
$totalPontosInteresse = $dashboardService->getTotalPontosInteresse();
$totalArchaeologicalSites = $dashboardService->getTotalArchaeologicalSites();
$totalMuseums = $dashboardService->getTotalMuseums();
$totalContactRequests = $dashboardService->getTotalContactRequests();
$temporalFossilData = $dashboardService->getTemporalFossilData();
$temporalContactData = $dashboardService->getTemporalContactData();

?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Paleomapa</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cores.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php 
        require_once "../components/header.php";
        require_once "../components/sidebar.php";
    ?>
    <div class="dashboard-container">
        <h1 class="dashboard-title">Dashboard Admin</h1>
        
        <div class="stats-cards">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-bone"></i>
                </div>
                <div class="stat-content">
                    <h3>Total de Fósseis</h3>
                    <p class="stat-number"><?php echo $totalFosseis; ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div class="stat-content">
                    <h3>Pontos de Interesse</h3>
                    <p class="stat-number"><?php echo $totalPontosInteresse; ?></p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div class="stat-content">
                    <h3>Pedidos de Contacto</h3>
                    <p class="stat-number"><?php echo $totalContactRequests; ?></p>
                </div>
            </div>
        </div>
        
        <div class="admin-sections">
            <div class="admin-sections">
                <div class="section chart-section">
                    <h2><i class="fas fa-chart-line"></i> Estatísticas Temporais</h2>
                    <div class="charts-container">
                        <div class="chart-card">
                            <h3>Fósseis Inseridos por Mês</h3>
                            <canvas id="fossilChart"></canvas>
                        </div>
                        <div class="chart-card">
                            <h3>Pedidos de Contacto por Mês</h3>
                            <canvas id="contactChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section">
                <h2><i class="fas fa-database"></i> Gestão de Dados</h2>
                <div class="action-buttons">
                    <a href="#" class="admin-btn">Dados Paleontológicos</a>
                </div>
            </div>
            <div class="section">
                <h2><i class="fas fa-database"></i> Gestão de Pedidos de Contacto</h2>
                <div class="action-buttons">
                    <a href="contact-requests.php" class="admin-btn">Ver Pedidos de Contactos</a>
                </div>
            </div>
        </div>
        <script src="../js/dashboard.js"></script>
        <script>
            window.dashboardData = {
                fossilData: <?php echo json_encode($temporalFossilData); ?>,
                contactData: <?php echo json_encode($temporalContactData); ?>
            };
        </script>
</body>
</html>