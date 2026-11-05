<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conexiune, $_POST['email']);
    $parola_introdusa = $_POST['parola'];

    // Căutăm utilizatorul după email
    $sql = "SELECT id_client, username, parola, rol FROM clienti WHERE email = '$email'";
    $rezultat = mysqli_query($conexiune, $sql);

    if ($rand = mysqli_fetch_assoc($rezultat)) {
        // Verificăm parola criptată
        if (password_verify($parola_introdusa, $rand['parola'])) {
            // Salvăm datele în sesiune (Persistența sesiunii)
            $_SESSION['id_utilizator'] = $rand['id_client'];
            $_SESSION['username'] = $rand['username'];
            $_SESSION['rol'] = $rand['rol'];

            // Redirecționare în funcție de rol (Cerința: Acces pe bază de rol)
            if ($rand['rol'] == 'admin') {
                header("Location: home_admin.php");
            } else {
                header("Location: home_client.php");
            }
            exit();
        } else {
            header("Location: index.php?eroare=Parola incorecta!");
        }
    } else {
        header("Location: index.php?eroare=Utilizatorul nu exista!");
    }
}
?>