<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['username'])) {
    // Redirecionar para a página de login se o usuário não estiver logado
    header("Location: login.php");
    exit();
}

// Obter o nome do usuário da sessão
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autorizador Web - Hospital de Miracema</title>
    <link rel="icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Autorizador HM</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item d-flex align-items-center">
                        <span class="me-3">Olá, <strong><?php echo htmlspecialchars($username); ?></strong></span>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle hidden-arrow" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i data-lucide="bell"></i>
                            <span id="notificationBadge" class="badge rounded-pill badge-notification bg-danger">0</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink" id="notifications">
                            <h6 class="dropdown-header">Notificações</h6>
                            <div id="notificationList"></div>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a href="#" target="_blank" rel="noopener noreferrer" class="nav-link"><i data-lucide="settings"></i></a>
                    </li>
                    <li class="nav-item">
                        <a href="logout.php" class="nav-link"><i data-lucide="power"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<div class="container mt-4">
    <div class="justify-content-between align-items-center">
        
        <!-- Exibir mensagem de importação -->
        <?php if (isset($_SESSION['import_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['import_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['import_message']); ?>
        <?php endif; ?>

        <div class="btn-group group-options mb-3">
            <a href="#" class="btn">
                <i data-lucide="home"></i><br>
                <span>Início</span>
            </a>
            <a href="#" class="btn" data-bs-toggle="modal" data-bs-target="#registerNotificationModal">
                <i data-lucide="megaphone"></i><br>
                <span>Notificar</span>
            </a>
            <a href="#" class="btn" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i data-lucide="upload"></i><br>
                <span>Upload</span>
            </a>
        </div>

        <div class="card">
            <div class="card-header bg-primary text-white">
                Passo 1 (Informações sobre o Beneficiário)
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#searchBeneficiarioModal">Pesquisar Beneficiários</button>
                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#elegibilityBeneficiarioModal">Elegibilidade Beneficiário</button>
                </div>
                <form class="row g-3 align-items-end" id="beneficiaryForm">
                    <div class="col-md-2">
                        <label for="matricula" class="form-label">Matrícula/Nº Cartão</label>
                        <input type="text" class="form-control" id="matricula" name="matricula">
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-success" type="button" id="checkButton"><i data-lucide="check"></i></button>
                    </div>
                    <div class="col-md-3">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome" value="" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label" for="dia">Data de Nascimento</label>
                        <div class="input-group">
                            <select class="form-select" id="dia" name="dia" aria-label="Dia">
                                <option value="">Dia</option>
                                <!-- Opções de dia -->
                            </select>
                            <select class="form-select" id="mes" name="mes" onchange="updateDays()" aria-label="Mês">
                                <option value="1">Janeiro</option>
                                <option value="2">Fevereiro</option>
                                <option value="3">Março</option>
                                <option value="4">Abril</option>
                                <option value="5">Maio</option>
                                <option value="6">Junho</option>
                                <option value="7">Julho</option>
                                <option value="8" selected>Agosto</option>
                                <option value="9">Setembro</option>
                                <option value="10">Outubro</option>
                                <option value="11">Novembro</option>
                                <option value="12">Dezembro</option>
                            </select>
                            <select class="form-select" id="ano" name="ano" onchange="updateDays()" aria-label="Ano">
                                <?php
                                $currentYear = date('Y');
                                for ($year = 1943; $year <= $currentYear; $year++) {
                                    echo "<option value=\"$year\">$year</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <label for="tipoGuia" class="form-label">Tipo de Guia</label>
                        <select id="tipoGuia" name="tipoGuia" class="form-select">
                            <option value="" selected></option>
                            <option value="CONSULTAS">CONSULTAS</option>
                            <option value="SADT">SADT</option>
                            <option value="INTERNAÇÃO">INTERNAÇÃO</option>
                            <option value="OPME">OPME</option>
                            <option value="PRORROGAÇÃO/PROCEDIMENTO">PRORROGAÇÃO/PROCEDIMENTO</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary">Próximo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de Upload (admin) -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload de Arquivo Excel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="processa.php" enctype="multipart/form-data">
                        <label for="upload">Arquivo</label>
                        <input type="file" name="arquivo" id="upload"><br><br>
                        <input type="submit" value="Enviar">
                    </form>                       
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Buscar Beneficiário -->
    <div class="modal fade" id="searchBeneficiarioModal" tabindex="-1" aria-labelledby="searchBeneficiarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="searchBeneficiarioModalLabel">Pesquisar Beneficiário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Conteúdo da pesquisa de beneficiário -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Elegibilidade do Beneficiário -->
    <div class="modal fade" id="elegibilityBeneficiarioModal" tabindex="-1" aria-labelledby="elegibilityBeneficiarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="elegibilityBeneficiarioModalLabel">Elegibilidade do Beneficiário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Conteúdo da elegibilidade do beneficiário -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Exibir Notificações -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel"><?php echo $tituloNotification; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- O conteúdo da notificação será inserido aqui -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Cadastrar Notificações (Admin) -->
    <div class="modal fade" id="registerNotificationModal" tabindex="-1" aria-labelledby="registerNotificationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="registerNotificationModalLabel">Cadastrar Notificação</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="insert_notification.php">
                        <div class="mb-3">
                            <label for="notificationTitle" class="form-label">Título</label>
                            <input type="text" class="form-control" id="notificationTitle" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="notificationMessage" class="form-label">Mensagem</label>
                            <textarea class="form-control" id="notificationMessage" name="message" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>

</body>
</html>
