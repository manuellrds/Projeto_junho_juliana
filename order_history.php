<?php
// Inicia a sessão para verificar se o usuário está logado
session_start();
// Verifica se o usuário está autenticado
if (!isset($_SESSION['user_id'])) {
    // Redireciona para a página de login se o usuário não estiver autenticado
    header("Location: login.php");
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include 'db.php';
// Obtém o ID do usuário da sessão
$user_id = $_SESSION['user_id'];

// Consulta para obter o histórico de pedidos do usuário logado
$orders = $conn->query("SELECT orders.id as order_id, orders.total_price, orders.order_date, products.name as product_name, order_items.quantity 
                        FROM orders 
                        INNER JOIN order_items ON orders.id = order_items.order_id 
                        INNER JOIN products ON order_items.product_id = products.id 
                        WHERE orders.user_id = $user_id 
                        ORDER BY orders.order_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
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
</style>
<body>
    <div class="container">
        <h2>Historico de Compra</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product Name</th>
                    <th>Quantidade</th>
                    <th>Preço Total</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $orders->fetch_assoc()): ?>
                    <tr>
                        <!-- Exibe os dados de cada pedido -->
                        <td><?php echo $row['order_id']; ?></td>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['quantity']; ?></td>
                        <td><?php echo $row['total_price']; ?></td>
                        <td><?php echo $row['order_date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- Links para voltar à loja ou desconectar-se -->
        <a href="products.php" class="btn btn-primary">Voltar a Loja</a>
        <a href="logout.php" class="btn btn-secondary">Desconectar-se</a>
    </div>
</body>
</html>
