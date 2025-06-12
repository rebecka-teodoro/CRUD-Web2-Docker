<?php
$servidor = "localhost";
$usuario = "root";
$senha = ""; // corrigido ponto e vírgula
$banco = "agenda";

$conexao = mysqli_connect($servidor, $usuario, $senha, $banco);

if (!$conexao) {
    die("Falha na conexão: " . mysqli_connect_error());
}
?>
