<?php
// Inicia a sessão para podermos usar mensagens de status
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> 
    <title>Login - Sistema de Despesas</title>
</head>
<body>
    <header>
        <h1>Acessar o Sistema</h1>
    </header>

    <main>
        <form action="processa_login.php" method="POST" class="form-auth">
            
            <?php
            // Se houver uma mensagem de status (ex: vindo do registro), exibe aqui
                if (isset($_GET['status'])) {
                    if ($_GET['status'] == 'registrado') {
                        echo "<p class='mensagem-sucesso'>Usuário registrado com sucesso! Faça o login.</p>";

            // Exibe a mensagem de erro aqui 
                    } elseif ($_GET['status'] == 'erro_senha' || $_GET['status'] == 'erro_email') {
                        echo "<p class='mensagem-erro'>E-mail ou senha incorretos. Tente novamente.</p>";
                    }
                }
            ?>

            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha:</label>
            <div class="input-com-icone">
                <input type="password" id="senha" name="senha" required>
                <span id="toggleSenha" class="toggle-senha">&#128065;</span>
            </div>
            
            <div class="acoes-form">
                <a href="registro.php" class="btn-cancelar">Criar uma conta</a>
                <button type="submit">Entrar</button>
            </div>
        </form>
    </main>

    <script>
        const toggleSenha = document.getElementById('toggleSenha');
        const campoSenha = document.getElementById('senha');

        if (toggleSenha && campoSenha) {
            // Evento de CLIQUE no ícone
            toggleSenha.addEventListener('click', function () {
                // Mostra a senha
                campoSenha.setAttribute('type', 'text');
                // Esconde o ícone
                this.style.display = 'none'; 
            });

            // Evento de PERDA DE FOCO (clicar fora)
            campoSenha.addEventListener('blur', function() {
                // Esconde a senha de volta
                this.setAttribute('type', 'password');
                // Mostra o ícone novamente
                toggleSenha.style.display = 'block'; 
            });
        }
    </script>
    
</body>
</html>