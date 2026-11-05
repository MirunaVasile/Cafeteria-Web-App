<?php
session_start();
require_once 'config.php';

// Protejăm pagina
if (!isset($_SESSION['id_utilizator'])) {
    header("Location: index.php?eroare=Trebuie să te autentifici!");
    exit();
}

$id_utilizator = $_SESSION['id_utilizator'];

// 1. Array pentru lunile anului în română (pentru a afișa "12 Martie 2026")
$luni_ro = [
    1 => 'Ianuarie', 2 => 'Februarie', 3 => 'Martie', 4 => 'Aprilie', 
    5 => 'Mai', 6 => 'Iunie', 7 => 'Iulie', 8 => 'August', 
    9 => 'Septembrie', 10 => 'Octombrie', 11 => 'Noiembrie', 12 => 'Decembrie'
];

// 2. Dicționar cu informații pentru fiecare masă (ca să afișăm corect pozele și locația în tooltip)
$info_mese = [
    'M1' => ['img' => 'icons/masa-22.png', 'locatie' => 'Geam'],
    'M2' => ['img' => 'icons/masa-2.png', 'locatie' => 'Interior'],
    'M3' => ['img' => 'icons/masa-4.png', 'locatie' => 'Terasă'],
    'M4' => ['img' => 'icons/masa-2.png', 'locatie' => 'Geam'],
    'M5' => ['img' => 'icons/masa-6.png', 'locatie' => 'VIP'],
    'M6' => ['img' => 'icons/masa-6.png', 'locatie' => 'Terasă']
];

// 3. Extragem rezervările clientului ordonate descrescător (cele mai noi primele)
$sql_rezervari = "SELECT * FROM rezervari WHERE id_client = $id_utilizator ORDER BY data_ora DESC";
$rezultat_rezervari = mysqli_query($conexiune, $sql_rezervari);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervările Mele - Restaurant UBB</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <header>
        <img src="https://cdn-icons-png.flaticon.com/512/3075/3075977.png" alt="Logo" class="logo-animat">
        <div>
            <h1>Rezervările Mele</h1>
            <p>Gestionarea programărilor la Restaurantul UBB</p>
        </div>
    </header>

    <div class="layout-principal">
        <nav>
            <h2>Navigare</h2>
            <ul>
                <li><a href="home_client.php">Acasă</a></li>
                <li><a href="rezervare_noua_client.php">Faceți o rezervare</a></li>
                <li><a href="rezervarile_mele_client.php"><strong>Rezervările mele</strong></a></li>
                <li class="has-submeniu">
                    <a href="https://silverfare.com/venue/HRABBSV8G" target="_blank">Meniu Restaurant</a>
                    <ul class="submeniu">
                        <li><a href="https://silverfare.com/venue/HRABBSV8G/cfb23b1e-6aaf-11ef-a1b3-ef9415e2965f" target="_blank">Preparate Calde</a></li>
                        <li><a href="https://silverfare.com/venue/HRABBSV8G/7f36f90f-8d4a-11ef-abf9-05a317ed8448" target="_blank">Deserturi</a></li>
                        <li><a href="https://silverfare.com/venue/HRABBSV8G/204c2166-6ac0-11ef-a1b3-3db7626b0965" target="_blank">Băuturi</a></li>
                    </ul>
                </li>
                <li><a href="cont_client.php">Contul meu</a></li>
                <li><a href="logout.php" style="color: #e74c3c; font-weight: bold;"><i class="fa-solid fa-power-off"></i> Ieșire</a></li>
            </ul>
        </nav>

        <main>
            <section>
                <h2>Listă Rezervări Active</h2>
                <p>Gestionați programările dumneavoastră.</p>

                <?php if (isset($_GET['succes'])): ?>
                    <div style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                        <i class="fa-solid fa-check-circle"></i> <?php echo htmlspecialchars($_GET['succes']); ?>
                    </div>
                <?php endif; ?>

                <table class="tabel-stilizat">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Data și Ora</th>
                            <th>Mese</th> <th>Status</th>
                            <th>Pers.</th>
                            <th>Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($rezultat_rezervari) > 0): ?>
                            <?php while ($rez = mysqli_fetch_assoc($rezultat_rezervari)): ?>
                                <?php
                                $timestamp = strtotime($rez['data_ora']);
                                $zi = date('d', $timestamp);
                                $luna = $luni_ro[(int)date('m', $timestamp)];
                                $an = date('Y', $timestamp);
                                $ora = date('H:i', $timestamp);
                                $data_afisare = "$zi $luna $an - $ora";

                                $status_text = ucfirst($rez['status_rezervare']);
                                $status_class = '';
                                if ($rez['status_rezervare'] == 'acceptata') {
                                    $status_class = 'confirmat';
                                    $status_text = 'Confirmată';
                                } elseif ($rez['status_rezervare'] == 'respinsa') {
                                    $status_class = 'respins';
                                    $status_text = 'Respinsă';
                                } else {
                                    $status_class = 'asteptare';
                                    $status_text = 'În așteptare';
                                }

                                $mese_array = array_filter(array_map('trim', explode(',', $rez['mese_selectate'])));
                                ?>
                                <tr>
                                    <td data-label="ID">#<?php echo $rez['id_rezervare']; ?></td>
                                    
                                    <td data-label="Data și Ora"><?php echo $data_afisare; ?></td>
                                    
                                    <td data-label="Mese">
                                        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                            <?php foreach ($mese_array as $masa_cod): ?>
                                                <?php 
                                                    $imagine = isset($info_mese[$masa_cod]) ? $info_mese[$masa_cod]['img'] : 'icons/masa-2.png';
                                                    $locatie = isset($info_mese[$masa_cod]) ? $info_mese[$masa_cod]['locatie'] : 'Nespecificat';
                                                ?>
                                                <div class="container-tooltip" style="cursor: pointer; background: #ecf0f1; padding: 2px 8px; border-radius: 4px; border: 1px solid #bdc3c7;">
                                                    <?php echo $masa_cod; ?>
                                                    <div class="info-extra">
                                                        <img src="<?php echo $imagine; ?>" alt="<?php echo $masa_cod; ?>" style="width:60px; display:block; margin:0 auto 10px;">
                                                        <p style="text-align:center;"><strong>Locație:</strong> <?php echo $locatie; ?></p>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </td>
                                    
                                    <td data-label="Status">
                                        <span class="status-tag <?php echo $status_class; ?>"><?php echo $status_text; ?></span>
                                    </td>
                                    
                                    <td data-label="Persoane"><?php echo $rez['nr_persoane']; ?></td>
                                    
                                    <td data-label="Acțiuni">
                                        <div class="actiuni-flex">
                                            <div class="container-tooltip">
                                                <span class="buton-tabel info">Detalii</span>
                                                <div class="info-extra">
                                                    <p><strong>Observații:</strong></p>
                                                    <p>"<?php echo htmlspecialchars($rez['observatii']) ?: 'Fără observații.'; ?>"</p>
                                                </div>
                                            </div>
                                            <a href="editare_rezervare.php?id=<?php echo $rez['id_rezervare']; ?>" class="buton-tabel edit">Modifică</a>
<a href="anuleaza_rezervare.php?id=<?php echo $rez['id_rezervare']; ?>" class="buton-tabel delete" onclick="return confirm('Sigur vrei să anulezi această rezervare?')">Anulează</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 20px;">Nu aveți nicio rezervare activă momentan. <a href="rezervare_noua_client.php" style="color: var(--culoare-primara); text-decoration: underline;">Faceți o rezervare nouă!</a></td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </section>
        </main>
    </div>

    <footer>
        <p>&copy; Laborator Programare Web - Vasile Miruna</p>
    </footer>

</body>
</html>