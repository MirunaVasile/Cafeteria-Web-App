<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['id_utilizator']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php?eroare=Acces neautorizat!");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titlu = mysqli_real_escape_string($conexiune, $_POST['titlu_document']);
    
    if (isset($_FILES['fisier_regulament']) && $_FILES['fisier_regulament']['error'] == 0) {
        $nume_original = $_FILES['fisier_regulament']['name'];
        $extensie = strtolower(pathinfo($nume_original, PATHINFO_EXTENSION));
        
        $extensii_acceptate = ['pdf', 'doc', 'docx', 'png', 'jpg', 'jpeg'];
        
        if (in_array($extensie, $extensii_acceptate)) {
            $nume_fictiv = time() . "_" . bin2hex(random_bytes(4)) . "." . $extensie;
            $director_destinatie = "uploads/";
            $cale_finala = $director_destinatie . $nume_fictiv;
            
            if (move_uploaded_file($_FILES['fisier_regulament']['tmp_name'], $cale_finala)) {
                $sql = "INSERT INTO regulamente (titlu, cale_fisier) VALUES ('$titlu', '$cale_finala')";
                if (mysqli_query($conexiune, $sql)) {
                    header("Location: home_admin.php?succes=Documentul a fost încărcat și salvat cu succes!");
                } else {
                    header("Location: home_admin.php?eroare=Eroare la înregistrarea în baza de date.");
                }
            } else {
                header("Location: home_admin.php?eroare=Nu s-a putut salva fișierul în folderul uploads.");
            }
        } else {
            header("Location: home_admin.php?eroare=Format neacceptat! Trimite doar PDF, DOCX sau imagini.");
        }
    } else {
        header("Location: home_admin.php?eroare=Eroare tehnică la recepționarea fișierului.");
    }
    exit();
}
?>