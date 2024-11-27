<?php
include("../model/connect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['campo_nome'];
    $email = $_POST['campo_email'];
    $nascimento = $_POST['campo_nascimento'];
    $senha = md5($_POST['campo_senha']);
    
    // Manipulação do arquivo de foto
    $arquivo = $_FILES['arquivo'];
    $file_name = $_FILES['arquivo']['name'];
    $extensao = pathinfo($file_name, PATHINFO_EXTENSION);
    $nomeCompleto = md5(uniqid()) . "." . $extensao;
    $destino = "../view/imgs/" . $nomeCompleto;
    move_uploaded_file($arquivo['tmp_name'], $destino);

    // Inserir dados no banco
    $sql = "INSERT INTO usuarios (Usuario_Nome, Usuario_Email, Usuario_Nascimento, Usuario_Senha, Usuario_Foto) VALUES ('$nome', '$email', '$nascimento', '$senha', '$nomeCompleto')";
    if (mysqli_query($conexao, $sql)) {
        header("Location: ../view/home.php");
    } else {
        echo "Erro: " . $sql . "<br>" . mysqli_error($conexao);
    }
}
?>
