<?php
include 'conexao.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];

    $sql = "UPDATE contato SET nome='$nome', email='$email' WHERE id=$id";

    if (mysqli_query($conexao, $sql)) {
        echo "Contato atualizado com sucesso!";
    } else {
        echo "Erro: " . mysqli_error($conexao);
    }

    mysqli_close($conexao);
    exit;
}

$sql = "SELECT * FROM contato WHERE id=$id";
$result = mysqli_query($conexao, $sql);
$row = mysqli_fetch_assoc($result);
?>

<form method="POST">
    Nome: <input type="text" name="nome" value="<?php echo $row['nome']; ?>"><br>
    Email: <input type="email" name="email" value="<?php echo $row['email']; ?>"><br>
    <button type="submit">Atualizar</button>
</form>
