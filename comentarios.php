<?php
session_start();
include 'config.php'; // Conexão com o banco de dados

// 🔹 Buscar todos os comentários do banco de dados
$sql = "SELECT * FROM comentarios ORDER BY data_criacao DESC";
$result = $conn->query($sql);
$comentarios = [];

while ($row = $result->fetch_assoc()) {
    $comentarios[] = [
        'id' => $row['id'],
        'usuario' => $row['nome'] ?? 'Anônimo',
        'comentario' => $row['texto'],
        'data' => date('d/m/Y H:i', strtotime($row['data_criacao'])),
        'timestamp' => strtotime($row['data_criacao']),
        'editado' => false,
        'parent_id' => null
    ];
}

// 🔹 Buscar respostas e associá-las aos comentários principais
$sql_respostas = "SELECT * FROM respostas ORDER BY data_criacao ASC";
$result_respostas = $conn->query($sql_respostas);
$respostas = [];

while ($row = $result_respostas->fetch_assoc()) {
    $respostas[] = [
        'id' => $row['id'],
        'usuario' => $row['nome'] ?? 'Anônimo',
        'comentario' => $row['resposta'],
        'data' => date('d/m/Y H:i', strtotime($row['data_criacao'])),
        'timestamp' => strtotime($row['data_criacao']),
        'editado' => false,
        'parent_id' => $row['comentario_id']
    ];
}

// 🔹 Unindo os comentários e respostas no mesmo array
$comentarios = array_merge($comentarios, $respostas);

// 🔹 Ordenar por data (do mais recente para o mais antigo)
usort($comentarios, function ($a, $b) {
    return $b['timestamp'] - $a['timestamp'];
});

// 🔹 Função para exibir comentários e respostas
function exibirComentarios($comentarios, $parent_id = null) {
    foreach ($comentarios as $comentario) {
        if ($comentario['parent_id'] === $parent_id) {
            echo '
            <div class="comentario" data-comentario-id="' . $comentario['id'] . '">
                <div class="comentario-cabecalho">
                    <span class="usuario">' . htmlspecialchars($comentario['usuario']) . '</span>
                    <span class="data">' . htmlspecialchars($comentario['data']) . '</span>
                    ' . ($comentario['editado'] ? '<span class="editado">(Editado)</span>' : '') . '
                </div>
                <div class="comentario-texto">' . htmlspecialchars($comentario['comentario']) . '</div>
                <div class="comentario-acoes">
                    <form method="POST" action="processa_comentarios.php" style="display: inline;">
                        <input type="hidden" name="deletar_id" value="' . $comentario['id'] . '">
                        <button class="btn-excluir" type="submit">X</button>
                    </form>
                    <form method="POST" action="processa_comentarios.php" style="display: inline;">
                        <input type="hidden" name="comentario_id" value="' . $comentario['id'] . '">
                        <input type="text" name="nome" placeholder="Seu nome" required>
                        <textarea name="resposta" rows="3" placeholder="Digite sua resposta..." required style="resize: vertical; width: 100%;"></textarea>
                        <button class="btn-responder" type="submit">Responder</button>
                    </form>
                </div>
                <div class="respostas">';
            exibirComentarios($comentarios, $comentario['id']);
            echo '</div></div>';
        }
    }
}

include 'header.php';
?>

<section class="comentarios-container">
    <h2>Comentários & Perguntas</h2>
    <h3>Este é um espaço para comentários sobre a barbearia e dúvidas relacionadas a qualquer assunto do visual masculino. 
        Pedimos a todos que sejam respeitosos e evitem comentários ofensivos.
    </h3>
    
    <!-- Formulário para adicionar comentário principal -->
    <form id="form-comentario" method="POST" action="processa_comentarios.php">
        <input type="text" name="nome" placeholder="Seu nome" required>
        <textarea name="comentario" rows="4" required placeholder="Adicione um comentário..." style="resize: vertical; width: 100%;"></textarea>
        <button type="submit" class="botao-comentario">
            <img src="img/iconenviar.gif" alt="Enviar">
        </button>
    </form>
    
    <!-- Lista de Comentários -->
    <div id="lista-comentarios">
        <?php exibirComentarios($comentarios); ?>
    </div>
</section>

<script>
    // Salva a posição da rolagem antes de recarregar a página
    window.addEventListener("beforeunload", function () {
        localStorage.setItem("scrollPosition", window.scrollY);
    });

    // Restaura a posição da rolagem ao carregar a página
    window.addEventListener("load", function () {
        const scrollPosition = localStorage.getItem("scrollPosition");
        if (scrollPosition) {
            window.scrollTo(0, parseInt(scrollPosition, 10));
        }
    });
</script>

<?php include 'footer.php'; ?>
