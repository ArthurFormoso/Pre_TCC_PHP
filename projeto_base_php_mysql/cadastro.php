<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/funcoes.php';
require_once __DIR__ . '/config/conexao.php';

if (!empty($_SESSION['usuario_id'])) {
    header('Location: painel.php');
    exit;
}
?>