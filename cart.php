<?php
// Inicia a sessão para verificar se o usuário está logado
session_start();
if (!isset($_SESSION['user_id'])) {
    // Redireciona para a página de login se o usuário não estiver logado
    header("Location: login.php");
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include 'db.php';

// Obtém o ID do usuário da sessão
$user_id = $_SESSION['user_id'];

// Verifica se o formulário foi submetido via método POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se o botão "add_to_cart" foi pressionado para adicionar um produto ao carrinho
    if (isset($_POST['add_to_cart'])) {
        // Obtém os dados do formulário
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // Prepara e executa a consulta para inserir o produto no carrinho do usuário
        $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
        $stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $stmt->execute();
        $stmt->close();
    } 
    // Verifica se o botão "remove_from_cart" foi pressionado para remover um item do carrinho
    elseif (isset($_POST['remove_from_cart'])) {
        // Obtém o ID do item do carrinho a ser removido
        $cart_id = $_POST['cart_id'];

        // Prepara e executa a consulta para remover o item do carrinho
        $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $stmt->close();
    } 
    // Verifica se o botão "checkout" foi pressionado para finalizar a compra
    elseif (isset($_POST['checkout'])) {
        // Obtém o preço total da compra do formulário
        $total_price = $_POST['total_price'];

        // Insere o pedido na tabela de pedidos e obtém o ID do pedido inserido
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
        $stmt->bind_param("id", $user_id, $total_price);
        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        // Salva os itens do carrinho como itens do pedido na tabela order_items
        $cart_items = $conn->query("SELECT * FROM cart WHERE user_id = $user_id");
        while ($item = $cart_items->fetch_assoc()) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("iii", $order_id, $item['product_id'], $item['quantity']);
            $stmt->execute();
            $stmt->close();
        }

        // Limpa o carrinho após finalizar o pedido
        $stmt = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();

        // Exibe uma mensagem de sucesso e redireciona para a página de login após 2 segundos
        echo "<div class='alert alert-success'>Pedido realizado com sucesso!</div>";
        header("Refresh: 2; URL=login.php");
        exit();
    }
}

// Consulta para obter o nome de usuário do usuário logado
$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();

// Consulta para obter os itens do carrinho do usuário
$cart_items = $conn->query("SELECT cart.id as cart_id, products.name, products.price, cart.quantity FROM cart INNER JOIN products ON cart.product_id = products.id WHERE cart.user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-image: url('https://static.vecteezy.com/ti/vetor-gratis/p3/14471815-carrinho-de-compras-icone-de-negocios-desenho-animado-plano-para-a-ideia-de-negocio-web-design-ilustracaoial-gratis-vetor.jpg'); /* Imagem de fundo */
            background-size: cover;
            background-repeat: no-repeat;
            color: black; /* Cor do texto */
            font-family: Arial, sans-serif; /* Fonte para o texto */
            padding-top: 20px;
        }

        .btn-purple {
            background-color: purple;
            color: white;
        }
        .sol{
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Banners de cabeçalho -->
        <div class="header-banner">
            <h2 class="sol">Bem-vindo, <?php echo $username; ?>!</h2>
            <p>Estes são os itens no seu carrinho:</p>
        </div>
        <!-- Título da página -->
        <h2>Carrinho</h2>
        <!-- Tabela de itens do carrinho -->
        <table class="table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Total</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Inicializa o preço total do carrinho
                $total_price = 0;
                // Loop para exibir cada item do carrinho
                while ($row = $cart_items->fetch_assoc()): 
                    // Calcula o preço total para este item
                    $total_price += $row['price'] * $row['quantity'];
                ?>
                    <tr>
                        <!-- Nome do produto -->
                        <td><?php echo $row['name']; ?></td>
                        <!-- Preço unitário do produto -->
                        <td><?php echo $row['price']; ?></td>
                        <!-- Quantidade do produto no carrinho -->
                        <td><?php echo $row['quantity']; ?></td>
                        <!-- Preço total para este item -->
                        <td><?php echo $row['price'] * $row['quantity']; ?></td>
                        <!-- Coluna para ações (remover do carrinho, aumentar/diminuir quantidade) -->
                        <td>
                            <!-- Formulário para remover o item do carrinho -->
                            <form method="POST" action="">
                                <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                                <button type="submit" name="remove_from_cart" class="btn btn-danger">Remove</button>
                            </form>

                            <!-- Formulário para aumentar a quantidade do item -->
                            <form method="POST" action="edit_cart_item.php">
                                <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                                <input type="hidden" name="action" value="increase">
                                <button type="submit" name="edit_cart_item" class="btn btn-primary">+</button>
                            </form>

                            <!-- Formulário para diminuir a quantidade do item -->
                            <form method="POST" action="edit_cart_item.php">
                                <input type="hidden" name="cart_id" value="<?php echo $row['cart_id']; ?>">
                                <input type="hidden" name="action" value="decrease">
                                <button type="submit" name="edit_cart_item" class="btn btn-outline-light">-</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- Exibe o preço total do carrinho -->
        <h3>Total: <?php echo $total_price; ?></h3>
        <!-- Formulário para finalizar a compra -->
        <form method="POST" action="">
            <input type="hidden" name="total_price" value="<?php echo $total_price; ?>">
            <button type="submit" name="checkout" class="btn btn-primary">FINALIZAR COMPRA</button>
        </form>
        <!-- Link para continuar comprando -->
        <a href="products.php" class="btn btn-secondary">Continue Comprando</a>
    </div>
</body>
</html>
