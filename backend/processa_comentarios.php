<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['comentario'])) {
        // Adicionar um novo comentário
        $nome = htmlspecialchars($_POST['nome'] ?? 'Anônimo'); // Nome do usuário
        $comentario = htmlspecialchars($_POST['comentario']);
        $sql = "INSERT INTO comentarios (nome, texto) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nome, $comentario);
        $stmt->execute();
    } elseif (isset($_POST['deletar_id'])) {
        // Excluir um comentário
        $id = intval($_POST['deletar_id']);
        $sql = "DELETE FROM comentarios WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } elseif (isset($_POST['resposta']) && isset($_POST['comentario_id'])) {
        // Adicionar uma nova resposta
        $nome = htmlspecialchars($_POST['nome'] ?? 'Anônimo'); // Nome do usuário
        $comentario_id = intval($_POST['comentario_id']);
        $resposta = htmlspecialchars($_POST['resposta']);
        $sql = "INSERT INTO respostas (comentario_id, nome, resposta) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $comentario_id, $nome, $resposta);
        $stmt->execute();
    }
}

// Recarrega a página automaticamente
header("Location: ../comentarios.php");
exit;
?>
