<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['id_utilizator']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php?eroare=Acces interzis! Doar administratorii au acces aici.");
    exit();
}

$sql = "SELECT r.*, c.username, c.telefon, c.email, c.poza_profil 
        FROM rezervari r 
        JOIN clienti c ON r.id_client = c.id_client 
        ORDER BY r.data_ora DESC";
$rezultat = mysqli_query($conexiune, $sql);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrare Rezervări - Restaurant UBB</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header>
        <img src="https://cdn-icons-png.flaticon.com/512/3075/3075977.png" alt="Logo">
        <div>
            <h1>Toate rezervările</h1>
            <p>Panou de gestionare a solicitărilor primite</p>
        </div>
    </header>

    <div class="layout-principal">
        <nav>
            <h2>Navigare Admin</h2>
            <ul>
                <li><a href="home_admin.php">Acasă Admin</a></li>
                <li><a href="vizualizare_rezervari_admin.php"><strong>Vizualizare Rezervări</strong></a></li>
                <li><a href="logout.php" style="color: #e74c3c; font-weight: bold;"><i class="fa-solid fa-power-off"></i> Ieșire din sistem</a></li>
            </ul>
        </nav>

        <main>
            <section>
                <h2>Toate Rezervările Clienților</h2>
                <p>Gestionați mai jos cererile primite.</p>

                <?php if (isset($_GET['succes'])): ?>
                    <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                        <i class="fa-solid fa-check-circle"></i> <?php echo htmlspecialchars($_GET['succes']); ?>
                    </div>
                <?php endif; ?>

                <table class="tabel-stilizat tabel-sortabil">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Detalii Rezervare</th>
                            <th>Status</th>
                            <th class="fara-sortare">Acțiuni Administrative</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($rezultat) > 0): ?>
                            <?php while ($rand = mysqli_fetch_assoc($rezultat)): ?>
                                <?php
                                    $data_formata = date('d.m.Y - H:i', strtotime($rand['data_ora']));
                                    
                                    $poza_client = !empty($rand['poza_profil']) ? $rand['poza_profil'] : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';

                                    if ($rand['status_rezervare'] == 'acceptata') {
                                        $status_html = '<span class="status-tag confirmat">Acceptată</span>';
                                    } elseif ($rand['status_rezervare'] == 'respinsa') {
                                        $status_html = '<span class="status-tag" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">Respinsă</span>';
                                    } else {
                                        $status_html = '<span class="status-tag asteptare">În așteptare</span>';
                                    }
                                ?>
                                <tr>
                                    <td data-label="ID Rezervare">#<?php echo $rand['id_rezervare']; ?></td>

                                    <td data-label="Client">
                                        <div class="container-tooltip">
                                            <img src="<?php echo htmlspecialchars($poza_client); ?>" alt="User" style="width: 35px; height: 35px; object-fit: cover; vertical-align: middle; border-radius: 50%; margin-right: 8px; border: 2px solid var(--culoare-secundara);">
                                            <?php echo htmlspecialchars($rand['username']); ?>
                                            <div class="info-extra">
                                                <p style="margin-bottom: 5px;"><strong><i class="fa-solid fa-address-card"></i> Fișă Client:</strong></p>
                                                <p><i class="fa-solid fa-phone"></i> Tel: <?php echo htmlspecialchars($rand['telefon'] ?: 'Nespecificat'); ?></p>
                                                <p><i class="fa-solid fa-envelope"></i> Email: <?php echo htmlspecialchars($rand['email']); ?></p>
                                                <?php if(!empty($rand['observatii'])): ?>
                                                    <hr style="margin:5px 0; border:0; border-top:1px solid #ccc;">
                                                    <p><i>Obs: <?php echo htmlspecialchars($rand['observatii']); ?></i></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>

                                    <td data-label="Detalii Masă">
                                        Data: <?php echo $data_formata; ?><br>
                                        <small>Nr. Pers: <?php echo $rand['nr_persoane']; ?> | Mese: <?php echo htmlspecialchars($rand['mese_selectate']); ?></small>
                                    </td>

                                    <td data-label="Status">
                                        <?php echo $status_html; ?>
                                    </td>

                                    <td data-label="Acțiuni">
                                        <div class="actiuni-flex">
                                            <?php if ($rand['status_rezervare'] === 'in asteptare'): ?>
                                                <a href="schimba_status_rezervare.php?id=<?php echo $rand['id_rezervare']; ?>&status=acceptata" class="buton-tabel" style="background-color: #2ecc71; text-decoration: none;">Acceptă</a>
                                                <a href="schimba_status_rezervare.php?id=<?php echo $rand['id_rezervare']; ?>&status=respinsa" class="buton-tabel delete" style="text-decoration: none;" onclick="return confirm('Ești sigur că vrei să respingi și să eliberezi masa?');">Respinge</a>
                                            <?php else: ?>
                                                <span style="color: #7f8c8d; font-size: 0.9em; font-style: italic;">Procesată</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 20px;">Nu există nicio rezervare în sistem.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>

            <section class="setari-cont" style="margin-top: 40px;">
                <h3 style="color: var(--culoare-primara); border-bottom: 2px solid #ecf0f1; padding-bottom: 10px;">
                    <i class="fa-solid fa-circle-info" style="color: var(--culoare-secundara);"></i> Legende Stări
                </h3>
                <ul style="margin-left: 20px; margin-top: 15px; line-height: 1.8;">
                    <li><span class="status-tag confirmat">Acceptată</span> - Clientul a primit confirmarea pe mail. Masa este blocată.</li>
                    <li><span class="status-tag asteptare">În așteptare</span> - Necesită verificare manuală a disponibilității.</li>
                    <li><span class="status-tag" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;">Respinsă</span> - Masa redevine disponibilă în sistem. Motivul va fi trimis clientului.</li>
                </ul>
            </section>
        </main>
    </div>

    <footer>
        <p>&copy; Laborator Programare Web - Vasile Miruna</p>
    </footer>
    <script src="script.js?v=3"></script>
</body>
</html>