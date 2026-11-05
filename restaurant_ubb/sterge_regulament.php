<?php
session_start();
require_once 'config.php';

// Securitate admin
if (!isset($_SESSION['id_utilizator']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    $sql_cauta = "SELECT cale_fisier FROM regulamente WHERE id_regulament = $id";
    $rez = mysqli_query($conexiune, $sql_cauta);
    
    if ($doc = mysqli_fetch_assoc($rez)) {
        $cale_fizica = $doc['cale_fisier'];
        
        if (file_exists($cale_fizica)) {
            unlink($cale_fizica); // Această funcție șterge fișierul de pe disc!
        }
        
        mysqli_query($conexiune, "DELETE FROM regulamente WHERE id_regulament = $id");
        header("Location: home_admin.php?succes=Regulamentul a fost eliminat complet (server + DB).");
    } else {
        header("Location: home_admin.php?eroare=Documentul nu a fost găsit.");
    }
    exit();
}
?>