<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/funcoes.php';
require_once __DIR__ . '/config/conexao.php';

if (!empty($_SESSION['usuario_id'])) {
    header('Location: painel.php');
    exit;
}

$erro = '';
$nome = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim((string)($_POST['nome'] ?? ''));
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $senha = (string)($_POST['senha'] ?? '');
    $confirmacao = (string)($_POST['confirmacao'] ?? '');
    $token = $_POST['csrf_token'] ?? null;

    if (!validarCsrf(is_string($token) ? $token : null)) {
        $erro = 'Formulário expirado. Atualize a página e tente novamente.';
    } elseif (mb_strlen($nome) < 2 || mb_strlen($nome) > 100) {
        $erro = 'O nome deve ter entre 2 e 100 caracteres.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) {
        $erro = 'Informe um e-mail válido.';
    } elseif (strlen($senha) < 8) {
        $erro = 'A senha precisa ter pelo menos 8 caracteres.';
    } elseif ($senha !== $confirmacao) {
        $erro = 'As senhas não são iguais.';
    } else {
        try {
            $stmt = $pdo->prepare(
                'INSERT INTO usuarios (nome, email, senha_hash) VALUES (:nome, :email, :senha_hash)'
            );
            $stmt->execute([
                'nome' => $nome,
                'email' => $email,
                'senha_hash' => password_hash($senha, PASSWORD_DEFAULT),
            ]);

            $_SESSION['mensagem_login'] = 'Cadastro concluído. Entre com seu e-mail e senha.';
            header('Location: login.php');
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $erro = 'Este e-mail já está cadastrado.';
            } else {
                error_log($e->getMessage());
                $erro = 'Não foi possível concluir o cadastro. Tente novamente.';
            }
        }
    }
}
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro | Projeto Base</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="cabecalho"><div class="navegacao"><a class="marca" href="index.php">Projeto<span>Base</span></a><nav class="links"><a href="index.php">Início</a><a href="login.php">Entrar</a></nav></div></header>
<main>
    <section class="cartao">
        <span class="etiqueta">Nova conta</span>
        <h1>Cadastre-se</h1>
        <p>Preencha os dados abaixo para criar sua conta.</p>
        <?php if ($erro !== ''): ?><div class="aviso aviso-erro" role="alert"><?= escapar($erro) ?></div><?php endif; ?>
        <form class="formulario" method="post" action="cadastro.php">
            <input type="hidden" name="csrf_token" value="<?= escapar(tokenCsrf()) ?>">
            <div class="campo">
                <label for="nome">Nome</label>
                <input id="nome" name="nome" type="text" maxlength="100" autocomplete="name" required value="<?= escapar($nome) ?>">
            </div>
            <div class="campo">
                <label for="email">E-mail</label>
                <input id="email" name="email" type="email" maxlength="190" autocomplete="email" required value="<?= escapar($email) ?>">
            </div>
            <div class="campo">
                <label for="senha">Senha (mínimo 8 caracteres)</label>
                <input id="senha" name="senha" type="password" minlength="8" autocomplete="new-password" required>
            </div>
            <div class="campo">
                <label for="confirmacao">Confirmar senha</label>
                <input id="confirmacao" name="confirmacao" type="password" minlength="8" autocomplete="new-password" required>
            </div>
            <button class="botao" type="submit">Criar conta</button>
        </form>
        <p>Já tem uma conta? <a class="link-texto" href="login.php">Entrar</a></p>
    </section>
</main>
<footer class="rodape"><p>As senhas são armazenadas com hash, nunca em texto puro.</p></footer>
</body>
</html>
