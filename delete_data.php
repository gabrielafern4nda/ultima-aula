<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bombeirosbank";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['table']) && isset($_POST['column'])) {
    $id = $_POST['id'];
    $tableName = $_POST['table'];
    $columnName = $_POST['column'];

    // Certifique-se de escapar os valores para evitar injeção de SQL
    $id = $conn->real_escape_string($id);
    $tableName = $conn->real_escape_string($tableName);
    $columnName = $conn->real_escape_string($columnName);

    // Construa a consulta SQL para excluir o registro
    $sql = "DELETE FROM $tableName WHERE $columnName = '$id'";

    if ($conn->query($sql) === TRUE) {
        echo 'Registro excluído com sucesso.';
    } else {
        echo 'Erro ao excluir registro: ' . $conn->error;
    }
} else {
    echo 'Requisição inválida.';
}

$conn->close();
?>
