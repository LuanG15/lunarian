<?php
session_start(); // Inicia a sessão
include("../model/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitizando os dados
    $email = mysqli_real_escape_string($conexao, trim($_POST["email"]));
    $senha = mysqli_real_escape_string($conexao, md5($_POST["senha"])); // Certifique-se de usar o mesmo algoritmo no banco

    // Consulta no banco de dados
    $query = mysqli_query($conexao, "SELECT * FROM usuarios WHERE Usuario_Email = '$email' AND Usuario_Senha = '$senha'");

    if (mysqli_num_rows($query) == 1) {
        // Usuário encontrado
        $usuario = mysqli_fetch_assoc($query);

        // Salva informações do usuário na sessão
        $_SESSION['nome_usuario'] = $usuario['Usuario_Nome']; // Nome do usuário
        $_SESSION['email_usuario'] = $usuario['Usuario_Email']; // Email do usuário
        $_SESSION['foto_usuario'] = $usuario['Usuario_Foto']; // Foto do usuário (nome do arquivo)

        // Redireciona para a home
        header("Location: ../view/home.php");
        exit();
    } else {
        // Login falhou
        header("Location: ../view/login.php?error=1");
        exit();
    }
}
?>
