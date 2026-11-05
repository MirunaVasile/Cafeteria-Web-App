<?php
session_start();
require_once 'config.php';

if (isset($_GET['id']) && isset($_SESSION['id_utilizator'])) {
    $id_rez = intval($_GET['id']);
    $id_client = $_SESSION['id_utilizator'];

    // Ștergem rezervarea doar dacă aparține clientului logat (securitate)
    $sql = "DELETE FROM rezervari WHERE id_rezervare = $id_rez AND id_client = $id_client";
    
    if (mysqli_query($conexiune, $sql)) {
        header("Location: rezervarile_mele_client.php?succes=Rezervarea a fost anulată cu succes.");
    } else {
        header("Location: rezervarile_mele_client.php?eroare=Eroare la ștergerea rezervării.");
    }
    exit();
}
?>