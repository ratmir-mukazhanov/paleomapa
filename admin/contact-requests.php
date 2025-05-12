<?php
session_start();

// Verificar se o utilizador está autenticado e é um admin
if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: ../login/login.php");
    exit();
}

require_once "../services/dashboard_service.php";
$dashboardService = new DashboardService();

// Processar a ação para marcar como processado, se enviado
// Processar ações de marcar ou desmarcar
if (isset($_POST['mark_processed']) && isset($_POST['contact_id'])) {
    $contactId = $_POST['contact_id'];
    $dashboardService->markContactAsProcessed($contactId);
    header("Location: contact-requests.php?success=1");
    exit();
}

if (isset($_POST['unmark_processed']) && isset($_POST['contact_id'])) {
    $contactId = $_POST['contact_id'];
    $dashboardService->unmarkContactAsProcessed($contactId);
    header("Location: contact-requests.php?unmarked=1");
    exit();
}

// Buscar todos os pedidos de contato
$contactRequests = $dashboardService->getAllContactRequests();
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos de Contacto - Paleomapa</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/cores.css">
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--background-color);
            margin: 0;
            padding: 0;
        }

        .dashboard-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .btn-back-dashboard {
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

        .btn-back-dashboard:hover {
            background-color: var(--primary-color);
            color: var(--text-white);
        }

        .contact-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: var(--text-white);
            box-shadow: var(--shadow);
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .contact-table th, .contact-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--highlight);
        }

        .contact-table th {
            background-color: var(--primary-light);
            text-align: left;
            color: var(--text-white);
        }

        .status-pending {
            color: var(--error-color);
            font-weight: bold;
        }

        .status-processed {
            color: var(--success-color);
            font-weight: bold;
        }

        .action-btn {
            background-color: var(--primary-color);
            color: var(--text-white);
            border: none;
            padding: 8px 12px;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
        }

        .action-btn:hover {
            background-color: var(--primary-dark);
        }

        .action-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .success-message {
            background-color: #d4edda;
            color: var(--success-color);
            padding: 10px 15px;
            margin: 20px 0;
            border-radius: var(--border-radius);
        }

        .message-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: var(--text-white);
            padding: 25px 30px;
            border-radius: var(--border-radius);
            max-width: 600px;
            width: 90%;
            box-shadow: var(--shadow);
            animation: fadeIn 0.3s ease-in-out;
            position: relative;
        }

        .modal-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            border-bottom: 1px solid var(--highlight);
            padding-bottom: 10px;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.3rem;
            color: var(--primary-color);
        }

        .modal-body {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .modal-field label {
            font-weight: 600;
            color: var(--text-black);
            margin-bottom: 5px;
            display: block;
            font-size: 0.95rem;
        }

        .modal-subject,
        .modal-message {
            background-color: #f8f9fa;
            border: 1px solid var(--highlight);
            border-radius: var(--border-radius);
            padding: 12px;
            font-size: 0.95rem;
            color: var(--text-black);
            white-space: pre-line;
        }

        .modal-footer {
            text-align: right;
            margin-top: 25px;
        }

        .modal-footer .action-btn {
            background-color: var(--accent-color);
            border: none;
            font-size: 0.9rem;
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 22px;
            font-weight: bold;
            color: var(--text-white);
            cursor: pointer;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
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
        Pedidos de Contacto
        <a href="dashboard.php" class="btn-back-dashboard">
            <i class="fas fa-arrow-left"></i> Voltar à Dashboard
        </a>
    </h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="success-message">
            <i class="fas fa-check-circle"></i> Pedido de contato atualizado com sucesso!
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['unmarked'])): ?>
        <div class="success-message">
            <i class="fas fa-undo"></i> Pedido desmarcado como processado.
        </div>
    <?php endif; ?>

    <div class="admin-sections">
        <div class="section">
            <h2><i class="fas fa-envelope"></i> Lista de Pedidos</h2>

            <?php if (empty($contactRequests)): ?>
                <p>Não existem pedidos de contacto.</p>
            <?php else: ?>
                <table class="contact-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Data</th>
                        <th>Estado</th>
                        <th>Ver Pedido</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($contactRequests as $request): ?>
                        <tr>
                            <td><?php echo $request['id']; ?></td>
                            <td><?php echo $request['name']; ?></td>
                            <td><?php echo $request['email']; ?></td>
                            <td><?php echo date('d/m/Y H:i', strtotime($request['submitted_at'])); ?></td>
                            <td>
                                <?php if ($request['is_processed'] == 't'): ?>
                                    <span class="status-processed">Processado</span>
                                <?php else: ?>
                                    <span class="status-pending">Pendente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="#" class="view-message"
                                   data-subject="<?php echo $request['subject']; ?>"
                                   data-message="<?php echo $request['message']; ?>">
                                    <i class="fas fa-eye"></i> Ver Pedido
                                </a>
                            </td>
                            <td>
                                <?php if ($request['is_processed'] != 't'): ?>
                                    <form method="post">
                                        <input type="hidden" name="contact_id" value="<?php echo $request['id']; ?>">
                                        <button type="submit" name="mark_processed" class="action-btn">
                                            <i class="fas fa-check"></i> Marcar como processado
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form method="post">
                                        <input type="hidden" name="contact_id" value="<?php echo $request['id']; ?>">
                                        <button type="submit" name="unmark_processed" class="action-btn" style="background-color: var(--error-color);">
                                            <i class="fas fa-undo"></i> Desmarcar
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para exibir pedido completo -->
<div id="messageModal" class="message-modal">
    <div class="modal-content">
        <span class="close-modal" title="Fechar">&times;</span>
        <div class="modal-header">
            <i class="fas fa-envelope-open-text fa-lg"></i>
            <h3>Detalhes do Pedido</h3>
        </div>
        <div class="modal-body">
            <div class="modal-field">
                <label>Assunto</label>
                <div class="modal-subject" id="modalSubject"></div>
            </div>
            <div class="modal-field">
                <label>Mensagem</label>
                <div class="modal-message" id="modalMessage"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-btn close-modal">
                <i class="fas fa-times-circle"></i> Fechar
            </button>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    $(document).ready(function() {
        // Abrir modal com os dados completos
        $('.view-message').click(function(e) {
            e.preventDefault();
            var subject = $(this).data('subject');
            var message = $(this).data('message');
            $('#modalSubject').text(subject);
            $('#modalMessage').text(message);
            $('#messageModal').css('display', 'flex');
        });

        // Fechar modal
        $('.close-modal').click(function() {
            $('#messageModal').hide();
        });

        // Fechar modal ao clicar fora
        $(window).click(function(e) {
            if ($(e.target).is('#messageModal')) {
                $('#messageModal').hide();
            }
        });
    });
</script>
</body>
</html>