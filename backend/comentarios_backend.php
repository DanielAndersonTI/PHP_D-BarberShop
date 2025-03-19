<?php
include 'config.php';

// Adicionar um novo comentário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['comentario'])) {
    $comentario = htmlspecialchars($_POST['comentario']);
    $sql = "INSERT INTO comentarios (texto) VALUES ('$comentario')";
    $conn->query($sql);
}

// Deletar comentário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deletar_id'])) {
    $id = intval($_POST['deletar_id']);
    $sql = "DELETE FROM comentarios WHERE id = $id";
    $conn->query($sql);
}

// Adicionar resposta a um comentário
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['resposta']) && isset($_POST['comentario_id'])) {
    $comentario_id = intval($_POST['comentario_id']);
    $resposta = htmlspecialchars($_POST['resposta']);
    $sql = "INSERT INTO respostas (comentario_id, resposta) VALUES ('$comentario_id', '$resposta')";
    $conn->query($sql);
}

// Exibir comentários e respostas
$result = $conn->query("SELECT * FROM comentarios ORDER BY id DESC");
while ($row = $result->fetch_assoc()) {
    echo "<div class='comentario'>
            <p>" . $row['texto'] . "</p>
            <form action='comentarios_backend.php' method='POST' style='display:inline;'>
                <input type='hidden' name='deletar_id' value='" . $row['id'] . "'>
                <button type='submit' class='botao-deletar'>X</button>
            </form>
            <button class='botao-responder' onclick='mostrarFormulario(" . $row['id'] . ")'>Responder</button>
            <div id='responder-" . $row['id'] . "' style='display:none;'>
                <form action='comentarios_backend.php' method='POST'>
                    <input type='hidden' name='comentario_id' value='" . $row['id'] . "'>
                    <textarea name='resposta' required placeholder='Escreva sua resposta...'></textarea>
                    <button type='submit' class='botao-comentario'>Responder</button>
                </form>
            </div>
          </div>";

    // Exibir respostas
    $respostas = $conn->query("SELECT * FROM respostas WHERE comentario_id = " . $row['id']);
    while ($resp = $respostas->fetch_assoc()) {
        echo "<div class='resposta'> → " . $resp['resposta'] . "</div>";
    }
}
?>
