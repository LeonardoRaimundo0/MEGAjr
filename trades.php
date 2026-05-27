<?php
session_start();
require('ligacao.php');

$estado = 0;

$sql_id ="SELECT * FROM Trocas WHERE id_utilizador = ? AND estado_da_troca != ? ";
$stmt = $con->prepare($sql_id);
$stmt->bind_param("ii", $_SESSION['id_utilizador'], $estado);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();

$sql_item_troca ="SELECT * FROM Items WHERE id_item = ? ";
$stmt_item_troca = $con->prepare($sql_item_troca);
$stmt_item_troca->bind_param("i", $row['id_item']);
$stmt_item_troca->execute();
$res_item_troca = $stmt_item_troca->get_result();

$sql_propostas ="SELECT * FROM Propostas WHERE id_troca = ? ";
$stmt_propostas = $con->prepare($sql_propostas);
$stmt_propostas->bind_param("i", $row['id_troca']);
$stmt_propostas->execute();
$res_propostas = $stmt_propostas->get_result();
$row_propostas = $res_propostas->fetch_assoc();

$sql_item_proposta ="SELECT * FROM Items WHERE id_item = ? ";
$stmt_item_proposta = $con->prepare($sql_item_proposta);
$stmt_item_proposta->bind_param("i", $row_propostas['id_item']);
$stmt_item_proposta->execute();
$res_item_proposta = $stmt_item_proposta->get_result();

if(isset($_POST['Remove'])){
    $sql = "DELETE FROM Trocas WHERE id_troca = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $_POST['id_troca']);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success'] = 'Troca removida com sucesso.';
    header('Location: trades.php');
    exit;
}

if(isset($_POST['Refuse'])){
    $sql = "DELETE FROM Propostas WHERE id_proposta = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $_POST['id_proposta']);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success'] = 'Proposta recusada.';
    header('Location: trades.php');
    exit;
}

if(isset($_POST['Accept'])){

    $sql = "UPDATE Trocas SET proposta_final = ?, estado_da_troca = ? WHERE id_troca = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("iii",$_POST['id_proposta'], $estado, $_POST['id_troca']);
    $stmt->execute();
    $stmt->close();

    $sql = "DELETE FROM Propostas WHERE id_proposta != ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $_POST['id_proposta']);
    $stmt->execute();
    $stmt->close();

    $_SESSION['success'] = 'Proposta aceita.';
    header('Location: trades.php');
    exit;
}

?>
<!doctype html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DBT - Anúncio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header class="site-header">
        <nav class="navbar">
            <a class="brand" href="index.php" aria-label="Ir para a página inicial">
                <span class="brand-mark">◉</span>
                <span class="brand-text">DBT</span>
            </a>
            <div class="nav-center">
                <div class="nav-links">
                    <a class="nav-link " href="indexSignedIn.php">Início</a>
                    <a class="nav-link " href="tradeMarketSignedIn.php">Explorar trocas</a>
                    <a class="nav-link " href="personalInfo.php">Informação pessoal</a>
                    <a class="nav-link " href="inventory.php">Inventário</a>
                    <a class="nav-link " href="trades.php">Trocas</a>
                </div>
            </div>
            <div class="nav-right">
                <div class="user-menu">
                    <button type="button" class="user-button" aria-haspopup="true" aria-expanded="false">
                        <img src="images/user-icon.png" alt="Foto de perfil" class="user-avatar">
                        <span class="user-name"></span>
                    </button>
                    <div class="user-dropdown" role="menu">
                        <a class='btn btn-outline' href='index.php'>Sign Out</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main class="page-shell">
        <section class="dashboard">
            <div class="main-panel">
                <div class="section-card panel-card">
                    <h1 class="page-title">Trocas</h1>
                    <p class="section-subtitle">Os teus anúncios atuais e as propostas recebidas aparecem aqui.</p>
                    <?php if ($_SESSION['success']){ ?>
                        <div class="alert"><?php echo $_SESSION['success']; ?></div>
                        <?php unset($_SESSION['success']); ?>
                    <?php } ?>
                    <div class="list-stack">
                        <?php if ($res->num_rows == 0){ ?>
                            <div class="list-card">
                                <p class="muted">Ainda não publicaste trocas.</p>
                            </div>
                        <?php } ?>
                        <?php while ($row_item_troca = $res_item_troca->fetch_assoc()) { ?>
                            <div class="list-card">
                                <div class="list-item">
                                    <div>
                                        <strong class="list-item-title"><?php echo $row_item_troca['nome_item']; ?></strong>
                                        <div class="list-item-meta">Publicado para troca atualmente</div>
                                    </div>
                                    <form method="post" data-confirm-remove="Remover este anúncio de troca?">
                                        <input type="hidden" name="id_troca" value="<?php echo (int)$row['id_troca']; ?>">
                                        <button class="btn-danger" type="submit" name="Remove">Remover</button>
                                    </form>
                                </div>
                                <?php if ($res_propostas->num_rows > 0){ ?>
                                    <?php while ($row_item_proposta = $res_item_proposta->fetch_assoc()) { ?>
                                        <div class="list-item sub-item">
                                            <div>
                                                <strong class="small-meta">Proposta recebida</strong>
                                                <div class="list-item-meta"><?php echo $row_item_proposta['nome_item'] ?></div>
                                                <form method="post" data-confirm-remove="Aceitar proposta?">
                                                    <input type="hidden" name="id_troca" value="<?php echo (int)$row['id_troca']; ?>">
                                                    <input type="hidden" name="id_proposta" value="<?php echo (int)$row_propostas['id_proposta']; ?>">
                                                    <button class="btn-danger" type="submit" name="Accept">Aceitar</button>
                                                </form>
                                                <form method="post" data-confirm-remove="Recusar proposta?">
                                                    <input type="hidden" name="id_proposta" value="<?php echo (int)$row_propostas['id_proposta']; ?>">
                                                    <button class="btn-danger" type="submit" name="Refuse">Recusar</button>
                                                </form>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php }else{ ?>
                                    <div class="list-item sub-item">
                                        <div class="list-item-meta">Sem propostas recebidas ainda.</div>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <aside class="profile-sidebar">
                <div class="sidebar-card">
                    <h3>Perfil</h3>
                    <nav>
                        <a class="sidebar-link" href="personalInfo.php">Informação pessoal</a>
                        <a class="sidebar-link" href="inventory.php">Inventário</a>
                        <a class="sidebar-link active" href="trades.php">Trocas</a>
                    </nav>
                </div>
            </aside>
        </section>
    </main>
    <footer class="site-footer">
        <div class="footer-links">
            <a class="nav-link " href="indexSignedIn.php">Início</a>
            <a class="nav-link " href="tradeMarketSignedIn.php">Explorar trocas</a>
            <a class="nav-link " href="personalInfo.php">Informação pessoal</a>
            <a class="nav-link " href="inventory.php">Inventário</a>
            <a class="nav-link " href="trades.php">Trocas</a>
        </div>
        <p>© 2026 DBT — Don't Buy, Trade</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>