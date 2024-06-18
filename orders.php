<?php
// Inicia a sessão para verificar se o usuário está autenticado
session_start();
// Verifica se o usuário não está logado e redireciona para a página de login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Inclui o arquivo de conexão com o banco de dados
include 'db.php';
// Obtém o ID do usuário da sessão para consultar seus pedidos
$user_id = $_SESSION['user_id'];

// Consulta SQL para obter os pedidos do usuário logado
$orders = $conn->query("SELECT * FROM orders WHERE user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Orders</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $orders->fetch_assoc()): ?>
                    <!-- Loop para exibir cada pedido como uma linha na tabela -->
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['total_price']; ?></td>
                        <td><?php echo $row['order_date']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <!-- Links para voltar à loja e desconectar-se -->
        <a href="products.php" class="btn btn-primary">Voltar a loja</a>
        <a href="logout.php" class="btn btn-secondary">Desconectar-se</a>
    </div>
</body>
</html>
