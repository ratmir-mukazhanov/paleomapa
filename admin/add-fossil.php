<?php
session_start();

// Verificar se o utilizador está autenticado e é um admin
if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: ../login/login.php");
    exit();
}

require_once "../services/dashboard_service.php";
$dashboardService = new DashboardService();

$errorMsg = "";
$successMsg = "";

// Processar o formulário quando enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar todos os campos do formulário
    $title = isset($_POST['title']) ? trim($_POST['title']) : null;
    $discoveredBy = isset($_POST['discovered_by']) ? trim($_POST['discovered_by']) : null;
    $dateDiscovered = isset($_POST['date_discovered']) && !empty($_POST['date_discovered']) ?
        trim($_POST['date_discovered']) : null;
    $kingdom = isset($_POST['kingdom']) ? trim($_POST['kingdom']) : null;
    $phylum = isset($_POST['phylum']) ? trim($_POST['phylum']) : null;
    $class = isset($_POST['class']) ? trim($_POST['class']) : null;
    $order = isset($_POST['order']) ? trim($_POST['order']) : null;
    $family = isset($_POST['family']) ? trim($_POST['family']) : null;
    $genus = isset($_POST['genus']) ? trim($_POST['genus']) : null;
    $species = isset($_POST['species']) ? trim($_POST['species']) : null;
    $latitude = isset($_POST['latitude']) ? trim($_POST['latitude']) : '';
    $longitude = isset($_POST['longitude']) ? trim($_POST['longitude']) : '';

    // Validação básica - apenas coordenadas são obrigatórias
    if (empty($latitude) || empty($longitude)) {
        $errorMsg = "Por favor, forneça valores válidos para latitude e longitude.";
    } elseif (!is_numeric($latitude) || !is_numeric($longitude)) {
        $errorMsg = "Latitude e longitude devem ser valores numéricos válidos.";
    } else {
        // Adicionar o fóssil ao banco de dados
        $result = $dashboardService->addFossil($title, $discoveredBy, $dateDiscovered,
            $kingdom, $phylum, $class, $order,
            $family, $genus, $species,
            $latitude, $longitude);

        if ($result) {
            // Redirecionar para a página de gestão de fósseis com mensagem de sucesso
            header("Location: fossils-management.php?success=1");
            exit();
        } else {
            $errorMsg = "Erro ao adicionar o fóssil. Por favor, tente novamente.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Fóssil - Paleomapa</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cores.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        .form-container {
            background-color: var(--text-white);
            border-radius: var(--border-radius);
            padding: 25px;
            box-shadow: var(--shadow);
        }

        .dashboard-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-back-fossils {
            background-color: transparent;
            color: var(--primary-color);
            border: 1px solid var(--primary-color);
            padding: 6px 12px;
            border-radius: var(--border-radius);
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: var(--transition);
        }

        .btn-back-fossils:hover {
            background-color: var(--primary-color);
            color: var(--text-white);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-black);
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--highlight);
            border-radius: var(--border-radius);
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .btn-submit {
            background-color: var(--success-color);
            color: var(--text-white);
            border: none;
            padding: 12px 20px;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-submit:hover {
            background-color: #218838;
        }

        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: var(--border-radius);
        }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-cancel {
            background-color: #6c757d;
            color: var(--text-white);
            border: none;
            padding: 12px 20px;
            border-radius: var(--border-radius);
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-cancel:hover {
            background-color: #5a6268;
        }

        .coordinates-section {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: var(--border-radius);
            border: 1px solid var(--highlight);
            margin-bottom: 20px;
        }

        .coordinates-section h3 {
            margin-top: 0;
            margin-bottom: 15px;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

    </style>
</head>
<body>
<?php
require_once "../components/header.php";
require_once "../components/sidebar.php";
?>
<div class="dashboard-container">
    <h1 class="dashboard-title">
        Adicionar Novo Fóssil
        <a href="fossils-management.php" class="btn-back-fossils">
            <i class="fas fa-arrow-left"></i> Voltar à Lista
        </a>
    </h1>

    <div class="admin-sections">
        <div class="section">
            <div class="form-container">
                <?php if (!empty($errorMsg)): ?>
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i> <?php echo $errorMsg; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="title">Título/Nome do Fóssil</label>
                            <input type="text" id="title" name="title" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="discovered_by">Descobridor</label>
                            <input type="text" id="discovered_by" name="discovered_by" class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="date_discovered">Data de Descoberta</label>
                            <input type="date" id="date_discovered" name="date_discovered" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="kingdom">Reino</label>
                            <input type="text" id="kingdom" name="kingdom" class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="phylum">Filo</label>
                            <input type="text" id="phylum" name="phylum" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="class">Classe</label>
                            <input type="text" id="class" name="class" class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="order">Ordem</label>
                            <input type="text" id="order" name="order" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="family">Família</label>
                            <input type="text" id="family" name="family" class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="genus">Género</label>
                            <input type="text" id="genus" name="genus" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="species">Espécie</label>
                            <input type="text" id="species" name="species" class="form-control">
                        </div>
                    </div>

                    <div class="coordinates-section">
                        <h3><i class="fas fa-map-marker-alt"></i> Localização do Fóssil</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="latitude">Latitude *</label>
                                <input type="text" id="latitude" name="latitude" class="form-control" placeholder="Ex: 41.1579" required>
                            </div>

                            <div class="form-group">
                                <label for="longitude">Longitude *</label>
                                <input type="text" id="longitude" name="longitude" class="form-control" placeholder="Ex: -8.6291" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <a href="fossils-management.php" class="btn-cancel">
                            <i class="fas fa-times-circle"></i> Cancelar
                        </a>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save"></i> Guardar Fóssil
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>