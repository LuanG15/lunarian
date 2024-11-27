<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../view/css/style.css" type="text/css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-mask-plugin@1.14.16/dist/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#birthdate').mask('00/00/0000');
        });

        function validateDate() {
            const dateInput = document.getElementById('birthdate');
            const dateValue = dateInput.value;
            const regex = /^\d{2}\/\d{2}\/\d{4}$/;
            
            if (!regex.test(dateValue)) {
                alert('Por favor, insira a data no formato dd/mm/aaaa.');
                return false;
            }
            return true;
        }
    </script>
</head>
<body class="bg-dark text-light"> 
    <div class="container mt-4 bg-secondary p-4 rounded">
        <h1 class="text-center mb-4">Cadastro</h1>
        <form action="../controller/funcao-cadastro.php" method="post" enctype="multipart/form-data" onsubmit="return validateDate()">
            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control bg-dark text-light border-light" name="campo_nome" id="name" placeholder="Seu nome" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control bg-dark text-light border-light" name="campo_email" id="email" placeholder="Seu email" required>
            </div>
            <div class="mb-3">
                <label for="birthdate" class="form-label">Data de Nascimento</label>
                <input type="text" class="form-control bg-dark text-light border-light" name="campo_nascimento" id="birthdate" placeholder="dd/mm/aaaa" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control bg-dark text-light border-light" name="campo_senha" id="password" placeholder="Sua senha" required>
            </div>
            <div class="mb-3">
                <label for="photo" class="form-label">Foto</label>
                <input type="file" class="form-control bg-dark text-light border-light" name="arquivo" id="photo" required>
            </div>
            <div class="d-flex justify-content-end mt-4">
                <input type="submit" value="Cadastrar" class="btn btn-warning">
            </div>
        </form>
        <div class="mt-4 text-center">
            <p>Já possui uma conta? <a href="login.php" class="text-warning">Entre agora</a></p>
        </div>
    </div>

    <?php include("../blades/footer.php"); ?>
</body>
</html>
