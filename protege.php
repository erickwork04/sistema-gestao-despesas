<?php
// Inicia a sessão, tornando a variável $_SESSION disponível.
// Colocamos um @ na frente para suprimir avisos caso a sessão já tenha sido iniciada.
@session_start();

// Verifica se não existe a variável de sessão do usuário
if(!isset($_SESSION['usuario_id'])) {
    // Se não existir, destrói a sessão por segurança
    session_destroy();
    // E redireciona o usuário para a tela de login
    header("Location: login.php");
    exit;
}
?>