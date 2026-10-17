<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/funcoes.php';
exigirLogin();
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel | Projeto Base</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="cabecalho">
    <div class="navegacao">
        <a class="marca" href="index.php">Projeto<span>Base</span></a>
        <nav class="links"><a href="index.php">Início</a><a href="logout.php">Sair</a></nav>
    </div>
</header>
<main>
    <section class="cartao cartao-largo">
        <span class="etiqueta">Área restrita</span>
        <h1>Bem-vindo, <?= escapar((string)($_SESSION['usuario_nome'] ?? 'usuário')) ?>.</h1>
        <p>Login realizado. Esta página exige uma sessão válida.</p>
        <div class="detalhes">
            <small>Conta conectada</small>
            <strong><?= escapar((string)($_SESSION['usuario_email'] ?? '')) ?></strong>
        </div>
        <div class="acoes"><a class="botao" href="logout.php">Encerrar sessão</a></div>
    </section>
</main>
<footer class="rodape"><p>Você está em uma página protegida por sessão PHP.</p></footer>
</body>
</html>
