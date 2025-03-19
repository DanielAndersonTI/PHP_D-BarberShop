<?php
include 'config.php'; // Inclui a conexão correta

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['comentario'])) {
        // Adicionar um novo comentário
        $nome = htmlspecialchars($_POST['nome'] ?? 'Anônimo'); // Nome do usuário
        $comentario = htmlspecialchars($_POST['comentario']);
        
        $stmt = $conn->prepare("INSERT INTO comentarios (nome, texto) VALUES (?, ?)");
        $stmt->bind_param("ss", $nome, $comentario);
        $stmt->execute();
        $stmt->close();
        
    } elseif (isset($_POST['deletar_id'])) {
        // Excluir um comentário
        $id = intval($_POST['deletar_id']);
        
        $stmt = $conn->prepare("DELETE FROM comentarios WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        
    } elseif (isset($_POST['resposta']) && isset($_POST['comentario_id'])) {
        // Adicionar uma nova resposta
        $nome = htmlspecialchars($_POST['nome'] ?? 'Anônimo'); // Nome do usuário
        $comentario_id = intval($_POST['comentario_id']);
        $resposta = htmlspecialchars($_POST['resposta']);
        
        $stmt = $conn->prepare("INSERT INTO respostas (comentario_id, nome, resposta) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $comentario_id, $nome, $resposta);
        $stmt->execute();
        $stmt->close();
    }
}

// Recarrega a página automaticamente
header("Location: comentarios.php");
exit;
?>
