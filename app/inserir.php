<?php
include 'conexao.php';

$nome = $_POST['nome'];
$email = $_POST['email'];

$sql = "INSERT INTO contato (nome, email) VALUES ('$nome', '$email')";

if (mysqli_query($conexao, $sql)) {
    echo "Contato inserido com sucesso!";
} else {
    echo "Erro: " . mysqli_error($conexao);
}

mysqli_close($conexao);
?>
