<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['id_utilizator']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id_rez = intval($_GET['id']);
    $status_nou = mysqli_real_escape_string($conexiune, $_GET['status']);

    if (in_array($status_nou, ['acceptata', 'respinsa'])) {
        $sql = "UPDATE rezervari SET status_rezervare = '$status_nou' WHERE id_rezervare = $id_rez";
        
        if (mysqli_query($conexiune, $sql)) {
            header("Location: vizualizare_rezervari_admin.php?succes=Statusul rezervării a fost actualizat.");
        } else {
            header("Location: vizualizare_rezervari_admin.php?eroare=Eroare la actualizarea statusului.");
        }
    } else {
        header("Location: vizualizare_rezervari_admin.php?eroare=Status invalid.");
    }
    exit();
} else {
    header("Location: vizualizare_rezervari_admin.php");
    exit();
}
?>