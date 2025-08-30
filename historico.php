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
    <title>Histórico de Despesas</title>
</head>
<body>
   <header>
        <div class="container-header">
            <h1>Histórico de Despesas</h1>
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
        <div style="margin-bottom: 20px;">
            <a href="index.php" class="btn-voltar">← Voltar para a Página Principal</a>
        </div>

        <section class="historico-despesas">
            <?php
            if (isset($_GET['mes_filtro']) && !empty($_GET['mes_filtro'])) {
                $filtro_mes = $_GET['mes_filtro'];
            } else {
                $filtro_mes = date('Y-m'); 
            }
            ?>

            <form method="GET" action="historico.php" class="form-filtro-custom">
                <label for="mes_filtro" style="font-weight: bold;">Filtrar por Mês:</label>
                <div class="dropdown-custom">
                    <div class="dropdown-selecionado">
                        <?php
                            $formatter_filtro = new IntlDateFormatter('pt_BR', IntlDateFormatter::LONG, IntlDateFormatter::NONE, null, null, 'MMMM \'de\' yyyy');
                            echo ucfirst($formatter_filtro->format(strtotime($filtro_mes . '-01')));
                        ?>
                    </div>
                    <div class="dropdown-opcoes">
                        <a href="historico.php">-- Mês Atual --</a>
                        <?php
                            $sql_meses = "SELECT DISTINCT DATE_FORMAT(data_compra, '%Y-%m') AS mes FROM despesas WHERE usuario_id = ? ORDER BY mes DESC";
                            $stmt_meses = mysqli_prepare($conexao, $sql_meses);
                            mysqli_stmt_bind_param($stmt_meses, "i", $_SESSION['usuario_id']);
                            mysqli_stmt_execute($stmt_meses);
                            $resultado_meses = mysqli_stmt_get_result($stmt_meses);
                            while ($row = mysqli_fetch_assoc($resultado_meses)) {
                                $mes_valor = $row['mes'];
                                $mes_texto = ucfirst($formatter_filtro->format(strtotime($mes_valor . '-01')));
                                echo "<a href='historico.php?mes_filtro={$mes_valor}'>{$mes_texto}</a>";
                            }
                        ?>
                    </div>
                </div>
            </form>

            <?php
            $usuario_id = $_SESSION['usuario_id'];
            $sql_despesas = "SELECT * FROM despesas WHERE usuario_id = ? AND DATE_FORMAT(data_compra, '%Y-%m') = ? ORDER BY data_compra DESC";
            $stmt_despesas = mysqli_prepare($conexao, $sql_despesas);
            mysqli_stmt_bind_param($stmt_despesas, "is", $usuario_id, $filtro_mes);
            mysqli_stmt_execute($stmt_despesas);
            $resultado_despesas = mysqli_stmt_get_result($stmt_despesas);
            
            if (mysqli_num_rows($resultado_despesas) == 0) {
                echo "<p>Nenhuma despesa encontrada para o período selecionado.</p>";
            } else {
                $despesas_do_mes = mysqli_fetch_all($resultado_despesas, MYSQLI_ASSOC);
                
                echo "<h3>" . ucfirst($formatter_filtro->format(strtotime($filtro_mes . '-01'))) . "</h3>";
                echo '<div class="tabela-container">';
                echo "<table class='tabela-historico'>";
                echo "<thead><tr><th>Data</th><th>O que foi comprado?</th><th>Valor</th><th>Ações</th></tr></thead>";
                echo "<tbody>";
                $total_mes = 0;
                foreach ($despesas_do_mes as $d) {
                    $data_formatada = date('d/m/Y', strtotime($d['data_compra']));
                    $valor_formatado = number_format($d['valor'], 2, ',', '.');
                    echo "<tr>";
                    echo "<td data-label='Data'>" . $data_formatada . "</td>";
                    echo "<td data-label='O que foi comprado?'>" . htmlspecialchars($d['descricao']) . "</td>";
                    echo "<td data-label='Valor'>R$ " . $valor_formatado . "</td>";
                    echo "<td data-label='Ações'>";
                    echo '  <form action="processa_despesa.php" method="POST" style="display: inline;"><input type="hidden" name="acao" value="remover"><input type="hidden" name="id_despesa" value="' . $d['id'] . '"><button type="submit" onclick="return confirm(\'Tem certeza que deseja apagar esta despesa?\');">Apagar</button></form>';
                    echo "</td>";
                    echo "</tr>";
                    $total_mes += $d['valor'];
                }
                echo "</tbody>";
                echo "<tfoot><tr><td colspan='3' style='text-align: right;'><strong>Total do Mês:</strong></td><td><strong>R$ " . number_format($total_mes, 2, ',', '.') . "</strong></td></tr></tfoot>";
                echo "</table>";
                echo '</div><br>';
            }
            ?>
        </section>
    </main>

    <?php
    mysqli_close($conexao); 
    ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdown = document.querySelector('.dropdown-custom');
            if (dropdown) {
                const selecionado = dropdown.querySelector('.dropdown-selecionado');
                selecionado.addEventListener('click', function(event) {
                    event.stopPropagation();
                    dropdown.classList.toggle('aberto');
                });
            }
        });
        window.addEventListener('click', function() {
            const dropdownAberto = document.querySelector('.dropdown-custom.aberto');
            if (dropdownAberto) {
                dropdownAberto.classList.remove('aberto');
            }
        });
    </script>
</body>
</html>