<?php
session_start();
include 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];
$acao = $_POST['acao'] ?? '';

// --- AÇÃO: ADICIONAR ITEM (JÁ ESTÁ EM MODO AJAX) ---
if ($acao == 'adicionar_item') {
    // ... (O código de adicionar que já fizemos continua aqui, sem alterações)
    $item_nome = $_POST['item'];
    $id_categoria = $_POST['id_categoria'];
    $sql = "INSERT INTO itens_lista (item, id_categoria, usuario_id) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "sii", $item_nome, $id_categoria, $usuario_id);
    if (mysqli_stmt_execute($stmt)) {
        $novo_item_id = mysqli_insert_id($conexao);
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'item' => ['id' => $novo_item_id, 'nome' => $item_nome, 'id_categoria' => $id_categoria]]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao salvar o item.']);
    }
    mysqli_stmt_close($stmt);
    exit;
}

// --- NOVA AÇÃO: REMOVER ITEM (MODO AJAX) ---
elseif ($acao == 'remover_item') {
    $id_item = $_POST['id_item'];
    $sql = "DELETE FROM itens_lista WHERE id = ? AND usuario_id = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $id_item, $usuario_id);

    header('Content-Type: application/json');
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(['success' => true]); // Apenas responde que deu certo
    } else {
        echo json_encode(['success' => false, 'message' => 'Erro ao remover o item.']);
    }
    mysqli_stmt_close($stmt);
    exit;
}

// --- AÇÃO DE MARCAR ITEM (CONTINUA COM RECARREGAMENTO POR ENQUANTO) ---
elseif ($acao == 'marcar_item') {
    $id_item = $_POST['id_item'];
    $id_categoria_retorno = $_POST['id_categoria'];
    
    // ... (código completo de marcar item) ...
    $sql_check = "SELECT comprado FROM itens_lista WHERE id = ? AND usuario_id = ?"; $stmt_check = mysqli_prepare($conexao, $sql_check); mysqli_stmt_bind_param($stmt_check, "ii", $id_item, $usuario_id); mysqli_stmt_execute($stmt_check); $resultado = mysqli_stmt_get_result($stmt_check); $item = mysqli_fetch_assoc($resultado); mysqli_stmt_close($stmt_check); if ($item) { $novo_estado = $item['comprado'] ? 0 : 1; $sql_update = "UPDATE itens_lista SET comprado = ? WHERE id = ? AND usuario_id = ?"; $stmt_update = mysqli_prepare($conexao, $sql_update); mysqli_stmt_bind_param($stmt_update, "iii", $novo_estado, $id_item, $usuario_id); mysqli_stmt_execute($stmt_update); mysqli_stmt_close($stmt_update); }
    
    $url_redirecionamento = 'index.php?lista_aberta=' . $id_categoria_retorno;
    header('Location: ' . $url_redirecionamento);
    exit;
}

mysqli_close($conexao);
?>