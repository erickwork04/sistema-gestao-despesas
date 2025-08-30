<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> 
    <title>Criar Nova Conta</title>
</head>
<body>
    <header>
        <h1>Crie sua Conta</h1>
    </header>

    <main>
        <?php
          if (isset($_GET['status']) && $_GET['status'] == 'email_existe') {
              echo "<p class='mensagem-erro'>Este e-mail já está cadastrado. Tente outro ou faça login.</p>";
          }
        ?>
        <form action="processa_registro.php" method="POST" class="form-auth">
            
            <label for="nome">Nome Completo:</label>
            <input type="text" id="nome" name="nome" required>
            
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required>

            <label for="senha">Senha (mínimo 6 caracteres):</label>
            <div class="input-com-icone">
                <input type="password" id="senha" name="senha" required minlength="6">
                <span id="toggleSenha" class="toggle-senha">&#128065;</span> 
            </div>
            
            <div class="acoes-form">
                <a href="login.php" class="btn-cancelar">Já tenho uma conta</a>
                <button type="submit">Registrar</button>
            </div>
        </form>
    </main>
</body>
<script>
        const toggleSenha = document.getElementById('toggleSenha');
        const campoSenha = document.getElementById('senha');

        if (toggleSenha && campoSenha) {
            
            // Função para ESCONDER a senha
            function esconderSenha() {
                campoSenha.setAttribute('type', 'password');
                toggleSenha.style.display = 'block'; // Garante que o ícone esteja visível
            }

            // Função para MOSTRAR a senha
            function mostrarSenha() {
                campoSenha.setAttribute('type', 'text');
                toggleSenha.style.display = 'none'; // Esconde o ícone
            }

            // --- NOSSOS EVENTOS ---

            // 1. Ao CLICAR no ícone
            toggleSenha.addEventListener('click', function () {
                mostrarSenha();
            });

            // 2. Ao CLICAR FORA do campo (perder o foco)
            campoSenha.addEventListener('blur', function() {
                esconderSenha();
            });
            
            // 3. (BÔNUS) Ao começar a DIGITAR no campo
            campoSenha.addEventListener('input', function() {
                // Se o usuário começar a digitar e a senha estiver visível,
                // a gente esconde ela de novo por segurança.
                if (campoSenha.getAttribute('type') === 'text') {
                    esconderSenha();
                }
            });
        }
    </script>
</html>