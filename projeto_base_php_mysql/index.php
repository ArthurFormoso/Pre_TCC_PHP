<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/funcoes.php';
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Projeto Base</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="cabecalho">
    <div class="navegacao">
        <a class="marca" href="index.php">Projeto<span>Base</span></a>
        <nav class="links" aria-label="Navegação principal">
            <a href="index.php">Início</a>
            <?php if (!empty($_SESSION['usuario_id'])): ?>
                <a href="painel.php">Minha área</a>
                <a href="logout.php">Sair</a>
            <?php else: ?>
                <a href="login.php">Entrar</a>
                <a href="cadastro.php">Cadastrar</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main>
    <section class="cartao cartao-largo">
        <span class="etiqueta">PHP + MySQL</span>
        <h1>Uma base simples para diferentes projetos.</h1>
        <p>Estrutura inicial com cadastro, login e banco de dados, feita com PHP, HTML e CSS — sem JavaScript.</p>
        <div class="acoes">
            <?php if (!empty($_SESSION['usuario_id'])): ?>
                <a class="botao" href="painel.php">Acessar minha área →</a>
            <?php else: ?>
                <a class="botao" href="cadastro.php">Criar conta →</a>
                <a class="botao botao-secundario" href="login.php">Já tenho cadastro</a>
            <?php endif; ?>
        </div>
    </section>
</main>
<footer class="rodape"><p>Projeto educacional · Personalize o conteúdo e as cores conforme o tema.</p></footer>
</body>
</html>
