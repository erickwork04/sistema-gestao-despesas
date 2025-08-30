<?php 
    include('protege.php');
    include 'conexao.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css"> 
    <title>Sistemas de Gestão de Despesas</title>
</head>
<body>
    <header>
        <div class="container-header">
            <h1>Sistema de Despesas</h1>
            
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
        <section class="container-listas">
            <h2>Minhas Listas de Compras</h2>
            <form action="processa_categoria.php" method="POST" class="form-nova-categoria">
                <input type="hidden" name="acao" value="adicionar_categoria">
                <input type="text" name="nome_categoria" placeholder="Nome da nova lista..." required>
                <button type="submit">Criar Nova Lista</button>
            </form>

            <div class="grid-categorias">
            <?php
                $sql_categorias = "SELECT * FROM categorias ORDER BY id ASC";
                $resultado_categorias = mysqli_query($conexao, $sql_categorias);
                if(mysqli_num_rows($resultado_categorias) > 0) {
                    // Loop para criar um "card" e um "modal" para cada categoria
                    while($categoria = mysqli_fetch_assoc($resultado_categorias)) {
                        $id_cat = $categoria['id'];
                        ?>
                        <div class="card-categoria-gatilho" data-modal-alvo="modal-categoria-<?php echo $id_cat; ?>">
                            <h3><?php echo htmlspecialchars($categoria['nome_categoria']); ?></h3>
                            <div class="acoes-categoria">
                                <form action="processa_categoria.php" method="POST" style="display: inline;"><input type="hidden" name="acao" value="excluir_categoria"><input type="hidden" name="id_categoria" value="<?php echo $id_cat; ?>"><button type="submit" class="btn-acao" title="Excluir lista" onclick="return confirm('ATENÇÃO! Isso apagará a lista e TODOS os itens dentro dela. Deseja continuar?');">&#128465;</button></form>
                                <a href="editar_categoria.php?id=<?php echo $id_cat; ?>" class="btn-acao" title="Editar nome da lista">&#9998;</a>
                            </div>
                        </div>

                        <div id="modal-categoria-<?php echo $id_cat; ?>" class="modal">
                            <div class="modal-conteudo">
                                <span class="modal-fechar">&times;</span>
                                <h2><?php echo htmlspecialchars($categoria['nome_categoria']); ?></h2>
                                <div class="conteudo-lista-modal">
                                    <form action="processa_item.php" method="POST" class="form-add-item"><input type="hidden" name="acao" value="adicionar_item"><input type="hidden" name="id_categoria" value="<?php echo $id_cat; ?>"><input type="text" name="item" placeholder="Novo item..." required><button type="submit" title="Adicionar item">+</button></form>
                                    <ul>
                                    <?php
                                        $sql_itens = "SELECT * FROM itens_lista WHERE id_categoria = ? AND usuario_id = ? ORDER BY comprado ASC, id ASC";
                                        $stmt_itens = mysqli_prepare($conexao, $sql_itens);
                                        mysqli_stmt_bind_param($stmt_itens, "ii", $id_cat, $_SESSION['usuario_id']);
                                        mysqli_stmt_execute($stmt_itens);
                                        $resultado_itens = mysqli_stmt_get_result($stmt_itens);
                                        if(mysqli_num_rows($resultado_itens) > 0){
                                            while($item = mysqli_fetch_assoc($resultado_itens)){
                                                $classe_item = $item['comprado'] ? 'item-comprado' : '';
                                                echo "<li class='{$classe_item}'>";
                                                echo '<form action="processa_item.php" method="POST" class="form-check-item"><input type="hidden" name="acao" value="marcar_item"><input type="hidden" name="id_item" value="' . $item['id'] . '"><input type="hidden" name="id_categoria" value="' . $id_cat . '"><input type="checkbox" onchange="this.form.submit()" ' . ($item['comprado'] ? 'checked' : '') . ' title="Marcar como comprado"></form>';
                                                echo "<span>" . htmlspecialchars($item['item']) . "</span>";
                                                echo '<form action="processa_item.php" method="POST" class="form-remove-item"><input type="hidden" name="acao" value="remover_item"><input type="hidden" name="id_item" value="' . $item['id'] . '"><input type="hidden" name="id_categoria" value="' . $id_cat . '"><button type="submit" onclick="return confirm(\'Tem certeza?\');" class="btn-remover-item">X</button></form>';
                                                echo "</li>";
                                            }
                                        } else { echo "<li class='lista-vazia'>Nenhum item adicionado.</li>"; }
                                        mysqli_stmt_close($stmt_itens);
                                    ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>Você ainda não tem nenhuma lista. Crie uma acima para começar!</p>";
                }
            ?>
            </div>
        </section>
        
        <hr>

        <section class="form-despesa">
            <h2>Nova Despesa</h2>
            <form action="processa_despesa.php" method="POST">
                <input type="hidden" name="acao" value="adicionar">
                <label for="descricao">O que foi comprado?</label><br>
                <input type="text" id="descricao" name="descricao" required><br><br>

                <label for="valor_formatado">Valor (R$):</label><br>
                <input type="text" id="valor_formatado" placeholder="0,00" required>
                <input type="hidden" id="valor" name="valor"><br><br>

                <label for="data_compra">Data da Compra:</label><br>
                <input type="date" id="data_compra" name="data_compra" required><br><br>

                <button type="submit">Adicionar Despesa</button>
            </form>
        </section>

        <hr>

        <section class="link-historico">
            <h2>Histórico de Despesas</h2>
            <p>Consulte e gerencie todas as suas despesas passadas.</p>
            <a href="historico.php" class="btn-principal">Ver Histórico Completo</a>
        </section>
    </main>

    <?php
    mysqli_close($conexao); 
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // --- LÓGICA PARA ABRIR E FECHAR OS MODAIS ---
            // ... (o código para abrir/fechar modais continua o mesmo)
            const gatilhos = document.querySelectorAll('.card-categoria-gatilho');
            gatilhos.forEach(gatilho => gatilho.addEventListener('click', function() {
                const modal = document.getElementById(this.dataset.modalAlvo);
                if(modal) modal.style.display = 'block';
            }));
            const botoesFechar = document.querySelectorAll('.modal-fechar');
            botoesFechar.forEach(botao => botao.addEventListener('click', function() {
                this.closest('.modal').style.display = 'none';
            }));
            window.addEventListener('click', function(event) {
                if (event.target.classList.contains('modal')) event.target.style.display = 'none';
            });
            
            // --- LÓGICA AJAX PARA ADICIONAR E REMOVER ITENS ---
            const containerListas = document.querySelector('.container-listas');

            containerListas.addEventListener('submit', function(event) {
                const form = event.target;
                
                // --- AÇÃO AJAX: ADICIONAR ITEM ---
                if (form.classList.contains('form-add-item')) {
                    event.preventDefault(); // Impede o recarregamento
                    const formData = new FormData(form);
                    const input = form.querySelector('input[type="text"]');
                    const listaUl = form.closest('.conteudo-lista-modal').querySelector('ul');

                    fetch('processa_item.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const novoLi = document.createElement('li');
                            novoLi.innerHTML = `
                                <form action="processa_item.php" method="POST" class="form-check-item">
                                    <input type="hidden" name="acao" value="marcar_item">
                                    <input type="hidden" name="id_item" value="${data.item.id}">
                                    <input type="hidden" name="id_categoria" value="${data.item.id_categoria}">
                                    <input type="checkbox" onchange="this.form.submit()" title="Marcar como comprado">
                                </form>
                                <span>${data.item.nome}</span>
                                <form action="processa_item.php" method="POST" class="form-remove-item">
                                    <input type="hidden" name="acao" value="remover_item">
                                    <input type="hidden" name="id_item" value="${data.item.id}">
                                    <input type="hidden" name="id_categoria" value="${data.item.id_categoria}">
                                    <button type="submit" onclick="return confirm('Tem certeza?');" class="btn-remover-item">X</button>
                                </form>
                            `;
                            const listaVaziaMsg = listaUl.querySelector('.lista-vazia');
                            if (listaVaziaMsg) listaVaziaMsg.remove();
                            listaUl.appendChild(novoLi);
                            input.value = '';
                            input.focus();
                        } else {
                            alert(data.message || 'Ocorreu um erro.');
                        }
                    });
                }

                // --- AÇÃO AJAX: REMOVER ITEM ---
                if (form.classList.contains('form-remove-item')) {
                    event.preventDefault(); // Impede o recarregamento
                    const formData = new FormData(form);
                    const listItem = form.closest('li');

                    fetch('processa_item.php', { method: 'POST', body: formData })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Animação de "fade out" antes de remover
                            listItem.style.transition = 'opacity 0.3s ease';
                            listItem.style.opacity = '0';
                            setTimeout(() => {
                                listItem.remove();
                            }, 300);
                        } else {
                            alert(data.message || 'Ocorreu um erro ao remover.');
                        }
                    });
                }
            });
        });
    </script>
    <script>
        // Pega os dois campos do HTML
        const campoValorFormatado = document.getElementById('valor_formatado');
        const campoValorPuro = document.getElementById('valor');

        // Adiciona um "ouvinte" que dispara sempre que o usuário digita algo
        campoValorFormatado.addEventListener('input', function(e) {
            // Pega o valor digitado e remove tudo que não for número
            let valor = e.target.value.replace(/\D/g, '');
            
            // Se não houver nada, não faz nada
            if (valor === '') {
                campoValorPuro.value = '';
                return;
            }

            // Transforma o valor em número e divide por 100 para ter os centavos
            valor = parseFloat(valor) / 100;
            
            // Atualiza o valor no campo escondido, no formato que o banco de dados espera (ex: 1234.50)
            campoValorPuro.value = valor.toFixed(2);
            
            // Formata o número para o padrão de moeda brasileira (BRL)
            const valorFormatado = new Intl.NumberFormat('pt-BR', {
                style: 'currency',
                currency: 'BRL'
            }).format(valor);
            
            // Mostra o valor formatado (ex: R$ 1.234,50) no campo que o usuário vê
            e.target.value = valorFormatado.replace('R$', '').trim(); // Remove o "R$" para o usuário poder digitar mais
        });
    </script>

</body>
</html>