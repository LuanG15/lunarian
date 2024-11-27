<?php
session_start();
include("../model/connect.php");
// Em home.php ou outros arquivos que precisem das funções de carrinho
include_once('../controller/funcao-carrinho.php');


// Recuperando os produtos
$query_completa = mysqli_query($conexao, "SELECT * FROM luminarias");

if (!$query_completa) {
    die('Erro na consulta: ' . mysqli_error($conexao));
}

if (isset($_POST['adicionar'])) {
    $id_produto = $_POST['id_produto'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];
    adicionarAoCarrinho($id_produto, $quantidade, $preco);
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja - Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../view/css/style.css" type="text/css">
    <style>
        /* Estilos gerais */
        .card-produto {
            transition: transform 0.3s ease, opacity 0.3s ease;
            cursor: pointer;
        }

        /* Modal */
        #modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1050;
            display: none;
        }

        #modal-produto {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 2000;
            max-width: 400px;
            width: 100%;
        }

        #modal-overlay.show {
            display: block;
        }

        /* Corpo sem scroll ao abrir modal */
        body.no-scroll {
            overflow: hidden;
        }
    </style>
</head>
<body>
<?php include("../blades/top.php"); ?>

<div class="container mt-5">
    <h2 class="text-center">Confira nossos produtos</h2>

    <div class="row">
        <?php
        while ($produto = mysqli_fetch_assoc($query_completa)) {
            echo "
            <div class='col-md-4 mb-4'>
                <div class='card card-produto' data-id='{$produto['id']}' data-nome='{$produto['nome']}' data-preco='{$produto['preco']}' data-imagem='imgs/{$produto['url_imagem']}'>
                    <img src='imgs/{$produto['url_imagem']}' class='card-img-top' alt='{$produto['nome']}'>
                    <div class='card-body'>
                        <h5 class='card-title'>{$produto['nome']}</h5>
                        <p class='card-text'>R$ " . number_format($produto['preco'], 2, ',', '.') . "</p>
                    </div>
                    <form method='POST' action='home.php'>
                        <input type='hidden' name='id_produto' value='{$produto['id']}'>
                        <input type='hidden' name='preco' value='{$produto['preco']}'>
                        <button type='submit' name='adicionar' class='btn btn-primary'>Adicionar ao Carrinho</button>
                    </form>
                </div>
            </div>";
        }
        ?>
    </div>
</div>

<!-- Modal para exibir informações detalhadas do produto -->
<div id="modal-overlay">
    <div id="modal-produto">
        <img id="imagem-modal" src="" alt="" class="img-fluid mb-3">
        <h4 id="nome-modal"></h4>
        <p id="preco-modal"></p>
        <form method="POST" action="home.php">
            <input type="hidden" name="id_produto" id="id_produto_modal">
            <input type="hidden" name="preco" id="preco_modal">
            <div class="mb-3">
                <label for="quantidade" class="form-label">Quantidade</label>
                <input type="number" name="quantidade" id="quantidade" class="form-control" min="1" value="1">
            </div>
            <button type="submit" name="adicionar" class="btn btn-success">Adicionar ao Carrinho</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const modalOverlay = document.getElementById('modal-overlay');
    const modalProduto = document.getElementById('modal-produto');

    // Exibir o modal ao clicar no card
    document.querySelectorAll('.card-produto').forEach(card => {
        card.addEventListener('click', function() {
            // Recuperar informações do produto
            const id = this.dataset.id;
            const nome = this.dataset.nome;
            const preco = parseFloat(this.dataset.preco).toFixed(2).replace('.', ',');
            const imagem = this.dataset.imagem;

            // Preencher o modal com as informações
            document.getElementById('id_produto_modal').value = id;
            document.getElementById('preco_modal').value = this.dataset.preco;
            document.getElementById('nome-modal').textContent = nome;
            document.getElementById('preco-modal').textContent = `R$ ${preco}`;
            document.getElementById('imagem-modal').src = imagem;

            // Mostrar o modal e desativar scroll na página
            modalOverlay.classList.add('show');
            document.body.classList.add('no-scroll');
        });
    });

    // Fechar o modal ao clicar fora dele
    modalOverlay.addEventListener('click', function(event) {
        if (event.target === modalOverlay) {
            fecharModal();
        }
    });

    function fecharModal() {
        modalOverlay.classList.remove('show');
        document.body.classList.remove('no-scroll');
    }
</script>

<?php include("../blades/footer.php"); ?>
</body>
</html>
