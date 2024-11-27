<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include("../model/connect.php");

function adicionarAoCarrinho($id_produto, $quantidade, $preco) {
    if (isset($_COOKIE['carrinho'])) {
        $carrinho = json_decode($_COOKIE['carrinho'], true);
    } else {
        $carrinho = [];
    }

    // Verifica se o produto já existe no carrinho
    if (isset($carrinho[$id_produto])) {
        // Se o índice 'quantidade' existir, aumenta a quantidade, caso contrário, define como 0
        if (isset($carrinho[$id_produto]['quantidade'])) {
            $carrinho[$id_produto]['quantidade'] += $quantidade;
        } else {
            $carrinho[$id_produto]['quantidade'] = $quantidade;
        }
    } else {
        // Se o produto não existir no carrinho, adiciona com a quantidade inicial
        $carrinho[$id_produto] = [
            'quantidade' => $quantidade,
            'preco' => $preco
        ];
    }

    // Atualiza o carrinho no cookie
    setcookie('carrinho', json_encode($carrinho), time() + (86400 * 30), "/");
}

function removerDoCarrinho($id_produto) {
    if (isset($_COOKIE['carrinho'])) {
        $carrinho = json_decode($_COOKIE['carrinho'], true);
        unset($carrinho[$id_produto]);
        setcookie('carrinho', json_encode($carrinho), time() + (86400 * 30), "/");
    }
}

function exibirCarrinho() {
    if (isset($_COOKIE['carrinho'])) {
        $carrinho = json_decode($_COOKIE['carrinho'], true);
        $total = 0;
        // Verifica se o índice 'quantidade' está presente para evitar erro
        foreach ($carrinho as $id_produto => $item) {
            if (isset($item['quantidade'])) {
                $total += $item['quantidade'] * $item['preco'];
            }
        }
        return ['carrinho' => $carrinho, 'total' => $total];
    }
    return ['carrinho' => [], 'total' => 0];
}

function getNumeroDeItens() {
    if (isset($_COOKIE['carrinho'])) {
        $carrinho = json_decode($_COOKIE['carrinho'], true);
        $totalItens = 0;
        // Verifica se o índice 'quantidade' está presente
        foreach ($carrinho as $item) {
            if (isset($item['quantidade'])) {
                $totalItens += $item['quantidade'];
            }
        }
        return $totalItens;
    }
    return 0;
}

if (isset($_POST['adicionar'])) {
    $id_produto = $_POST['id_produto'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];
    adicionarAoCarrinho($id_produto, $quantidade, $preco);
}

if (isset($_GET['remover'])) {
    $id_produto = $_GET['remover'];
    removerDoCarrinho($id_produto);
}
?>
