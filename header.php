<header id="top-header">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>D' Barber Shop</title>
    <link rel="stylesheet" href="style.css">
    
    <div class="header-container">
        <h1 class="logo">D' Barber Shop</h1>
        <div class="contatos">
            <span>WhatsApp:</span>
            <a href="https://wa.me/5583988660079" target="_blank">
                <img src="img/zap.jpg" alt="WhatsApp">
            </a>

            <span>Instagram:</span>
            <a href="https://instagram.com/daniel_andersonss" target="_blank">
                <img src="img/insta.jpg" alt="Instagram">
            </a>

            <span>Ou Ligue:</span>
            <a href="tel:+5583988660079">
                 +55(83)98866-0079
            </a>
        </div>
    </div>
</header>
<nav>
    <?php $pagina_atual = basename($_SERVER['PHP_SELF']); ?>
    <ul>
        <li><a href="index.php" class="<?= $pagina_atual == 'index.php' ? 'ativo' : ''; ?>">Home</a></li>
        <li><a href="localizacao.php" class="<?= $pagina_atual == 'localizacao.php' ? 'ativo' : ''; ?>">Localização</a></li>
        <li><a href="avaliacoes.php" class="<?= $pagina_atual == 'avaliacoes.php' ? 'ativo' : ''; ?>">Avaliações</a></li>
        <li><a href="comentarios.php" class="<?= $pagina_atual == 'comentarios.php' ? 'ativo' : ''; ?>">Comentários & Perguntas</a></li>      
    </ul>
</nav>
<?php if (basename($_SERVER['PHP_SELF']) != "index.php") : ?>
    
<?php endif; ?>
