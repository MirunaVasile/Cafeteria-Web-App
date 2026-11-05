<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['id_utilizator'])) {
    $id_client = $_SESSION['id_utilizator'];
    $nota = intval($_POST['nota']);
    $comentariu = mysqli_real_escape_string($conexiune, $_POST['comentariu']);

    $sql = "INSERT INTO recenzii (id_client, nota, comentariu) VALUES ('$id_client', '$nota', '$comentariu')";
    
    if (mysqli_query($conexiune, $sql)) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($conexiune)]);
    }
}
?>