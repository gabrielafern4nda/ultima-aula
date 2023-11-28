<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bombeirosbank";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["editId"];

    // Construa a consulta SQL de atualização com base nos campos do formulário
    $sql = "UPDATE sua_tabela SET ";

    // Substitua 'nome_coluna' pelos nomes reais das colunas da sua tabela
    $columns = array('nome_coluna1', 'nome_coluna2', 'nome_coluna3');
    foreach ($columns as $column) {
        $value = $_POST[$column];
        $sql .= "$column = '$value', ";
    }

    // Remova a vírgula extra no final da string SQL
    $sql = rtrim($sql, ", ");

    $sql .= " WHERE id = '$id'";  // Substitua 'id' pelo nome da coluna que serve como identificador exclusivo

    if ($conn->query($sql) === TRUE) {
        echo "Dados atualizados com sucesso";
    } else {
        echo "Erro ao atualizar dados: " . $conn->error;
    }
}

$conn->close();
?>
