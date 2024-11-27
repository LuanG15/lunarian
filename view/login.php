<?php
session_start(); // Inicia a sessão

// Verifica se o usuário já está logado
if (isset($_SESSION['nome_usuario'])) {
    header("Location: home.php"); // Redireciona para a home se já estiver logado
    exit();
}

// Verifica se há mensagem de erro no login
$erro_login = isset($_GET['error']) ? "Email ou senha inválidos!" : null;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../view/css/style.css" type="text/css">
</head>
<body class="bg-dark text-light">


    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="../controller/funcao-login.php" method="POST" class="bg-secondary p-4 rounded">
                    <h2 class="mb-4 text-center">Login</h2>
                    <?php if ($erro_login): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $erro_login; ?>
                        </div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control bg-dark text-light border-light" id="email" placeholder="Seu email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Senha</label>
                        <input type="password" name="senha" class="form-control bg-dark text-light border-light" id="password" placeholder="Sua senha" required>
                    </div>
                    <div class="container mt-3 d-flex justify-content-between">
                        <a href="cadastro.php" class="text-warning">Não tem uma conta? Cadastre-se</a>
                        <button type="submit" class="btn btn-warning">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include("../blades/footer.php"); ?>
</body>
</html>
