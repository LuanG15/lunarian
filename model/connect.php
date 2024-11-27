<?php
$host = 'localhost';
$user = 'root';
$password = ''; // sua senha
$database = 'ecommerce_luminarias';

// Criar conexão
$conexao = new mysqli($host, $user, $password, $database);

// Verificar conexão
if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}
?>
