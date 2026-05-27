<?php
require_once __DIR__ . '/dbt_bootstrap.php';

if (dbt_is_logged_in() && isset($_GET['logout'])) {
    // handled by bootstrap redirect before output
}

$featuredAds = dbt_seed_market_ads();

?>
<!doctype html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DBT - Inicio</title>
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
                    <a class='btn btn-outline' href='personalInfo.php'>Perfil</a>
                    <a class='btn btn-outline' href='index.php'>Sign Out</a>
                </div>
            </div>
        </div>
    </nav>
</header>
<main class="page-shell">
    <section class="hero">
        <div class="hero-card hero-copy">
            <span class="kicker">Troca de produtos entre utilizadores</span>
            <h1>Compra menos. Troca mais. Usa a DBT para descobrir trocas úteis.</h1>
            <p>Uma plataforma pensada para publicar anúncios, explorar ofertas de outros utilizadores e propor trocas de forma simples, visual e organizada.</p>
            <div class="hero-actions">
                <a class="btn-primary" href="tradeMarket.php">Explorar trocas</a>
                <a class="btn-outline" href="personalInfo.php">Ir para o perfil</a>
            </div>
        </div>
        <div class="hero-card hero-visual">
            <img src="images/dbt_banner.png" alt="Ilustração da plataforma DBT">
        </div>
    </section>

    <section class="stats">
        <article class="hero-card stat">
            <strong>+100</strong>
            <span>Anúncios trocáveis</span>
        </article>
        <article class="hero-card stat">
            <strong>3 passos</strong>
            <span>Publicar, propor e trocar</span>
        </article>
        <article class="hero-card stat">
            <strong>Velocidade</strong>
            <span>É extremamente rápido fazer trocas</span>
        </article>
    </section>

    <section class="section">
        <div class="section-header">
            <div>
                <h2>Como funciona</h2>
                <p class="section-subtitle">O fluxo foi pensado para ser simples e rápido.</p>
            </div>
        </div>
        <div class="steps">
            <article class="hero-card step">
                <div class="step-num">1</div>
                <h3>Cria conta</h3>
                <p class="muted">Regista-te e acede ao teu perfil, inventário e trocas.</p>
            </article>
            <article class="hero-card step">
                <div class="step-num">2</div>
                <h3>Explora anúncios</h3>
                <p class="muted">Consulta a proposta de troca e abre o anúncio com todos os detalhes.</p>
            </article>
            <article class="hero-card step">
                <div class="step-num">3</div>
                <h3>Propõe uma troca</h3>
                <p class="muted">Escolhe um item do inventário e submete a proposta diretamente no anúncio.</p>
            </article>
        </div>
    </section>

    <section class="section">
        <div class="section-header">
            <div>
                <h2>Anúncios em destaque</h2>
                <p class="section-subtitle">Explora os principais anúncios em destaque</p>
            </div>
            <a class="btn-ghost" href="tradeMarket.php">Ver todos</a>
        </div>
        <div class="grid-4">
            <?php foreach ($featuredAds as $ad): ?>
                <article class="trade-card">
                    <img src="<?php echo dbt_e($ad['image']); ?>" alt="<?php echo dbt_e($ad['name']); ?>">
                    <div class="content">
                        <h3><?php echo dbt_e($ad['name']); ?></h3>
                        <p><?php echo dbt_e($ad['description']); ?></p>
                        <a class="btn-outline" href="<?php echo dbt_is_logged_in() ? 'tradeAdvertisement.php?id=' . (int)$ad['id'] : 'SignIn.php?next=' . urlencode('tradeAdvertisement.php?id=' . (int)$ad['id']); ?>">Ver anúncio</a>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="section">
        <div class="section-card panel-card">
            <h2>Conceito da DBT</h2>
            <p class="muted">A DBT foi desenhada como uma loja de trocas entre utilizadores: cada anúncio pertence a alguém, e os outros utilizadores podem propor um item do seu inventário em troca. O resultado é um marketplace leve, visual e fácil de apresentar num trabalho académico.</p>
        </div>
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