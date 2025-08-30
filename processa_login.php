<?php
// 1. INICIAR A SESSÃO
// session_start() deve ser a primeira coisa a ser chamada em qualquer página que use sessões.
session_start();

include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha_digitada = $_POST['senha'];

    // 2. BUSCAR O USUÁRIO NO BANCO DE DADOS PELO E-MAIL
    $sql = "SELECT id, nome, senha FROM usuarios WHERE email = ?";
    $stmt = mysqli_prepare($conexao, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);

    // 3. VERIFICAR SE O USUÁRIO EXISTE
    if ($usuario = mysqli_fetch_assoc($resultado)) {
        // Usuário encontrado, agora vamos verificar a senha

        // 4. VERIFICAR A SENHA
        // A função password_verify() compara a senha digitada com o hash salvo no banco.
        if (password_verify($senha_digitada, $usuario['senha'])) {
            // Senha correta! Login bem-sucedido.

            // 5. SALVAR INFORMAÇÕES DO USUÁRIO NA SESSÃO
            // Guardamos o ID e o nome do usuário no "crachá" da sessão.
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];

            // 6. REDIRECIONAR PARA A PÁGINA PRINCIPAL
            header('Location: index.php');
            exit;

        } else {
            // Senha incorreta
            header('Location: login.php?status=erro_senha');
            exit;
        }

    } else {
        // Usuário não encontrado com este e-mail
        header('Location: login.php?status=erro_email');
        exit;
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conexao);
?>