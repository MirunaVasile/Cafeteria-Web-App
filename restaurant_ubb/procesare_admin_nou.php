<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin') {
    
    $nume = mysqli_real_escape_string($conexiune, $_POST['nume_admin']);
    $email = mysqli_real_escape_string($conexiune, $_POST['email_admin']);
    $parola = password_hash($_POST['parola_admin'], PASSWORD_DEFAULT); // Criptăm parola

    $check = "SELECT * FROM clienti WHERE email = '$email'";
    if (mysqli_num_rows(mysqli_query($conexiune, $check)) > 0) {
        header("Location: home_admin.php?eroare=Acest e-mail este deja folosit!");
        exit();
    }

    $sql = "INSERT INTO clienti (username, email, parola, rol) 
            VALUES ('$nume', '$email', '$parola', 'admin')";

    if (mysqli_query($conexiune, $sql)) {
        header("Location: home_admin.php?succes=Noul Administrator a fost creat cu succes!");
    } else {
        header("Location: home_admin.php?eroare=Eroare la salvare: " . mysqli_error($conexiune));
    }
    exit();

} else {
    header("Location: index.php");
    exit();
}
?>