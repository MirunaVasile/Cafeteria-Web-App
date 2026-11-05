<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['id_utilizator'])) {
    
    $id_client = $_SESSION['id_utilizator']; 

    $username = mysqli_real_escape_string($conexiune, $_POST['nume']);
    $telefon = mysqli_real_escape_string($conexiune, $_POST['telefon']);
    $judet = mysqli_real_escape_string($conexiune, $_POST['judet']);
    $oras = mysqli_real_escape_string($conexiune, $_POST['oras']);
    $gen = mysqli_real_escape_string($conexiune, $_POST['gen']);
    $preferinte = mysqli_real_escape_string($conexiune, $_POST['pref']);

    $sql_poza = ""; 
    
    if (isset($_FILES['poza_profil']) && $_FILES['poza_profil']['error'] == 0) {
        $nume_poza = time() . "_" . basename($_FILES['poza_profil']['name']);
        $cale_poza = "uploads/" . $nume_poza;
        
        if (move_uploaded_file($_FILES['poza_profil']['tmp_name'], $cale_poza)) {
            $sql_poza = ", poza_profil = '$cale_poza'";
        }
    }
    // --------------------------------------------------

    $sql = "UPDATE clienti SET 
            username = '$username', 
            telefon = '$telefon', 
            judet = '$judet', 
            oras = '$oras', 
            gen = '$gen', 
            preferinte_culinare = '$preferinte'
            $sql_poza"; 

    if (!empty($_POST['parola'])) {
        $parola_noua_criptata = password_hash($_POST['parola'], PASSWORD_DEFAULT);
        $sql .= ", parola = '$parola_noua_criptata'"; 
    }

    $sql .= " WHERE id_client = $id_client";

    if (mysqli_query($conexiune, $sql)) {
        header("Location: cont_client.php?succes=Datele au fost actualizate cu succes!");
        exit();
    } else {
        header("Location: cont_client.php?eroare=Eroare la baza de date: " . mysqli_error($conexiune));
        exit();
    }
} else {
    header("Location: cont_client.php");
    exit();
}
?>