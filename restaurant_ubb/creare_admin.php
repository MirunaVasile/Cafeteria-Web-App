<?php
require_once 'config.php';

// 1. Ștergem contul de admin greșit pe care l-am băgat adineauri (ca să nu avem duplicate)
mysqli_query($conexiune, "DELETE FROM clienti WHERE email='admin@admin.ro'");

// 2. Lăsăm PHP-ul să cripteze cuvântul 'admin' corect și matematic
$parola_buna = password_hash('admin', PASSWORD_DEFAULT);

// 3. Inserăm adminul cel bun
$sql = "INSERT INTO clienti (username, email, parola, rol) 
        VALUES ('Administrator', 'admin@admin.ro', '$parola_buna', 'admin')";

if (mysqli_query($conexiune, $sql)) {
    echo "<h2 style='color: green;'>Gata! Contul a fost creat corect!</h2>";
    echo "<p>Acum întoarce-te la <b>index.php</b> și loghează-te cu <b>admin@admin.ro</b> și parola <b>admin</b>.</p>";
} else {
    echo "Eroare: " . mysqli_error($conexiune);
}
?>