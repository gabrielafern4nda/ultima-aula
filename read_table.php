<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bombeirosbank";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Verificar se o parâmetro 'table' foi passado na URL
if (isset($_GET['table'])) {
    $tableName = $_GET['table'];

    // Consulta SQL para obter os dados da tabela selecionada
    $sql = "SELECT * FROM $tableName";
    $result = $conn->query($sql);

    if (!$result) {
        die("Erro na consulta: " . $conn->error);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dados da Tabela <?php echo $tableName; ?></title>
    <style>
        /* Adicione seu estilo CSS aqui */
        .edit-form {
            display: none;
        }
    </style>
</head>
<body>
    <h2>Dados da Tabela <?php echo $tableName; ?></h2>
    <table border="1">
        <tr>
            <!-- Adicione cabeçalhos de coluna dinamicamente com base nas colunas da tabela -->
            <?php
            $columns = $result->fetch_fields();
            foreach ($columns as $column) {
                echo "<th>" . $column->name . "</th>";
            }
            ?>
            <th>Ações</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                // Exibir os dados da linha
                foreach ($row as $columnName => $value) {
                    echo "<td class='data-cell' data-column='$columnName'>$value</td>";
                }

                // Identificar a coluna que serve como identificador exclusivo (pode variar entre tabelas)
                $idColumnName = '';  // Substitua pelo nome da coluna que serve como 'id' na sua tabela
                $id = isset($row[$idColumnName]) ? $row[$idColumnName] : '';

                echo "<td>
        <button class='edit-button' data-id='$id' data-table='$tableName' data-column='$idColumnName' onclick='showEditForm($id)'>Editar</button>
        <button class='delete-button' data-id='$id' data-table='$tableName' data-column='$idColumnName' onclick='deleteData(this)'>Excluir</button>
      </td>";

            }
        } else {
            echo "<tr><td colspan='" . (count($columns) + 1) . "'>Nenhum dado encontrado na tabela</td></tr>";
        }
        $conn->close();
        ?>
    </table>

    <!-- Formulário de Edição -->
    <form id="editForm" class="edit-form">
        <h2>Editar Dados</h2>
        <div id="editFormMessage"></div>
        <?php
        foreach ($columns as $column) {
            echo "<label>{$column->name}:</label>
                  <input type='text' id='{$column->name}' name='{$column->name}'><br>";
        }
        ?>
        <input type='hidden' id='editId' name='editId'>
        <button type='button' onclick='updateData()'>Salvar</button>
        <button type='button' onclick='cancelEdit()'>Cancelar</button>
    </form>

    <script>
        // Função para exibir o formulário de edição com os dados da linha selecionada
        function showEditForm(id) {
            document.getElementById('editId').value = id;

            var dataCells = document.querySelectorAll('.data-cell');
            dataCells.forEach(function(cell) {
                var columnName = cell.getAttribute('data-column');
                var inputField = document.getElementById(columnName);
                inputField.value = cell.innerText;
            });

            document.getElementById('editForm').style.display = 'block';
        }

        // Função para ocultar o formulário de edição
        function cancelEdit() {
            document.getElementById('editForm').style.display = 'none';
        }

        // Função para enviar os dados de edição ao servidor usando AJAX
        function updateData() {
            var form = document.getElementById('editForm');
            var formData = new FormData(form);

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        document.getElementById('editFormMessage').innerText = 'Dados atualizados com sucesso.';
                        setTimeout(function() {
                            location.reload(); // Recarregar a página após a atualização
                        }, 1000);
                    } else {
                        document.getElementById('editFormMessage').innerText = 'Erro ao atualizar dados.';
                    }
                }
            };

            xhr.open('POST', 'update_data.php', true);
            xhr.send(formData);
        }
        function deleteData(button) {
    var confirmDelete = confirm('Tem certeza de que deseja excluir este registro?');

    if (confirmDelete) {
        var id = button.getAttribute('data-id');
        var tableName = button.getAttribute('data-table');
        var columnName = button.getAttribute('data-column');

        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    document.getElementById('editFormMessage').innerText = 'Registro excluído com sucesso.';
                    setTimeout(function () {
                        location.reload(); // Recarregar a página após a exclusão
                    }, 1000);
                } else {
                    document.getElementById('editFormMessage').innerText = 'Erro ao excluir registro.';
                }
            }
        };

        xhr.open('POST', 'delete_data.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('id=' + id + '&table=' + tableName + '&column=' + columnName);
    }
}
    </script>
</body>
</html>

<?php
} else {
    // Redirecionar de volta para a página de listagem se o parâmetro 'table' não estiver presente
    header("Location: read.php");
    exit();
}
?>
