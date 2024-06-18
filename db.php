<?php
// Definição das variáveis para conexão ao banco de dados
$servername = "localhost";  // Endereço do servidor MySQL
$username = "root";         // Nome de usuário do MySQL
$password = "";             // Senha do MySQL
$dbname = "racaoracao";     // Nome do banco de dados

// Criando uma nova conexão com o MySQL usando o construtor mysqli
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando se a conexão teve êxito
if ($conn->connect_error) {
    // Caso haja erro na conexão, encerra o script e exibe mensagem de erro
    die("Connection failed: " . $conn->connect_error);
}
?>

