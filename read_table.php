<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bombeirosbank";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Consulta SQL para obter nomes de tabelas disponíveis
$tablesQuery = "SHOW TABLES";
$tablesResult = $conn->query($tablesQuery);

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tableName = $_POST["table"];
    
    // Consulta SQL para obter os dados da tabela selecionada
    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar Tabela</title>
    <style>
        /* Adicione seu estilo CSS aqui */
    </style>
</head>
<body>

<h2>Selecionar Tabela</h2>

<form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <label for="table">Escolha uma tabela:</label>
    <select name="table" id="table">
        <?php
        // Exibir opções de tabela
        while ($row = $tablesResult->fetch_row()) {
            echo "<option value='" . $row[0] . "'>" . $row[0] . "</option>";
        }
        ?>
    </select>
    <input type="submit" value="Visualizar Tabela">
</form>

<?php
if (isset($result)) {
    // Exibir os dados da tabela selecionada
    echo "<h2>Dados da Tabela $tableName</h2>";
    echo "<form method='post' action='atualizar_dados.php'>";
    echo "<table border='1'>";
    
    $columns = $result->fetch_fields();

    echo "<tr>";
    // Adicionar cabeçalhos de coluna
    foreach ($columns as $column) {
        echo "<th>" . $column->name . "</th>";
    }
    echo "</tr>";

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            // Exibir os dados da linha
            foreach ($row as $columnName => $value) {
                echo "<td><input type='text' name='data[$columnName][]' value='" . htmlspecialchars($value) . "'></td>";
            }
            echo "</tr>";
        }
        echo "<tr><td colspan='" . count($columns) . "'><input type='submit' value='Atualizar Dados'></td></tr>";
    } else {
        echo "<tr><td colspan='" . count($columns) . "'>Nenhum dado encontrado na tabela</td></tr>";
    }

    echo "</table>";
    echo "</form>";
}

$conn->close();
?>

</body>
</html>
