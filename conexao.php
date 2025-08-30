<?php 

// --- Configurações de Conexão ---

$servidor = "localhost";   // Geralmente é "localhost", pois o banco de dados está na mesma máquina.
$usuario = "root";          // Usuário padrão do XAMPP.
$senha = "";                 // Senha padrão do XAMPP é vazia.
$banco_de_dados = "gestao_despesas";  // O nome que dei ao banco.

// Criando a Conexão 

// Usei a função mysqli_connect para estabelecer a conexão.
$conexao = mysqli_connect($servidor, $usuario, $senha, $banco_de_dados);


// --- Verificando a Conexão ---
// É crucial verificar se a conexão foi bem-sucedida.
if (!$conexao){

     // Se a conexão falhar, a função die() interrompe a execução do script
    // e exibe uma mensagem de erro. Isso nos ajuda a identificar problemas.
    die("Falha na conexão com o banco de dados: ". mysqli_connect_error());
}
mysqli_set_charset($conexao, "utf8");

?>