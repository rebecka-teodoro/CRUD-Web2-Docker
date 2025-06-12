<?php
include 'conexao.php';

$id = $_GET['id'];

$sql = "DELETE FROM contato WHERE id=$id";

if (mysqli_query($conexao, $sql)) {
    echo "Contato excluído com sucesso!";
} else {
    echo "Erro: " . mysqli_error($conexao);
}

mysqli_close($conexao);
?>
