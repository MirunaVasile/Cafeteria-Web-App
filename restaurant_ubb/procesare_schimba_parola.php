<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['id_utilizator'])) {
    $id = $_SESSION['id_utilizator'];
    $veche = $_POST['parola_veche'];
    $noua = $_POST['parola_noua'];

    // 1. Luăm parola hash-uită din DB
    $sql = "SELECT parola FROM clienti WHERE id_client = $id";
    $rez = mysqli_query($conexiune, $sql);
    $user = mysqli_fetch_assoc($rez);

    // 2. Verificăm dacă parola veche e corectă
    if (password_verify($veche, $user['parola'])) {
        // 3. Salvăm noua parolă (hash-uită)
        $hash = password_hash($noua, PASSWORD_DEFAULT);
        mysqli_query($conexiune, "UPDATE clienti SET parola = '$hash' WHERE id_client = $id");
        header("Location: cont_client.php?succes=Parola a fost actualizată!");
    } else {
        header("Location: cont_client.php?eroare=Parola curentă este greșită!");
    }
    exit();
}