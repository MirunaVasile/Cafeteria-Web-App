<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['id_utilizator'])) {
    
    $id_client = $_SESSION['id_utilizator'];
    $data_rezervare = mysqli_real_escape_string($conexiune, $_POST['data_rezervare']);
    $ora_rezervare = mysqli_real_escape_string($conexiune, $_POST['ora_rezervare']);
    $nr_persoane = intval($_POST['numar_persoane']);
    $observatii = mysqli_real_escape_string($conexiune, $_POST['observatii']);
    
    // Preia mesele selectate (este un array din formular)
    if (isset($_POST['mese_selectate']) && is_array($_POST['mese_selectate'])) {
        // Le unim cu virgulă: "M1, M3"
        $mese_string = implode(",", $_POST['mese_selectate']);
    } else {
        // Dacă nu a selectat nicio masă, îl întoarcem înapoi
        header("Location: rezervare_noua_client.php?eroare=Trebuie să selectezi cel puțin o masă!");
        exit();
    }

    $data_ora_finala = $data_rezervare . ' ' . $ora_rezervare . ':00';

    // 1. Inserăm rezervarea
    $sql_insert = "INSERT INTO rezervari (id_client, data_ora, nr_persoane, mese_selectate, observatii) 
                   VALUES ('$id_client', '$data_ora_finala', '$nr_persoane', '$mese_string', '$observatii')";

    if (mysqli_query($conexiune, $sql_insert)) {
        
        // 2. Adăugăm 10 puncte de fidelitate utilizatorului!
        $sql_puncte = "UPDATE clienti SET puncte_fidelitate = puncte_fidelitate + 10 WHERE id_client = $id_client";
        mysqli_query($conexiune, $sql_puncte);

        // 3. Trimitem utilizatorul la pagina de "Rezervările mele"
        header("Location: rezervarile_mele_client.php?succes=Rezervarea a fost creată! Ai primit 10 puncte.");
        exit();
    } else {
        header("Location: rezervare_noua_client.php?eroare=A apărut o eroare la salvare.");
        exit();
    }
} else {
    header("Location: home_client.php");
}
?>