<?php

$conn = include __DIR__ . '/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tempo_cliente = $_POST['tempo_cliente'] ?? null;
    $idade_atendido = $_POST['idade_atendido'] ?? null;
    $servico = $_POST['servico'] ?? null;
    $motivo = $_POST['motivo'] ?? null;

    if ($tempo_cliente && $idade_atendido && $servico && $motivo) {
        $sql = "INSERT INTO avaliacoes (tempo_cliente, idade_atendido, servico, motivo) 
                VALUES ('$tempo_cliente', '$idade_atendido', '$servico', '$motivo')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('Avaliação enviada com sucesso!'); window.location.href='../avaliacoes.php';</script>";

        } else {
            echo "Erro ao enviar avaliação: " . $conn->error;
        }
    } else {
        echo "Preencha todos os campos.";
    }
}

$conn->close();
?>
