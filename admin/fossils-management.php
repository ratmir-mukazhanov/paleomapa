<?php
session_start();

// Verificar se o utilizador está autenticado e é um admin
if (empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header("Location: ../login/login.php");
    exit();
}

require_once "../services/dashboard_service.php";
$dashboardService = new DashboardService();

// Parâmetros de paginação
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 20; // 20 registos por página
if ($page < 1) $page = 1;

// Termo de pesquisa
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

// Se for uma requisição de pesquisa, ajustamos os parâmetros
if (!empty($searchTerm)) {
    $fossils = $dashboardService->searchFossils($searchTerm, $page, $limit);
    $totalFossils = $dashboardService->countSearchResults($searchTerm);
} else {
    // Busca normal com paginação
    $fossils = $dashboardService->getAllFossils($page, $limit);
    $totalFossils = $dashboardService->countFossils();
}

$totalPages = ceil($totalFossils / $limit);

// Garantir que a página atual não seja maior que o total de páginas
if ($page > $totalPages && $totalPages > 0) {
    header("Location: ?page={$totalPages}" . (!empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''));
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestão de Fósseis - Paleomapa</title>
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

        .fossils-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: var(--text-white);
            box-shadow: var(--shadow);
            border-radius: var(--border-radius);
            overflow: hidden;
        }

        .fossils-table th, .fossils-table td {
            padding: 12px 15px;
            border-bottom: 1px solid var(--highlight);
            vertical-align: middle;
        }

        .fossils-table th {
            background-color: var(--primary-light);
            text-align: left;
            color: var(--text-white);
        }

        .action-btn {
            background-color: var(--primary-color);
            color: var(--text-white);
            border: none;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
        }

        .action-btn:hover {
            background-color: var(--primary-dark);
        }

        .action-btn.delete {
            background-color: var(--error-color);
        }

        .action-btn.delete:hover {
            background-color: #d32f2f;
        }

        .action-btn.view {
            background-color: var(--accent-color);
        }

        .action-btn.view:hover {
            background-color: #1976d2;
        }

        .success-message {
            background-color: #d4edda;
            color: var(--success-color);
            padding: 10px 15px;
            margin: 20px 0;
            border-radius: var(--border-radius);
        }

        .fossil-modal {
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
            max-width: 700px;
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
            color: white;
            cursor: pointer;
        }

        .modal-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .modal-field {
            margin-bottom: 10px;
        }

        .modal-field label {
            font-weight: 600;
            color: var(--text-black);
            margin-bottom: 5px;
            display: block;
            font-size: 0.95rem;
        }

        .modal-field-value {
            background-color: #f8f9fa;
            border: 1px solid var(--highlight);
            border-radius: var(--border-radius);
            padding: 12px;
            font-size: 0.95rem;
            color: var(--text-black);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        .table-actions {
            display: flex;
            gap: 5px;
            align-items: center;
            justify-content: flex-start;
            min-height: 40px;
        }

        .add-fossil-btn {
            background-color: var(--success-color);
            color: var(--text-white);
            border: none;
            padding: 8px 15px;
            border-radius: var(--border-radius);
            cursor: pointer;
            transition: var(--transition);
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .add-fossil-btn:hover {
            background-color: #218838;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 5px;
        }

        .pagination a, .pagination span {
            padding: 8px 14px;
            background-color: var(--text-white);
            border-radius: var(--border-radius);
            border: 1px solid var(--highlight);
            text-decoration: none;
            color: var(--primary-color);
            transition: var(--transition);
        }

        .pagination a:hover {
            background-color: var(--primary-light);
            color: var(--text-white);
        }

        .pagination span {
            background-color: var(--primary-color);
            color: var(--text-white);
        }

        .search-container {
            margin-bottom: 20px;
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex-grow: 1;
            padding: 10px;
            border: 1px solid var(--highlight);
            border-radius: var(--border-radius);
            font-size: 1rem;
        }

        .search-btn {
            background-color: var(--primary-color);
            color: var(--text-white);
            border: none;
            padding: 0 15px;
            border-radius: var(--border-radius);
            cursor: pointer;
        }

        .search-btn:hover {
            background-color: var(--primary-dark);
        }

        /* Estilos adicionais para o modal de exclusão */
        #deleteModal .modal-content {
            border-top: 4px solid var(--error-color);
            animation: shakeModal 0.4s ease-in-out;
        }

        @keyframes shakeModal {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70% { transform: translateX(-5px); }
            20%, 40%, 60% { transform: translateX(5px); }
            80% { transform: translateX(3px); }
            90% { transform: translateX(-3px); }
        }

        /* Melhorando a interação dos botões */
        #deleteModal .action-btn {
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 140px;
            font-weight: 500;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        #deleteModal .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        #deleteModal .action-btn.delete:hover {
            background: linear-gradient(to right, #d32f2f, #b71c1c);
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
        Gestão de Fósseis
        <a href="dashboard.php" class="btn-back-dashboard">
            <i class="fas fa-arrow-left"></i> Voltar à Dashboard
        </a>
    </h1>

    <?php if (isset($_GET['success'])): ?>
        <div class="success-message">
            <i class="fas fa-check-circle"></i> Operação realizada com sucesso!
        </div>
    <?php endif; ?>

    <div class="admin-sections">
        <div class="section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2><i class="fas fa-bone"></i> Lista de Fósseis</h2>
                <button class="add-fossil-btn" onclick="location.href='add-fossil.php';">
                    <i class="fas fa-plus-circle"></i> Adicionar Fóssil
                </button>
            </div>

            <form class="search-container" method="GET" action="">
                <input type="text" id="searchFossil" name="search" class="search-input"
                       placeholder="Pesquisar por título, reino, descobridor..."
                       value="<?php echo htmlspecialchars($searchTerm); ?>">
                <button type="submit" class="search-btn"><i class="fas fa-search"></i></button>
                <?php if(!empty($searchTerm)): ?>
                    <a href="fossils-management.php" class="search-btn"
                       style="background-color: #6c757d; display: flex; align-items: center; justify-content: center;"
                       title="Limpar pesquisa">
                        <i class="fas fa-times"></i>
                    </a>
                <?php endif; ?>
            </form>

            <?php if (empty($fossils)): ?>
                <p>Não existem fósseis registados.</p>
            <?php else: ?>
                <table class="fossils-table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descobridor</th>
                        <th>Data Descoberta</th>
                        <th>Reino</th>
                        <th>Género</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($fossils as $fossil): ?>
                        <tr>
                            <td><?php echo $fossil['id']; ?></td>
                            <td><?php echo $fossil['title']; ?></td>
                            <td><?php echo $fossil['discovered_by']; ?></td>
                            <td><?php echo !empty($fossil['date_discovered']) ? date('d/m/Y', strtotime($fossil['date_discovered'])) : 'N/A'; ?></td>
                            <td><?php echo $fossil['kingdom']; ?></td>
                            <td><?php echo $fossil['genus']; ?></td>
                            <td class="table-actions">
                                <button class="action-btn view view-fossil"
                                        data-id="<?php echo $fossil['id']; ?>"
                                        data-title="<?php echo htmlspecialchars($fossil['title']); ?>"
                                        data-discovered-by="<?php echo htmlspecialchars($fossil['discovered_by']); ?>"
                                        data-date-discovered="<?php echo $fossil['date_discovered']; ?>"
                                        data-kingdom="<?php echo htmlspecialchars($fossil['kingdom']); ?>"
                                        data-phylum="<?php echo htmlspecialchars($fossil['phylum']); ?>"
                                        data-class="<?php echo htmlspecialchars($fossil['class']); ?>"
                                        data-order="<?php echo htmlspecialchars($fossil['order']); ?>"
                                        data-family="<?php echo htmlspecialchars($fossil['family']); ?>"
                                        data-genus="<?php echo htmlspecialchars($fossil['genus']); ?>"
                                        data-species="<?php echo htmlspecialchars($fossil['species']); ?>"
                                        data-source="<?php echo htmlspecialchars($fossil['source'] ?? ''); ?>">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn" onclick="location.href='edit-fossil.php?id=<?php echo $fossil['id']; ?>';">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete delete-fossil" data-id="<?php echo $fossil['id']; ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            <!-- Paginação -->
            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php if ($page > 1): ?>
                        <a href="?page=1<?php echo !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''; ?>" title="Primeira página">&laquo;&laquo;</a>
                        <a href="?page=<?php echo $page-1; ?><?php echo !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''; ?>" title="Página anterior">&laquo;</a>
                    <?php endif; ?>

                    <?php
                    // Mostrar 5 páginas: 2 antes e 2 depois da atual
                    $startPage = max(1, $page - 2);
                    $endPage = min($totalPages, $page + 2);

                    // Garantir que sempre mostremos 5 links (se possível)
                    if ($endPage - $startPage < 4) {
                        if ($startPage == 1) {
                            $endPage = min($totalPages, $startPage + 4);
                        } elseif ($endPage == $totalPages) {
                            $startPage = max(1, $endPage - 4);
                        }
                    }

                    for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span><?php echo $i; ?></span>
                        <?php else: ?>
                            <a href="?page=<?php echo $i; ?><?php echo !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''; ?>"><?php echo $i; ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="?page=<?php echo $page+1; ?><?php echo !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''; ?>" title="Próxima página">&raquo;</a>
                        <a href="?page=<?php echo $totalPages; ?><?php echo !empty($searchTerm) ? '&search=' . urlencode($searchTerm) : ''; ?>" title="Última página">&raquo;&raquo;</a>
                    <?php endif; ?>
                </div>
                <div style="text-align: center; margin-top: 10px; font-size: 0.9rem; color: #666;">
                    Mostrando <?php echo count($fossils); ?> de <?php echo $totalFossils; ?> registos
                    <?php if (!empty($searchTerm)): ?>
                        para a pesquisa "<?php echo htmlspecialchars($searchTerm); ?>"
                    <?php endif; ?>
                <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para visualizar detalhes do fóssil -->
<div id="fossilModal" class="fossil-modal">
    <div class="modal-content">
        <span class="close-modal" title="Fechar">&times;</span>
        <div class="modal-header">
            <i class="fas fa-bone fa-lg"></i>
            <h3>Detalhes do Fóssil</h3>
        </div>
        <div class="modal-body">
            <div class="modal-grid">
                <div class="modal-field">
                    <label>Título</label>
                    <div class="modal-field-value" id="modalTitle"></div>
                </div>
                <div class="modal-field">
                    <label>Descoberto por</label>
                    <div class="modal-field-value" id="modalDiscoveredBy"></div>
                </div>
                <div class="modal-field">
                    <label>Data de Descoberta</label>
                    <div class="modal-field-value" id="modalDataDiscovered"></div>
                </div>
                <div class="modal-field">
                    <label>Reino</label>
                    <div class="modal-field-value" id="modalKingdom"></div>
                </div>
                <div class="modal-field">
                    <label>Filo</label>
                    <div class="modal-field-value" id="modalPhylum"></div>
                </div>
                <div class="modal-field">
                    <label>Classe</label>
                    <div class="modal-field-value" id="modalClass"></div>
                </div>
                <div class="modal-field">
                    <label>Ordem</label>
                    <div class="modal-field-value" id="modalOrder"></div>
                </div>
                <div class="modal-field">
                    <label>Família</label>
                    <div class="modal-field-value" id="modalFamily"></div>
                </div>
                <div class="modal-field">
                    <label>Género</label>
                    <div class="modal-field-value" id="modalGenus"></div>
                </div>
                <div class="modal-field">
                    <label>Espécie</label>
                    <div class="modal-field-value" id="modalSpecies"></div>
                </div>
                <div class="modal-field">
                    <label>Fonte</label>
                    <div class="modal-field-value" id="modalSource"></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="action-btn close-modal">
                <i class="fas fa-times-circle"></i>
            </button>
        </div>
    </div>
</div>

<!-- Confirmação de exclusão -->
<div id="deleteModal" class="fossil-modal">
    <div class="modal-content" style="max-width: 500px;">
        <div class="modal-header" style="border-bottom: 2px solid #ffcdd2; margin-bottom: 20px; padding-bottom: 15px;">
            <i class="fas fa-trash-alt fa-lg" style="color: var(--error-color); font-size: 24px;"></i>
            <h3 style="color: var(--error-color);">Confirmar Exclusão</h3>
        </div>
        <div class="modal-body" style="text-align: center; padding: 10px 20px 20px;">
            <i class="fas fa-exclamation-triangle" style="font-size: 48px; color: #f44336; margin-bottom: 20px;"></i>
            <p style="font-size: 1.1rem; margin-bottom: 20px;">
                Tem certeza que deseja excluir este fóssil?<br>
                <span style="font-size: 0.9rem; color: #757575; display: block; margin-top: 8px;">
                    Esta ação não pode ser desfeita.
                </span>
            </p>
        </div>
        <div class="modal-footer" style="display: flex; justify-content: center; gap: 20px; margin-top: 15px; padding-top: 20px; border-top: 1px solid #f1f1f1;">
            <button class="action-btn" onclick="$('#deleteModal').hide();" style="background-color: #f5f5f5; color: #333; border: 1px solid #ddd; padding: 12px 18px;">
                Cancelar
            </button>
            <form id="deleteForm" method="post" action="delete-fossil.php">
                <input type="hidden" id="deleteId" name="fossil_id">
                <button type="submit" class="action-btn delete" style="padding: 12px 18px; background: linear-gradient(to right, #e53935, #c62828);">
                    <i class="fas fa-trash-alt" style="margin-right: 8px;"></i> Excluir Fóssil
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Visualizar fóssil
        $('.view-fossil').click(function() {
            const fossil = $(this).data();

            $('#modalTitle').text(fossil.title || 'N/A');
            $('#modalDiscoveredBy').text(fossil.discoveredBy || 'N/A');
            $('#modalDataDiscovered').text(fossil.dateDiscovered ? formatDate(fossil.dateDiscovered) : 'N/A');
            $('#modalKingdom').text(fossil.kingdom || 'N/A');
            $('#modalPhylum').text(fossil.phylum || 'N/A');
            $('#modalClass').text(fossil.class || 'N/A');
            $('#modalOrder').text(fossil.order || 'N/A');
            $('#modalFamily').text(fossil.family || 'N/A');
            $('#modalGenus').text(fossil.genus || 'N/A');
            $('#modalSpecies').text(fossil.species || 'N/A');
            $('#modalSource').text(fossil.source || 'N/A');

            $('#fossilModal').css('display', 'flex');
        });

        // Confirmar exclusão
        $('.delete-fossil').click(function() {
            const fossilId = $(this).data('id');
            $('#deleteId').val(fossilId);
            $('#deleteModal').css('display', 'flex');
        });

        // Fechar modais
        $('.close-modal').click(function() {
            $('.fossil-modal').hide();
        });

        // Fechar modal ao clicar fora
        $(window).click(function(e) {
            if ($(e.target).hasClass('fossil-modal')) {
                $('.fossil-modal').hide();
            }
        });

        // Função auxiliar para formatação de data
        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-PT');
        }
    });
</script>
</body>
</html>