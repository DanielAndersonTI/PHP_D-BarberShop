<?php
include 'db_connect.php'; // Conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tempo_cliente = $_POST['tempo_cliente'];
    $idade_atendido = $_POST['idade_atendido'];
    $servico = $_POST['servico'];
    $motivo = $_POST['motivo'];

    $sql = "INSERT INTO avaliacoes (tempo_cliente, idade_atendido, servico, motivo) 
            VALUES ('$tempo_cliente', '$idade_atendido', '$servico', '$motivo')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Avaliação enviada com sucesso!'); window.location.href='avaliacoes.php';</script>";
    } else {
        echo "Erro ao enviar avaliação: " . $conn->error;
    }

    $conn->close();
}
?>
