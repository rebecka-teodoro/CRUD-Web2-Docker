<?php
include 'conexao.php';

$sql = "SELECT * FROM contato";
$result = mysqli_query($conexao, $sql);

echo "<table border='1'>";
echo "<tr><th>ID</th><th>Nome</th><th>Email</th><th>Ações</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['nome']}</td>
            <td>{$row['email']}</td>
            <td>
                <a href='alterar.php?id={$row['id']}'>Alterar</a> | 
                <a href='excluir.php?id={$row['id']}'>Excluir</a>
            </td>
          </tr>";
}
echo "</table>";

mysqli_close($conexao);
?>
