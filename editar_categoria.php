<?php
    include('protege.php');
    include 'conexao.php';
    // Pega o ID da categoria da URL e garante que é um número
    $id_categoria = isset($_GET['id']) ? intval($_GET['id']) : 0;
    
    // Se o ID for inválido, redireciona para a página inicial
    if ($id_categoria == 0) {
        header('Location: index.php');
        exit;
    }

    // Busca o nome atual da categoria no banco de dados
    $sql = "SELECT nome_categoria FROM categorias WHERE id = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id_categoria);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $categoria = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmt);

    // Se não encontrar a categoria, redireciona
    if (!$categoria) {
        header('Location: index.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> 
    <title>Editar Lista</title>
</head>
<body>
    <header>
        <div class="container-header">
            <h1>Editar Categorias</h1>
            
            <div class="menu-usuario">
                <span>Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</span>
                <div class="dropdown-content">
                    <a href="editar_perfil.php">Editar Perfil</a>
                    <a href="logout.php">Sair</a>
                </div>
            </div>
        </div>
    </header>

    <main>
        <form action="processa_categoria.php" method="POST" class="form-edicao">
            <input type="hidden" name="acao" value="editar_categoria">
            <input type="hidden" name="id_categoria" value="<?php echo $id_categoria; ?>">
            
            <label for="nome_categoria">Novo nome para a lista:</label>
            <input type="text" id="nome_categoria" name="nome_categoria" value="<?php echo htmlspecialchars($categoria['nome_categoria']); ?>" required>
            
            <div class="acoes-form">
                <a href="index.php" class="btn-cancelar">Cancelar</a>
                <button type="submit">Salvar Alterações</button>
            </div>
        </form>
    </main>
</body>
</html>