<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['comentario'])) {
        $comentario = htmlspecialchars($_POST['comentario']);
        $sql = "INSERT INTO comentarios (texto) VALUES ('$comentario')";
        $conn->query($sql);
    } elseif (isset($_POST['deletar_id'])) {
        $id = intval($_POST['deletar_id']);
        $sql = "DELETE FROM comentarios WHERE id = $id";
        $conn->query($sql);
    } elseif (isset($_POST['resposta']) && isset($_POST['comentario_id'])) {
        $comentario_id = intval($_POST['comentario_id']);
        $resposta = htmlspecialchars($_POST['resposta']);
        $sql = "INSERT INTO respostas (comentario_id, resposta) VALUES ('$comentario_id', '$resposta')";
        $conn->query($sql);
    }
}

header("Location: comentarios.php");
exit;
?>
