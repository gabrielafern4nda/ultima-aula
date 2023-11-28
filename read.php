<?php
// Conectar ao banco de dados e recuperar os nomes das tabelas
// Substitua as credenciais do banco de dados conforme necessário

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bombeirosbank";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consulta SQL para obter os nomes das tabelas
$sql = "SHOW TABLES";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Tabelas</title>
    <style>
        /* Adicione seu estilo CSS aqui */
    </style>
</head>
<body>
    <h2>Lista de Tabelas</h2>
    <ul>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tableName = $row["Tables_in_$dbname"];
                echo "<li><a href='read_table.php?table=$tableName'>$tableName</a></li>";
            }
        } else {
            echo "<li>Nenhuma tabela encontrada</li>";
        }
        $conn->close();
        ?>
    </ul>
</body>
</html>