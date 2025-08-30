<?php
include 'conexao.php';

// Verifica se os dados foram enviados via formulário
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // 1. VERIFICAR SE O E-MAIL JÁ EXISTE NO BANCO
    $sql_check = "SELECT id FROM usuarios WHERE email = ?";
    $stmt_check = mysqli_prepare($conexao, $sql_check);
    mysqli_stmt_bind_param($stmt_check, "s", $email);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_store_result($stmt_check);

    if (mysqli_stmt_num_rows($stmt_check) > 0) {
    // E-mail já existe. Redireciona de volta com um status de erro.
    header('Location: registro.php?status=email_existe');
    exit;
    }
    mysqli_stmt_close($stmt_check);

    // 2. CRIPTOGRAFAR A SENHA (HASHING)
    // A parte mais importante da segurança!
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

    // 3. INSERIR O NOVO USUÁRIO NO BANCO
    $sql_insert = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
    $stmt_insert = mysqli_prepare($conexao, $sql_insert);
    // "sss" significa que estamos passando 3 variáveis do tipo String
    mysqli_stmt_bind_param($stmt_insert, "sss", $nome, $email, $senha_hash);

    if (mysqli_stmt_execute($stmt_insert)) {
        // Se o registro foi bem-sucedido, redireciona para a página de login
        header('Location: login.php?status=registrado');
        exit;
    } else {
        // Se houver um erro na inserção
        echo "Erro ao registrar o usuário. Por favor, tente novamente.";
    }
    mysqli_stmt_close($stmt_insert);

}

mysqli_close($conexao);
?>