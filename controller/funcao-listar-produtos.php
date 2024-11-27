<?php
include("../model/connect.php");

// Query para selecionar todos os produtos da tabela luminarias sem limite
$query_completa = mysqli_query($conexao, "SELECT * FROM luminarias");

// Verifique se a consulta foi bem-sucedida
if (!$query_completa) {
    die('Erro na consulta: ' . mysqli_error($conexao));
}
?>
