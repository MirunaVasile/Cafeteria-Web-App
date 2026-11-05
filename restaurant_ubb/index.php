<?php
session_start();

if (isset($_SESSION['id_utilizator'])) {
    if ($_SESSION['rol'] == 'admin') {
        header("Location: home_admin.php");
    } else {
        header("Location: home_client.php");
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autentificare - Restaurant UBB</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="pagina-auth">

    <header style="justify-content: center;">
        <img src="https://cdn-icons-png.flaticon.com/512/3075/3075977.png" alt="Logo">
        <div>
            <h1>Restaurant UBB</h1>
            <p>Sistem de Gestiune și Rezervări</p>
        </div>
    </header>

    <main class="auth-container">
        <section class="setari-cont">
            <h2 style="text-align: center; color: var(--culoare-primara); margin-bottom: 20px;">
                <i class="fa-solid fa-right-to-bracket"></i> Conectare
            </h2>

            <?php if (isset($_GET['succes'])): ?>
                <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
                    <?php echo htmlspecialchars($_GET['succes']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['eroare'])): ?>
                <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
                    <?php echo htmlspecialchars($_GET['eroare']); ?>
                </div>
            <?php endif; ?>

            <form action="procesare_login.php" method="POST">
                <div class="input-iconita" style="margin-bottom: 20px;">
                    <label for="email_id">E-mail:</label>
                    <i class="fa-solid fa-envelope" style="bottom: 14px;"></i>
                    <input type="email" name="email" id="email_id" required placeholder="adresa@exemplu.ro">
                </div>

                <div class="input-iconita" style="margin-bottom: 20px;">
                    <label for="parola_id">Parolă:</label>
                    <i class="fa-solid fa-lock" style="bottom: 14px;"></i>
                    <input type="password" name="parola" id="parola_id" required placeholder="********">
                </div>

                <button type="submit" class="btn btn-primar" style="width: 100%;">
                    <i class="fa-solid fa-right-to-bracket"></i> Autentificare
                </button>
            </form>

            <hr style="margin: 25px 0; border: 0; border-top: 1px solid #ecf0f1;">

            <div style="text-align: center;">
                <p style="margin-bottom: 10px; color: #7f8c8d;">Nu ai cont?</p>
                <a href="inregistrare.php" class="btn" style="background-color: #bdc3c7; color: #2c3e50;">
                    <i class="fa-solid fa-user-plus"></i> Creează unul acum
                </a>
            </div>
        </section>
    </main>

    <footer style="margin-top: auto;">
        <p>&copy; Laborator Programare Web - Vasile Miruna</p>
    </footer>

    <script src="script.js?v=2"></script>
</body>
</html>