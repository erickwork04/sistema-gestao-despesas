<?php
include 'conexao.php';

if (isset($_POST['acao'])) {

    // --- AÇÃO: ADICIONAR NOVA CATEGORIA ---
    if ($_POST['acao'] == 'adicionar_categoria') {
        if (!empty($_POST['nome_categoria'])) {
            
            // 1. Inicia a sessão para acessar os dados do usuário logado
            session_start();
            
            // 2. Pega o nome da categoria do formulário
            $nome_categoria = $_POST['nome_categoria'];
            
            // 3. Pega o ID do usuário que está guardado na sessão
            $usuario_id = $_SESSION['usuario_id'];

            // 4. Prepara a nova consulta SQL, agora incluindo a coluna 'usuario_id'
            $sql = "INSERT INTO categorias (usuario_id, nome_categoria) VALUES (?, ?)";
            $stmt = mysqli_prepare($conexao, $sql);

            // 5. Vincula os dois parâmetros: o ID (inteiro 'i') e o nome (string 's')
            mysqli_stmt_bind_param($stmt, "is", $usuario_id, $nome_categoria);
            
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }
    
    // --- AÇÃO: EXCLUIR CATEGORIA (E SEUS ITENS) ---
    elseif ($_POST['acao'] == 'excluir_categoria') {
        $id_categoria = $_POST['id_categoria'];

        // IMPORTANTE: Primeiro, apaga todos os ITENS que pertencem a esta categoria.
        $sql_itens = "DELETE FROM itens_lista WHERE id_categoria = ?";
        $stmt_itens = mysqli_prepare($conexao, $sql_itens);
        mysqli_stmt_bind_param($stmt_itens, "i", $id_categoria);
        mysqli_stmt_execute($stmt_itens);
        mysqli_stmt_close($stmt_itens);

        // Depois, apaga a CATEGORIA em si.
        $sql_cat = "DELETE FROM categorias WHERE id = ?";
        $stmt_cat = mysqli_prepare($conexao, $sql_cat);
        mysqli_stmt_bind_param($stmt_cat, "i", $id_categoria);
        mysqli_stmt_execute($stmt_cat);
        mysqli_stmt_close($stmt_cat);
    }

    // --- AÇÃO: EDITAR NOME DA CATEGORIA ---
    elseif ($_POST['acao'] == 'editar_categoria') {
        $id_categoria = $_POST['id_categoria'];
        $novo_nome = $_POST['nome_categoria'];

        $sql = "UPDATE categorias SET nome_categoria = ? WHERE id = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        mysqli_stmt_bind_param($stmt, "si", $novo_nome, $id_categoria);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conexao);
header('Location: index.php');
exit;
?>