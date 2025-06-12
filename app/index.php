<?php
// app/index.php

// Configurações de conexão com o banco de dados (lendo das variáveis de ambiente do Docker Compose)
$host = getenv('MYSQL_HOST') ?: 'db';
$dbname = getenv('MYSQL_DATABASE') ?: 'crud_db';
$user = getenv('MYSQL_USER') ?: 'crud_user';
$password = getenv('MYSQL_PASSWORD') ?: 'crud_password';

$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Conexão com o banco de dados estabelecida com sucesso!<br>";
} catch (PDOException $e) {
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}

// --- Lógica do CRUD ---

// Função para exibir uma mensagem (usada em vez de alert)
function displayMessage($message, $type = 'info') {
    echo "<div style='padding: 10px; margin-bottom: 10px; border-radius: 5px;";
    if ($type == 'success') echo " background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb;";
    if ($type == 'error') echo " background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;";
    if ($type == 'info') echo " background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb;";
    echo "'>" . htmlspecialchars($message) . "</div>";
}

$message = '';

// Criar Item (CREATE)
if (isset($_POST['create'])) {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    if (!empty($nome)) {
        $stmt = $pdo->prepare("INSERT INTO items (nome, descricao) VALUES (?, ?)");
        if ($stmt->execute([$nome, $descricao])) {
            $message = "Item '$nome' criado com sucesso!";
        } else {
            $message = "Erro ao criar item.";
        }
    } else {
        $message = "O nome do item não pode ser vazio.";
    }
}

// Atualizar Item (UPDATE)
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    if (!empty($nome) && !empty($id)) {
        $stmt = $pdo->prepare("UPDATE items SET nome = ?, descricao = ? WHERE id = ?");
        if ($stmt->execute([$nome, $descricao, $id])) {
            $message = "Item ID $id atualizado com sucesso!";
        } else {
            $message = "Erro ao atualizar item.";
        }
    } else {
        $message = "ID e nome do item não podem ser vazios.";
    }
}

// Excluir Item (DELETE)
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $pdo->prepare("DELETE FROM items WHERE id = ?");
    if ($stmt->execute([$id])) {
        $message = "Item ID $id excluído com sucesso!";
    } else {
        $message = "Erro ao excluir item.";
    }
}

// --- Formulários e Listagem ---
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Web2 - Rebecka</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }
        h1, h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            font-weight: 700;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #34495e;
            font-weight: 600;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }
        input[type="text"]:focus, textarea:focus {
            border-color: #3498db;
            outline: none;
        }
        button {
            background-color: #3498db;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }
        button:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .btn-delete {
            background-color: #e74c3c;
        }
        .btn-delete:hover {
            background-color: #c0392b;
        }
        .btn-edit {
            background-color: #f39c12;
        }
        .btn-edit:hover {
            background-color: #e67e22;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ecf0f1;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #ecf0f1;
            color: #34495e;
            font-weight: 700;
        }
        tr:nth-child(even) {
            background-color: #f9fbfd;
        }
        .actions-cell {
            white-space: nowrap;
        }
        .actions-cell button {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-3xl text-center mb-6">CRUD Web2 - Gerenciamento de Itens</h1>

        <?php if ($message): ?>
            <?php displayMessage($message, strpos($message, 'Erro') !== false ? 'error' : 'success'); ?>
        <?php endif; ?>

        <h2 class="text-2xl mb-4">Adicionar Novo Item</h2>
        <form method="POST" action="">
            <div class="mb-4">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required class="rounded-md">
            </div>
            <div class="mb-4">
                <label for="descricao">Descrição:</label>
                <textarea id="descricao" name="descricao" rows="3" class="rounded-md"></textarea>
            </div>
            <button type="submit" name="create" class="rounded-md">Adicionar Item</button>
        </form>

        <hr class="my-8 border-gray-300">

        <h2 class="text-2xl mb-4">Itens Existentes</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Ler Itens (READ)
                $stmt = $pdo->query("SELECT * FROM items ORDER BY id DESC");
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)):
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['nome']); ?></td>
                    <td><?php echo htmlspecialchars($row['descricao']); ?></td>
                    <td class="actions-cell">
                        <button class="btn-edit rounded-md" onclick="
                            document.getElementById('edit_id').value = '<?php echo htmlspecialchars($row['id']); ?>';
                            document.getElementById('edit_nome').value = '<?php echo htmlspecialchars($row['nome']); ?>';
                            document.getElementById('edit_descricao').value = '<?php echo htmlspecialchars($row['descricao']); ?>';
                            document.getElementById('update_form_section').scrollIntoView({ behavior: 'smooth' });
                        ">Editar</button>
                        <a href="?delete_id=<?php echo htmlspecialchars($row['id']); ?>" class="inline-block btn-delete rounded-md py-3 px-5 no-underline" onclick="return confirm('Tem certeza que deseja excluir este item?');">Excluir</a>
                    </td>
                </tr>
                <?php endwhile; ?>
                <?php if ($stmt->rowCount() == 0): ?>
                    <tr>
                        <td colspan="4" class="text-center">Nenhum item encontrado.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <hr class="my-8 border-gray-300">

        <h2 class="text-2xl mb-4" id="update_form_section">Atualizar Item</h2>
        <form method="POST" action="">
            <div class="mb-4">
                <label for="edit_id">ID do Item:</label>
                <input type="text" id="edit_id" name="id" readonly class="rounded-md bg-gray-100">
            </div>
            <div class="mb-4">
                <label for="edit_nome">Nome:</label>
                <input type="text" id="edit_nome" name="nome" required class="rounded-md">
            </div>
            <div class="mb-4">
                <label for="edit_descricao">Descrição:</label>
                <textarea id="edit_descricao" name="descricao" rows="3" class="rounded-md"></textarea>
            </div>
            <button type="submit" name="update" class="rounded-md">Atualizar Item</button>
        </form>
    </div>
</body>
</html>

