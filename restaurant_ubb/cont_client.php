<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['id_utilizator'])) {
    header("Location: index.php?eroare=Autentifică-te!");
    exit();
}

$id_utilizator = $_SESSION['id_utilizator'];

$sql_user = "SELECT * FROM clienti WHERE id_client = $id_utilizator";
$rezultat_user = mysqli_query($conexiune, $sql_user);
$date = mysqli_fetch_assoc($rezultat_user);

$poza_profil = !empty($date['poza_profil']) ? $date['poza_profil'] : 'https://cdn-icons-png.flaticon.com/512/3135/3135715.png';

$puncte_fidelitate = $date['puncte_fidelitate'] ?? 0;
$puncte_ramase = 100 - $puncte_fidelitate;
if ($puncte_ramase < 0) $puncte_ramase = 0;

$sql_rez = "SELECT COUNT(*) AS total FROM rezervari WHERE id_client = $id_utilizator";
$total_rezervari = mysqli_fetch_assoc(mysqli_query($conexiune, $sql_rez))['total'];

$sql_rec = "SELECT AVG(nota) AS medie, COUNT(*) AS nr FROM recenzii WHERE id_client = $id_utilizator";
$date_rec = mysqli_fetch_assoc(mysqli_query($conexiune, $sql_rec));
$media_recenzii = number_format($date_rec['medie'] ?? 0.0, 1);
$nr_recenzii = $date_rec['nr'];

$nivel_cont = ($total_rezervari >= 10) ? 'Gold' : 'Silver';
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contul Meu - Restaurant UBB</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        /* Stil minim pentru Modal */
        .modal-parola { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); }
        .modal-content { background: #fff; margin: 10% auto; padding: 25px; border-radius: 8px; width: 350px; position: relative; }
        .parola-container { position: relative; margin-bottom: 15px; }
        .ochi-modal { position: absolute; right: 10px; top: 35px; cursor: pointer; color: #7f8c8d; }
        
        /* Stil pentru avatar */
        .avatar-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px dashed #bdc3c7;
        }
        .avatar-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--culoare-secundara);
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <header>
        <img src="https://cdn-icons-png.flaticon.com/512/3075/3075977.png" alt="Logo">
        <div>
            <h1>Profil Utilizator</h1>
            <p>Panoul dumneavoastră personal</p>
        </div>
    </header>

    <div class="layout-principal">
        <nav>
            <h2>Navigare Principală</h2>
            <ul>
                <li><a href="home_client.php">Acasă</a></li>
                <li><a href="rezervare_noua_client.php">Faceți o rezervare</a></li>
                <li><a href="rezervarile_mele_client.php">Rezervările mele</a></li>
                <li class="has-submeniu"><a href="#">Meniu Restaurant</a></li>
                <li><a href="cont_client.php"><b>Contul meu</b></a></li>
                <li><a href="logout.php">Ieșire</a></li>
            </ul>
        </nav>

        <main>
            <section class="istoric-feedback">
                <h2>Media notelor acordate</h2>
                <div class="medie-container"><span id="medie-scor"><?php echo $media_recenzii; ?></span></div>
                <p class="detalii-medie"> <span id="numar-recenzii"><?php echo $nr_recenzii; ?></span> recenzii trimise.</p>
                <div class="lista-comentarii" id="lista-comentarii">
                    <?php
                    $res_rec = mysqli_query($conexiune, "SELECT * FROM recenzii WHERE id_client = $id_utilizator ORDER BY id_recenzie DESC");
                    while($r = mysqli_fetch_assoc($res_rec)) {
                        echo "<div class='comentariu-item'><strong>Nota {$r['nota']}/5</strong>: ".htmlspecialchars($r['comentariu'])."</div>";
                    }
                    ?>
                </div>
            </section>

            <section class="dashboard-widgets">
                <article class="widget-card">
                    <div class="widget-info"><h3>Rezervări Efectuate</h3><div class="valoare"><?php echo $total_rezervari; ?></div></div>
                    <i class="fa-solid fa-calendar-check widget-iconita"></i>
                </article>
                <article class="widget-card">
                    <div class="widget-info"><h3>Puncte Fidelitate</h3><div class="valoare"><?php echo $puncte_fidelitate; ?></div></div>
                    <i class="fa-solid fa-star widget-iconita"></i>
                </article>
                <article class="widget-card">
                    <div class="widget-info"><h3>Nivel Cont</h3><div class="valoare" style="color: #f1c40f;"><?php echo $nivel_cont; ?></div></div>
                    <i class="fa-solid fa-medal widget-iconita"></i>
                </article>
            </section>

            <section class="sectiune-indicator">
                <h2>Progres Reducere</h2>
                <div class="indicator-container">
                    <svg class="indicator-svg" viewBox="0 0 100 50">
                        <path class="indicator-fundal" d="M 10,50 A 40,40 0 0,1 90,50" />
                        <path class="indicator-progres" d="M 10,50 A 40,40 0 0,1 90,50" />
                    </svg>
                    <div class="indicator-text"><span id="puncte-curente"><?php echo $puncte_fidelitate; ?></span> / 100</div>
                </div>
                <div class="indicator-mesaj" id="mesaj-reducere">Încă <span id="puncte-ramase"><?php echo $puncte_ramase; ?></span> puncte pentru reducere</div>
                <div style="margin-top: 20px; display: flex; justify-content: center; gap: 15px;">
                    <button id="scade-puncte" class="btn" type="button" style="background-color: #e74c3c;"><i class="fa-solid fa-minus"></i> Scade 10</button>
                    <button id="adauga-puncte" class="btn btn-primar" type="button"><i class="fa-solid fa-plus"></i> Adaugă 10</button>
                </div>
            </section>

            <section class="setari-cont">
                <h2>Setări Personale</h2>
                <form action="procesare_editare_cont.php" method="POST" id="formular_cont" enctype="multipart/form-data">
                    <fieldset>
                        <legend><i class="fa-solid fa-user-pen"></i> Informații Profil</legend>
                        
                        <div class="avatar-container">
                            <img src="<?php echo htmlspecialchars($poza_profil); ?>" alt="Avatar Utilizator" class="avatar-img">
                            <div class="input-iconita" style="width: 100%; max-width: 300px;">
                                <label style="text-align: center; margin-bottom: 10px;">Încarcă sau modifică fotografia:</label>
                                <input type="file" name="poza_profil" accept="image/png, image/jpeg, image/jpg" style="padding: 8px;">
                            </div>
                        </div>

                        <div class="grup-rezervare">
                            <div class="input-iconita">
                                <label>ID Client:</label>
                                <input type="text" value="UBB-<?php echo $date['id_client']; ?>" readonly style="background-color: #ecf0f1;">
                            </div>
                            <div class="input-iconita">
                                <label>Username:</label>
                                <input type="text" name="nume" value="<?php echo htmlspecialchars($date['username']); ?>">
                            </div>
                            <div class="input-iconita">
                                <label>E-mail:</label>
                                <input type="email" value="<?php echo htmlspecialchars($date['email']); ?>" readonly style="background-color: #ecf0f1;">
                            </div>
                            <div class="input-iconita">
                                <label>Telefon:</label>
                                <input type="text" name="telefon" value="<?php echo htmlspecialchars($date['telefon']); ?>">
                            </div>
                            
                            <div class="input-iconita">
                                <label>Județ Curent:</label>
                                <input type="text" name="judet" id="judet_actual" value="<?php echo htmlspecialchars($date['judet']); ?>" readonly style="background:#eee">
                            </div>
                            <div class="input-iconita">
                                <label>Oraș Curent:</label>
                                <input type="text" name="oras" id="oras_actual" value="<?php echo htmlspecialchars($date['oras']); ?>" readonly style="background:#eee">
                            </div>
                        </div>

                        <div class="grup-rezervare" style="margin-top:15px; background:#f4f7f6; padding:10px; border-radius:5px;">
                            <div class="input-iconita">
                                <label>Alege Județ Nou:</label>
                                <select id="judet-select-nou"></select>
                            </div>
                            <div class="input-iconita">
                                <label>Alege Oraș Nou:</label>
                                <select id="oras-select-nou" disabled><option>Alege județul...</option></select>
                            </div>
                        </div>

                        <div style="margin: 20px 0;">
                            <label>Genul:</label><br>
                            <label><input type="radio" name="gen" value="m" <?php if($date['gen']=='m') echo 'checked'; ?>> Masculin</label>
                            <label><input type="radio" name="gen" value="f" <?php if($date['gen']=='f') echo 'checked'; ?>> Feminin</label>
                            <label><input type="radio" name="gen" value="a" <?php if($date['gen']=='a') echo 'checked'; ?>> Altul</label>
                        </div>

                        <div class="input-iconita">
                            <label>Preferințe alimentare:</label>
                            <textarea name="pref" rows="3"><?php echo htmlspecialchars($date['preferinte_culinare']); ?></textarea>
                        </div>

                        <div style="display: flex; gap: 15px; justify-content: flex-end; margin-top: 20px;">
                            <button type="button" id="btn-modal-parola" class="btn" style="background:#f39c12; color:white;"><i class="fa-solid fa-key"></i> Modifică Parola</button>
                            <button type="submit" class="btn btn-primar"><i class="fa-solid fa-save"></i> Salvează Modificările</button>
                        </div>
                    </fieldset>
                </form>
            </section>
        </main>
    </div>

    <div id="modalParola" class="modal-parola">
        <div class="modal-content">
            <h3>Schimbă Parola</h3>
            <form action="procesare_schimba_parola.php" method="POST">
                <div class="parola-container">
                    <label>Parola Curentă:</label>
                    <input type="password" name="parola_veche" id="v_parola" required style="width:100%; padding:8px;">
                    <i class="fa-solid fa-eye ochi-modal" onclick="togglePass('v_parola', this)"></i>
                </div>
                <div class="parola-container">
                    <label>Parola Nouă:</label>
                    <input type="password" name="parola_noua" id="n_parola" required style="width:100%; padding:8px;">
                    <i class="fa-solid fa-eye ochi-modal" onclick="togglePass('n_parola', this)"></i>
                </div>
                <div style="display:flex; gap:10px;">
                    <button type="submit" class="btn btn-primar" style="flex:1">Salvează</button>
                    <button type="button" onclick="$('#modalParola').hide()" class="btn" style="background:#95a5a6; flex:1">Anulează</button>
                </div>
            </form>
        </div>
    </div>

    <footer><p>&copy; Laborator Programare Web - Vasile Miruna</p></footer>

    <script>
        const locatii = { "Cluj": ["Cluj-Napoca", "Turda", "Dej"], "București": ["Sector 1", "Sector 2"], "Iași": ["Iași", "Pașcani"] };
        
        $(document).ready(function() {
            // Populare Judete
            let h = '<option value="">-- Schimbă --</option>';
            for(let j in locatii) h += `<option value="${j}">${j}</option>`;
            $('#judet-select-nou').html(h);

            $('#judet-select-nou').change(function() {
                const j = $(this).val();
                if(j) {
                    $('#judet_actual').val(j);
                    let o = '<option value="">-- Alege Oraș --</option>';
                    locatii[j].forEach(oras => o += `<option value="${oras}">${oras}</option>`);
                    $('#oras-select-nou').html(o).prop('disabled', false);
                }
            });

            $('#oras-select-nou').change(function() {
                if($(this).val()) $('#oras_actual').val($(this).val());
            });

            $('#btn-modal-parola').click(function() { $('#modalParola').show(); });
        });

        function togglePass(id, icon) {
            const x = document.getElementById(id);
            if (x.type === "password") {
                x.type = "text";
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                x.type = "password";
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }
    </script>
    <script src="script.js?v=9"></script>
	<div id="widget-feedback">
        <div id="panou-feedback">
            <h3>Cum a fost experiența ta?</h3>

            <div class="stele-container">
                <i class="fa-solid fa-star stea-feedback" data-valoare="1"></i>
                <i class="fa-solid fa-star stea-feedback" data-valoare="2"></i>
                <i class="fa-solid fa-star stea-feedback" data-valoare="3"></i>
                <i class="fa-solid fa-star stea-feedback" data-valoare="4"></i>
                <i class="fa-solid fa-star stea-feedback" data-valoare="5"></i>
            </div>

            <div id="zona-text-feedback" style="display: none;">
                <textarea id="text-feedback" placeholder="Lasă-ne un mesaj (opțional)..." rows="3"></textarea>
                <button id="trimite-feedback" class="btn btn-primar" style="width: 100%; margin-top: 10px;">Trimite Părerea</button>
            </div>

            <div id="mesaj-succes-feedback" style="display: none; color: #2ecc71; text-align: center; font-weight: bold; margin-top: 10px;">
                <i class="fa-solid fa-circle-check"></i> Mulțumim pentru feedback!
            </div>
        </div>

        <div id="buton-feedback-toggle">
            <i class="fa-solid fa-comment-dots"></i> Lasă-ne un mesaj!
        </div>
    </div>
</body>
</html>