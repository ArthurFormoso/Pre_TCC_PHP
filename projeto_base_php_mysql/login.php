<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/funcoes.php';
require_once __DIR__ . '/config/conexao.php';

if (!empty($_SESSION['usuario_id'])) {
    header('Location: painel.php');
    exit;
}

$erro = '';
$email = '';
$mensagem = (string)($_SESSION['mensagem_login'] ?? '');
unset($_SESSION['mensagem_login']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $senha = (string)($_POST['senha'] ?? '');
    $token = $_POST['csrf_token'] ?? null;

    if (!validarCsrf(is_string($token) ? $token : null)) {
        $erro = 'Formulário expirado. Atualize a página e tente novamente.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || $senha === '') {
        $erro = 'E-mail ou senha inválidos.';
    } else {
        $stmt = $pdo->prepare(
            'SELECT id, nome, email, senha_hash FROM usuarios WHERE email = :email LIMIT 1'
        );
        $stmt->execute(['email' => $email]);
        $usuario = $stmt->fetch();

        if ($usuario && password_verify($senha, $usuario['senha_hash'])) {
            session_regenerate_id(true);
            $_SESSION['usuario_id'] = (int)$usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_email'] = $usuario['email'];
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header('Location: painel.php');
            exit;
        }

        $erro = 'E-mail ou senha inválidos.';
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Projeto Base</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="cabecalho"><div class="navegacao"><a class="marca" href="index.php">Projeto<span>Base</span></a><nav class="links"><a href="index.php">Início</a><a href="cadastro.php">Cadastrar</a></nav></div></header>
<main>
    <section class="cartao">
        <span class="etiqueta">Acesso de usuário</span>
        <h1>Entrar</h1>
        <p>Informe seu e-mail e senha cadastrados.</p>
        <?php if ($mensagem !== ''): ?><div class="aviso" role="status"><?= escapar($mensagem) ?></div><?php endif; ?>
        <?php if ($erro !== ''): ?><div class="aviso aviso-erro" role="alert"><?= escapar($erro) ?></div><?php endif; ?>
        <form class="formulario" method="post" action="login.php">
            <input type="hidden" name="csrf_token" value="<?= escapar(tokenCsrf()) ?>">
            <div class="campo">
                <label for="email">E-mail</label>
                <input id="email" name="email" type="email" maxlength="190" autocomplete="email" required value="<?= escapar($email) ?>">
            </div>
            <div class="campo">
                <label for="senha">Senha</label>
                <input id="senha" name="senha" type="password" autocomplete="current-password" required>
            </div>
            <button class="botao" type="submit">Entrar</button>
        </form>
        <p>Não tem conta? <a class="link-texto" href="cadastro.php">Cadastre-se</a></p>
    </section>
</main>
<footer class="rodape"><p>A autenticação é validada no servidor com PHP.</p></footer>
</body>
</html>
