<?php
session_start(); // Inicia a sessão

// Inicializa a variável de sessão para armazenar comentários
if (!isset($_SESSION['comentarios'])) {
    $_SESSION['comentarios'] = [];
}

// Função para adicionar um comentário
function adicionarComentario($usuario, $comentario, $parent_id = null) {
    $novoComentario = [
        'id' => uniqid(), // Gera um ID único
        'usuario' => $usuario,
        'comentario' => $comentario,
        'data' => date('d/m/Y H:i'), // Data formatada para exibição
        'timestamp' => time(), // Timestamp para ordenação
        'editado' => false,
        'parent_id' => $parent_id, // ID do comentário pai (para respostas)
    ];
    $_SESSION['comentarios'][] = $novoComentario;
}

// Função para editar um comentário
function editarComentario($id, $novoComentario) {
    foreach ($_SESSION['comentarios'] as &$comentario) {
        if ($comentario['id'] === $id) {
            $comentario['comentario'] = $novoComentario;
            $comentario['editado'] = true;
            break;
        }
    }
}

// Função para excluir um comentário
function excluirComentario($id) {
    $_SESSION['comentarios'] = array_filter($_SESSION['comentarios'], function($comentario) use ($id) {
        return $comentario['id'] !== $id;
    });
}

// Processar ações do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
        switch ($action) {
            case 'adicionar':
                if (!empty($_POST['comentario'])) {
                    adicionarComentario('Usuário Teste', $_POST['comentario'], $_POST['parent_id'] ?? null);
                }
                break;
            case 'editar':
                if (!empty($_POST['id']) && !empty($_POST['comentario'])) {
                    editarComentario($_POST['id'], $_POST['comentario']);
                }
                break;
            case 'excluir':
                if (!empty($_POST['id'])) {
                    excluirComentario($_POST['id']);
                }
                break;
        }
    }
    // Redireciona para evitar reenvio do formulário ao recarregar
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

include 'header.php';
?>

<section class="comentarios-container">
    <h2>Comentários & Perguntas</h2>
    
    <!-- Formulário para adicionar comentário principal -->
    <form id="form-comentario" method="POST">
        <input type="hidden" name="action" value="adicionar">
        <textarea name="comentario" required placeholder="Adicione um comentário..."></textarea>
        <button type="submit" class="botao-comentario">
            <img src="img/iconenviar.gif" alt="Enviar">
        </button>
    </form>
    
    <!-- Lista de Comentários -->
    <div id="lista-comentarios">
        <?php
        // Função para ordenar comentários por timestamp (do mais recente para o mais antigo)
        function ordenarComentariosPorData($comentarios) {
            usort($comentarios, function($a, $b) {
                return $b['timestamp'] - $a['timestamp']; // Ordena do mais recente para o mais antigo
            });
            return $comentarios;
        }

        // Função para exibir comentários e respostas
        function exibirComentarios($comentarios, $parent_id = null) {
            foreach ($comentarios as $comentario) {
                if ($comentario['parent_id'] === $parent_id) {
                    echo '
                    <div class="comentario">
                        <div class="comentario-cabecalho">
                            <span class="usuario">' . htmlspecialchars($comentario['usuario']) . '</span>
                            <span class="data">' . htmlspecialchars($comentario['data']) . '</span>
                            ' . ($comentario['editado'] ? '<span class="editado">(Editado)</span>' : '') . '
                        </div>
                        <div class="comentario-texto">
                            ' . htmlspecialchars($comentario['comentario']) . '
                        </div>
                        <div class="comentario-acoes">
                            <button class="btn-responder" data-comentario-id="' . $comentario['id'] . '">Responder</button>
                            <button class="btn-editar" data-comentario-id="' . $comentario['id'] . '">Editar</button>
                            <button class="btn-excluir" data-comentario-id="' . $comentario['id'] . '">X</button>
                        </div>';

                    // Exibir respostas
                    echo '<div class="respostas">';
                    exibirComentarios($comentarios, $comentario['id']);
                    echo '</div>';

                    echo '</div>';
                }
            }
        }

        // Ordenar comentários por data (do mais recente para o mais antigo)
        $comentariosOrdenados = ordenarComentariosPorData($_SESSION['comentarios']);

        // Exibir todos os comentários ordenados
        exibirComentarios($comentariosOrdenados);
        ?>
    </div>
</section>

<script>
// Função para mostrar/ocultar formulário de resposta
document.querySelectorAll('.btn-responder').forEach(btn => {
    btn.addEventListener('click', () => {
        const comentarioId = btn.dataset.comentarioId;
        const formResposta = `
            <form class="form-resposta" method="POST">
                <input type="hidden" name="action" value="adicionar">
                <input type="hidden" name="parent_id" value="${comentarioId}">
                <textarea name="comentario" placeholder="Digite sua resposta..."></textarea>
                <button type="submit">Enviar</button>
            </form>
        `;
        btn.closest('.comentario').insertAdjacentHTML('beforeend', formResposta);
    });
});

// Função para mostrar/ocultar formulário de edição
document.querySelectorAll('.btn-editar').forEach(btn => {
    btn.addEventListener('click', () => {
        const comentarioId = btn.dataset.comentarioId;
        const comentarioTexto = btn.closest('.comentario').querySelector('.comentario-texto').innerText;
        const formEditar = `
            <form class="form-editar" method="POST">
                <input type="hidden" name="action" value="editar">
                <input type="hidden" name="id" value="${comentarioId}">
                <textarea name="comentario">${comentarioTexto}</textarea>
                <button type="submit">Salvar</button>
            </form>
        `;
        btn.closest('.comentario').querySelector('.comentario-texto').innerHTML = formEditar;
    });
});

// Função para excluir comentário
document.querySelectorAll('.btn-excluir').forEach(btn => {
    btn.addEventListener('click', () => {
        if (confirm('Tem certeza que deseja excluir este comentário?')) {
            const comentarioId = btn.dataset.comentarioId;
            const formExcluir = `
                <form method="POST" style="display: none;">
                    <input type="hidden" name="action" value="excluir">
                    <input type="hidden" name="id" value="${comentarioId}">
                    <button type="submit"></button>
                </form>
            `;
            document.body.insertAdjacentHTML('beforeend', formExcluir);
            document.forms[document.forms.length - 1].submit();
        }
    });
});
</script>

<?php include 'footer.php'; ?>