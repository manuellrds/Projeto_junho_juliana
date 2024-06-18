<?php
// Inicia a sessão para verificar se o usuário está logado
session_start();
// Inclui o arquivo de conexão com o banco de dados
include 'db.php';

// Verifica se o método de requisição é POST e se o botão 'edit_cart_item' foi pressionado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_cart_item'])) {
    // Obtém o ID do item do carrinho e a ação a ser executada (aumentar ou diminuir)
    $cart_id = $_POST['cart_id'];
    $action = $_POST['action'];

    // Verifica a ação a ser tomada (aumentar ou diminuir quantidade)
    if ($action === 'increase') {
        // Prepara a consulta para aumentar a quantidade do item no carrinho
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = ?");
    } elseif ($action === 'decrease') {
        // Prepara a consulta para diminuir a quantidade do item no carrinho
        $stmt = $conn->prepare("UPDATE cart SET quantity = quantity - 1 WHERE id = ?");
    }

    // Atualiza a quantidade do item no banco de dados
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
    $stmt->close();

    // Redireciona de volta para a página do carrinho após a atualização
    header("Location: cart.php");
    exit();
} else {
    // Se não houver postagem válida, redireciona de volta para a página do carrinho
    header("Location: cart.php");
    exit();
}
?>
