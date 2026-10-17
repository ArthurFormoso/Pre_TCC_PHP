<?php
declare(strict_types=1);

/*
 * Configuração padrão do XAMPP.
 * Se seu MySQL tiver outra senha/usuário, altere os valores abaixo.
 */
$host = 'localhost';
$banco = 'projeto_base';
$usuario = 'root';
$senha = '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$banco};charset=utf8mb4",
        $usuario,
        $senha,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $erro) {
    error_log($erro->getMessage());
    http_response_code(500);
    exit('Erro ao conectar ao banco. Confira config/conexao.php e se o MySQL está ligado.');
}
