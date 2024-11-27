<?php
// Inicia a sessão e inclui o arquivo de funções do carrinho
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include_once("../controller/funcao-carrinho.php"); // Certifique-se de que o caminho está correto
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lunarian - Cabeçalho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../view/css/style.css">
    <style>
        /* Modal Estilo */
        #cart-modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            max-width: 600px;
            background-color: #f8f9fa;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.2);
            display: none;
            z-index: 1050;
        }

        #cart-modal.show {
            display: block;
        }

        #cart-modal .modal-header {
            background-color: #343a40;
            color: white;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        #cart-modal .modal-body {
            padding: 15px;
        }

        #cart-modal .modal-footer {
            padding: 15px;
            border-top: 1px solid #ddd;
        }

        #cart-modal .cart-item {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        #cart-modal .cart-item .remove-btn {
            color: red;
            font-size: 14px;
            cursor: pointer;
        }

        /* Estilo do botão de carrinho */
        #cart-button {
            position: relative;
        }

        #cart-count {
            position: absolute;
            top: 0;
            start: 100%;
            transform: translate(50%, -50%);
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
<header class="bg-dark py-3">
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="home.php">
                <img src="../view/imgs/logo.png" alt="Logo" style="height: 70px; margin-right: 10px;">
                Lunarian
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <?php if (isset($_SESSION['nome_usuario'], $_SESSION['foto_usuario'])): ?>
                        <li class="nav-item">
                            <img src="../view/imgs/<?php echo htmlspecialchars($_SESSION['foto_usuario']); ?>" 
                                 alt="Foto do Usuário" 
                                 style="height: 40px; width: 40px; border-radius: 50%; margin-right: 10px;">
                        </li>
                        <li class="nav-item">
                            <form method="POST">
                                <button type="submit" name="logout" class="btn btn-danger">Sair da Conta</button>
                            </form>
                            <?php
                            if (isset($_POST['logout'])) {
                                session_destroy();
                                header("Location: login.php");
                                exit();
                            }
                            ?>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="login.php" class="btn btn-primary btn-sm">Entrar</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <button id="cart-button" class="btn btn-outline-light position-relative">
                            🛒 Carrinho
                            <span id="cart-count" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo getNumeroDeItens(); ?>
                            </span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<!-- Modal do Carrinho -->
<div id="cart-modal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Carrinho</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <!-- Lista de itens do carrinho -->
                <div id="cart-items">
                    <?php
                    $carrinho = exibirCarrinho();
                    if ($carrinho['total'] == 0) {
                        echo '<p class="text-center text-muted">Seu carrinho está vazio.</p>';
                    } else {
                        foreach ($carrinho['carrinho'] as $id_produto => $item) {
                            echo '<div class="cart-item d-flex justify-content-between align-items-center">';
                            echo '<div>';
                            echo '<strong>' . htmlspecialchars($id_produto) . '</strong>';
                            echo '<p>R$ ' . number_format($item['preco'], 2, ',', '.') . ' x ' . $item['quantidade'] . '</p>';
                            echo '</div>';
                            echo '<span class="remove-btn" onclick="removeFromCart(\'' . $id_produto . '\')">Remover</span>';
                            echo '</div>';
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <h5>Total: R$ <span id="cart-total"><?php echo number_format($carrinho['total'], 2, ',', '.'); ?></span></h5>
                <button class="btn btn-success w-100">Finalizar Compra</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Função para obter o valor de um cookie
    function getCookie(name) {
        let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? JSON.parse(match[2]) : null;
    }

    // Função para definir um cookie
    function setCookie(name, value) {
        document.cookie = name + '=' + JSON.stringify(value) + '; path=/';
    }

    // Função para remover um item do carrinho
    function removeFromCart(id_produto) {
        let cart = getCookie('carrinho') || [];
        if (cart.hasOwnProperty(id_produto)) {
            delete cart[id_produto];
            setCookie('carrinho', cart);
            renderCart();
        }
    }

    // Renderizar carrinho
    function renderCart() {
        const cartItems = document.getElementById('cart-items');
        const cartCount = document.getElementById('cart-count');
        const cartTotal = document.getElementById('cart-total');

        const cart = getCookie('carrinho') || {};
        let total = 0;
        cartItems.innerHTML = '';

        if (Object.keys(cart).length === 0) {
            cartItems.innerHTML = '<p class="text-center text-muted">Seu carrinho está vazio.</p>';
            cartCount.textContent = '0';
            cartTotal.textContent = '0,00';
        } else {
            Object.keys(cart).forEach(function (id_produto) {
                const item = cart[id_produto];
                total += item.preco * item.quantidade;
                cartItems.innerHTML += `
                    <div class="cart-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${id_produto}</strong>
                            <p>R$ ${item.preco.toFixed(2).replace('.', ',')} x ${item.quantidade}</p>
                        </div>
                        <span class="remove-btn" onclick="removeFromCart('${id_produto}')">Remover</span>
                    </div>
                `;
            });
            cartCount.textContent = Object.keys(cart).length;
            cartTotal.textContent = total.toFixed(2).replace('.', ',');
        }
    }

    // Inicializar carrinho
    renderCart();

    // Abrir o Modal
    const cartButton = document.getElementById('cart-button');
    const cartModal = new bootstrap.Modal(document.getElementById('cart-modal'));

    cartButton.addEventListener('click', () => {
        cartModal.show();
    });
</script>

</body>
</html>
