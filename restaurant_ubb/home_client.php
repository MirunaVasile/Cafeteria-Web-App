<?php
// PORNIM SESIUNEA ȘI PROTEJĂM PAGINA
session_start();

if (!isset($_SESSION['id_utilizator'])) {
    header("Location: index.php?eroare=Trebuie să te autentifici mai întâi!");
    exit();
}

if ($_SESSION['rol'] !== 'client') {
    header("Location: home_admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acasă - Restaurant UBB</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>

    <header>
        <img src="https://cdn-icons-png.flaticon.com/512/3075/3075977.png" alt="Logo Restaurant UBB">
        <div>
            <h1>Restaurant UBB</h1>
            <p>Un fel de cantină pentru studenţi</p>
            <small style="color: white;">Salut, <?php echo htmlspecialchars($_SESSION['username']); ?>!</small>
        </div>
    </header>

    <div class="layout-principal">

        <nav>
            <h2>Navigare Principală</h2>
            <ul>
                <li><a href="home_client.php"><b>Acasă</b></a></li>
                <li><a href="rezervare_noua_client.php">Faceți o rezervare</a></li>
                <li><a href="rezervarile_mele_client.php">Rezervările mele</a></li>

                <li class="has-submeniu">
                    <a href="https://silverfare.com/venue/HRABBSV8G" target="_blank">Meniu Restaurant</a>

                    <ul class="submeniu">
                        <li><a href="https://silverfare.com/venue/HRABBSV8G/cfb23b1e-6aaf-11ef-a1b3-ef9415e2965f" target="_blank">Preparate Calde</a></li>
                        <li><a href="https://silverfare.com/venue/HRABBSV8G/7f36f90f-8d4a-11ef-abf9-05a317ed8448" target="_blank">Deserturi</a></li>
                        <li><a href="https://silverfare.com/venue/HRABBSV8G/204c2166-6ac0-11ef-a1b3-3db7626b0965" target="_blank">Băuturi</a></li>
                    </ul>
                </li>

                <li><a href="cont_client.php">Contul meu</a></li>
                <li><a href="https://www.ubbcluj.ro" target="_blank">Site oficial UBB</a></li>
                
                <li><a href="logout.php" style="color: #e74c3c; font-weight: bold;"><i class="fa-solid fa-power-off"></i> Ieșire</a></li>
            </ul>
        </nav>

        <main>
            <article>
                <h2>Despre noi</h2>
                <p>
                    Bine ați venit la <strong>Restaurantul UBB</strong>! Aici vă puteţi bucura de cele mai delicioase preparate la un preț mai accesibil ca oriunde!
                </p>

                <div class="galerie-wrapper">

                    <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&q=80" class="img-default" alt="Restaurant Interior">

                    <div class="zona-miniaturi">

                        <div class="mini-container">
                            <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=200&q=80" class="img-mica" alt="Interior">
                            <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&q=80" class="img-ascunsa-mare" alt="Interior Mare">
                        </div>

                        <div class="mini-container">
                            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="img-mica" alt="Preparat">
                            <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=870&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" class="img-ascunsa-mare" alt="Preparat Mare">
                        </div>

                        <div class="mini-container">
                            <img src="https://images.unsplash.com/photo-1552566626-52f8b828add9?w=200&q=80" class="img-mica" alt="Bucatarie">
                            <img src="https://images.unsplash.com/photo-1552566626-52f8b828add9?w=800&q=80" class="img-ascunsa-mare" alt="Bucatarie Mare">
                        </div>

                        <div class="mini-container">
                            <img src="https://images.unsplash.com/photo-1544148103-0773bf10d330?w=200&q=80" class="img-mica" alt="Terasa">
                            <img src="https://images.unsplash.com/photo-1544148103-0773bf10d330?w=800&q=80" class="img-ascunsa-mare" alt="Terasa Mare">
                        </div>

                    </div>

                    <div class="controale-slider">

                        <button id="btn-play-pause" class="btn btn-primar">
                            <i class="fa-solid fa-play"></i> Play
                        </button>

                        <label class="control-label">
                            <input type="checkbox" id="check-repetare" checked> Repetare
                        </label>

                        <div class="interval-container">
                            <label for="select-interval">Timp:</label>
                            <select id="select-interval">
                                <option value="1000">1 secundă</option>
                                <option value="2000" selected>2 secunde</option>
                                <option value="3000">3 secunde</option>
                                <option value="5000">5 secunde</option>
                            </select>
                        </div>

                    </div>
                </div>

            </article>
            <div class="modul-slider">
                <h2>Preparatele noastre</h2>

                <div class="controale">
                    <label>
                        Imagini afișate:
                        <input type="number" id="setare-imagini" value="2" min="1" max="4">
                    </label>
                    <label>
                        Secunde tranziție:
                        <input type="number" id="setare-secunde" value="3" min="1">
                    </label>
                </div>

                <div class="slider-wrapper">
                    <div class="sageata sageata-sus" id="btn-sus">&#9650;</div>

                    <div class="viewport">
                        <ul class="lista-imagini lista-3d">
                            <li class="card-3d">
                                <img src="icons/mancare1.jpg" alt="Aripioare">
                            </li>
                            <li class="card-3d">
                                <video autoplay loop muted playsinline>
                                    <source src="icons/video.mp4" type="video/mp4">
                                </video>
                            </li>
                            <li class="card-3d">
                                <img src="icons/mancare3.jpg" alt="HotDogs">
                            </li>
                            <li class="card-3d">
                                <img src="icons/mancare4.jpg" alt="Shawarma">
                            </li>
                        </ul>
                    </div>

                    <div class="sageata sageata-jos" id="btn-jos">&#9660;</div>
                </div>
            </div>


            <section class="sectiune-noutati">
                <h2>Noutăți și Evenimente</h2>

                <article class="card-stire">
                    <h3>Meniu de Post disponibil</h3>
                    <p>Începând de luni, am introdus o secțiune dedicată preparatelor de post în meniul nostru zilnic.</p>
                    <small>Postat la: 15 Martie 2026</small>
                </article>

                <article class="card-stire">
                    <h3>Seară Culturală în Hasdeu</h3>
                    <p>Vineri seară organizăm o seară de muzică. Intrarea este liberă pentru toți studenții.</p>
                    <small>Postat la: 10 Martie 2026</small>
                </article>
            </section>
        </main>

        <aside>
            <h3>Informații Utile</h3>
            <section class="widget">
                <h4>Programul nostru</h4>
                <p>Luni - Vineri: 08:00 - 20:00</p>
                <p>Sâmbătă: 10:00 - 18:00</p>
            </section>

            <section class="widget">
                <h4>Promoția Zilei</h4>
                <p>Meniul zilei la doar 8 RON pentru studenții UBB!</p>
            </section>

            <section class="widget" style="margin-top: 20px;">
                <h4><i class="fa-solid fa-gavel" style="color: var(--culoare-secundara);"></i> Documente & Regulamente</h4>
                <p style="font-size: 0.85rem; color: #7f8c8d; margin-bottom: 10px;">Descarcă ultimele regulamente oficiale ale restaurantului:</p>
                <ul style="list-style: none; padding: 0;">
                    <?php
                    require_once 'config.php'; 
                    $sql_client_docs = "SELECT * FROM regulamente ORDER BY data_incarcare DESC";
                    $rez_client_docs = mysqli_query($conexiune, $sql_client_docs);
                    
                    if ($rez_client_docs && mysqli_num_rows($rez_client_docs) > 0) {
                        while ($doc_c = mysqli_fetch_assoc($rez_client_docs)) {
                            echo "<li style='margin-bottom: 8px; border-bottom: 1px dashed #ddd; padding-bottom: 5px;'>";
                            echo "<a href='" . htmlspecialchars($doc_c['cale_fisier']) . "' download style='color: var(--culoare-primara); text-decoration: none; font-weight: bold; font-size: 0.9rem;'>";
                            echo "<i class='fa-solid fa-file-arrow-down' style='color: var(--culoare-secundara); margin-right: 5px;'></i> " . htmlspecialchars($doc_c['titlu']);
                            echo "</a>";
                            echo "</li>";
                        }
                    } else {
                        echo "<li style='font-style: italic; color: #95a5a6; font-size: 0.85rem;'>Momentan nu sunt documente publice.</li>";
                    }
                    ?>
                </ul>
            </section>
        </aside>

    </div>
    <footer>
        <p>&copy; Laborator Programare Web - Vasile Miruna</p>
    </footer>
    <script src="script.js?v=2"></script>
   
</body>
</html>