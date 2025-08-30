<?php
// Inicia a sessão para ter acesso aos dados do usuário
session_start();
include 'conexao.php';

// Pega o ID do usuário logado da sessão
$usuario_id = $_SESSION['usuario_id'];

if (isset($_POST['acao'])) {

    // --- AÇÃO: ADICIONAR NOVA DESPESA ---
    if ($_POST['acao'] == 'adicionar') {
        $descricao = $_POST['descricao'];
        $valor = $_POST['valor'];
        $data_compra = $_POST['data_compra'];

        // Adicionamos a coluna 'usuario_id' na consulta INSERT
        $sql = "INSERT INTO despesas (usuario_id, descricao, valor, data_compra) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conexao, $sql);

        // "isds" = integer, string, double, string
        mysqli_stmt_bind_param($stmt, "isds", $usuario_id, $descricao, $valor, $data_compra);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    // --- AÇÃO: REMOVER DESPESA ---
    elseif ($_POST['acao'] == 'remover') {
        $id_despesa = $_POST['id_despesa'];

        // Ação de segurança: só apaga a despesa se ela pertencer ao usuário logado
        $sql = "DELETE FROM despesas WHERE id = ? AND usuario_id = ?";
        $stmt = mysqli_prepare($conexao, $sql);
        
        // "ii" = integer, integer
        mysqli_stmt_bind_param($stmt, "ii", $id_despesa, $usuario_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

// Ao final de qualquer ação, redireciona de volta.
// Se o usuário veio do histórico, podemos redirecioná-lo de volta para lá.
// Por enquanto, vamos manter o redirecionamento simples para o index.
mysqli_close($conexao);
header('Location: index.php');
exit;
?>