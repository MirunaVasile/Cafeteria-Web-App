<?php
// 1. Activăm raportarea erorilor pentru depanare
error_reporting(E_ALL);
ini_set('display_errors', 1);
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// 2. Includem configurarea bazei de date și pornim sesiunea
require_once 'config.php';
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 3. Preluăm și curățăm datele standard
    $user = mysqli_real_escape_string($conexiune, $_POST['username']);
    $porecla = mysqli_real_escape_string($conexiune, $_POST['porecla']);
    $email = mysqli_real_escape_string($conexiune, $_POST['mail_utilizator']);
    $telefon = mysqli_real_escape_string($conexiune, $_POST['telefon']);
    $varsta = intval($_POST['varsta']);
    $oras = mysqli_real_escape_string($conexiune, $_POST['oras']);
    $gen = mysqli_real_escape_string($conexiune, $_POST['gen']);
    $ocupatie = mysqli_real_escape_string($conexiune, $_POST['ocupatie']);
    
    // --- NOU: LOGICA PENTRU DATA DE NAȘTERE ---
    $data_bruta = $_POST['data_nastere'];
    // Asigură-te că în HTML ai pus name="format_data" la acel <select>
    $format = isset($_POST['format_data']) ? $_POST['format_data'] : 'zz/ll/aaaa'; 
    
    $parti = explode('/', $data_bruta);
    
    if (count($parti) == 3) {
        if ($format == 'zz/ll/aaaa') {
            $data_n_formatata = $parti[2] . '-' . $parti[1] . '-' . $parti[0];
        } elseif ($format == 'll/zz/aaaa') {
            $data_n_formatata = $parti[2] . '-' . $parti[0] . '-' . $parti[1];
        } elseif ($format == 'zz/ll/aa') {
            $an = intval($parti[2]);
            $an = ($an < 100) ? $an + 2000 : $an; 
            $data_n_formatata = $an . '-' . $parti[1] . '-' . $parti[0];
        } else {
            $data_n_formatata = '2000-01-01'; // Fallback de siguranță
        }
    } else {
        $data_n_formatata = '2000-01-01'; // Fallback dacă utilizatorul nu pune '/'
    }

    // Securizăm data finală calculată
    $data_n = mysqli_real_escape_string($conexiune, $data_n_formatata);
    // ------------------------------------------

    // 4. Criptăm parola
    $parola = password_hash($_POST['parola_noua'], PASSWORD_DEFAULT);

    // 5. Verificăm dacă utilizatorul sau email-ul există deja
    $verificare = "SELECT * FROM clienti WHERE username='$user' OR email='$email'";
    $rezultat_v = mysqli_query($conexiune, $verificare);

    if (mysqli_num_rows($rezultat_v) > 0) {
        header("Location: inregistrare.php?eroare=Utilizator sau email deja existent!");
        exit();
    } else {
        // 6. Inserăm datele în tabelul SQL (fără nume și prenume, cum ai cerut)
        $sql = "INSERT INTO clienti (username, porecla, email, parola, telefon, data_nastere, varsta, oras, gen, ocupatie, rol) 
                VALUES ('$user', '$porecla', '$email', '$parola', '$telefon', '$data_n', '$varsta', '$oras', '$gen', '$ocupatie', 'client')";

        if (mysqli_query($conexiune, $sql)) {
            header("Location: index.php?succes=Contul a fost creat cu succes!");
            exit();
        } else {
            header("Location: inregistrare.php?eroare=Eroare la salvarea în baza de date.");
            exit();
        }
    }
} else {
    header("Location: inregistrare.php");
    exit();
}
?>