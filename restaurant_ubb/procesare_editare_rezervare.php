<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['id_utilizator'])) {
    $id_rez = intval($_POST['id_rezervare']);
    $id_client = $_SESSION['id_utilizator'];
    $data_ora = $_POST['data_rezervare'] . ' ' . $_POST['ora_rezervare'] . ':00';
    $pers = intval($_POST['numar_persoane']);
    
    if (isset($_POST['mese_selectate'])) {
        $mese_string = implode(",", $_POST['mese_selectate']);
    } else {
        header("Location: editare_rezervare.php?id=$id_rez&eroare=Selectează o masă!");
        exit();
    }

    $sql = "UPDATE rezervari SET 
            data_ora = '$data_ora', 
            nr_persoane = $pers, 
            mese_selectate = '$mese_string',
            status_rezervare = 'in asteptare' 
            WHERE id_rezervare = $id_rez AND id_client = $id_client";

    if (mysqli_query($conexiune, $sql)) {
        header("Location: rezervarile_mele_client.php?succes=Rezervarea a fost modificată.");
    } else {
        header("Location: rezervarile_mele_client.php?eroare=Eroare la actualizare.");
    }
}
?>