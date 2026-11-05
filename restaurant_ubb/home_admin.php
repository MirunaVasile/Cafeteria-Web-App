<?php
session_start();
require_once 'config.php'; 

if (!isset($_SESSION['id_utilizator']) || $_SESSION['rol'] !== 'admin') {
    header("Location: index.php?eroare=Acces interzis! Doar administratorii au acces aici.");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panou Administrare - Restaurant UBB</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <style>
        /* Stil pentru fereastra mică (Modal) */
        .modal-admin { display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.6); }
        .modal-continut { background: #fff; margin: 10% auto; padding: 30px; border-radius: 8px; width: 400px; position: relative; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        .inchide-modal { position: absolute; right: 15px; top: 10px; font-size: 24px; cursor: pointer; color: #7f8c8d; }
    </style>
</head>
<body>

    <header>
        <img src="https://cdn-icons-png.flaticon.com/512/3075/3075977.png" alt="Logo Admin">
        <div>
            <h1>Administrare Sistem Rezervări</h1>
            <p>Acces doar pentru personalul restaurantului</p>
        </div>
    </header>

    <div class="layout-principal">

        <nav>
            <h2>Navigare Admin</h2>
            <ul>
                <li><a href="home_admin.php"><b>Acasă Admin</b></a></li>
                <li><a href="vizualizare_rezervari_admin.php">Vizualizare Rezervări</a></li>
                <li><a href="logout.php" style="color: #e74c3c; font-weight: bold;"><i class="fa-solid fa-power-off"></i> Ieșire din sistem</a></li>
            </ul>
        </nav>

        <main>
            <section>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2>Gestionarea rezervărilor</h2>
                    
                    <button id="btn-deschide-modal" class="btn btn-primar" style="background-color: #27ae60;">
                        <i class="fa-solid fa-user-plus"></i> Adaugă Admin Nou
                    </button>
                </div>

                <?php if (isset($_GET['succes'])): ?>
                    <div style="background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                        <i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($_GET['succes']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_GET['eroare'])): ?>
                    <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                        <i class="fa-solid fa-triangle-exclamation"></i> <?php echo htmlspecialchars($_GET['eroare']); ?>
                    </div>
                <?php endif; ?>

                <p style="margin-bottom: 25px; color: #7f8c8d;">Mai jos aveți o listă a sarcinilor administrative ale personalului:</p>

                <div style="display: grid; gap: 20px;">

                    <article class="setari-cont">
                        <h3 style="color: var(--culoare-primara); border-bottom: 2px solid #ecf0f1; padding-bottom: 10px;">
                            <i class="fa-solid fa-clipboard-list" style="color: var(--culoare-secundara);"></i> Sarcini administrative
                        </h3>
                        <ul style="margin-left: 20px; margin-top: 15px; line-height: 1.8;">
                            <li>Verificarea stocului de alimente.</li>
                            <li>Acceptarea sau respingerea rezervărilor.</li>
                            <li>
                                Întreținerea meselor:
                                <ol style="margin-left: 20px; margin-top: 5px;">
                                    <li>Mesele din interior;</li>
                                    <li>Mesele de pe terasă.</li>
                                </ol>
                            </li>
                        </ul>
                    </article>

                    <article class="setari-cont">
                        <h3 style="color: var(--culoare-primara); border-bottom: 2px solid #ecf0f1; padding-bottom: 10px;">
                            Gestiune Regulamente și Documente
                        </h3>
                        
                        <form action="procesare_regulament.php" method="POST" enctype="multipart/form-data" style="margin-top: 15px; background: #f8f9fa; padding: 15px; border-radius: 6px;">
                            <div class="grup-rezervare">
                                <div class="input-iconita">
                                    <label>Titlu Document:</label>
                                    <input type="text" name="titlu_document" required placeholder="Ex: Regulament de Ordine Interioară" style="width: 100%;">
                                </div>
                                <div class="input-iconita">
                                    <label>Alege fișierul (PDF, DOCX, PNG, JPG):</label>
                                    <input type="file" name="fisier_regulament" required style="padding-top: 5px; width: 100%;">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primar" style="background-color: #2980b9; margin-top: 10px;">
                                <i class="fa-solid fa-cloud-arrow-up"></i> Încarcă pe Server
                            </button>
                        </form>

                        <h4 style="margin-top: 25px; color: var(--culoare-primara);">Fișiere active pe server:</h4>
                        <table class="tabel-stilizat" style="box-shadow: none; margin-top: 10px; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Titlu Document</th>
                                    <th>Dată Încărcare</th>
                                    <th>Acțiuni Administrative</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql_docs = "SELECT * FROM regulamente ORDER BY data_incarcare DESC";
                                $rez_docs = mysqli_query($conexiune, $sql_docs);
                                
                                if (mysqli_num_rows($rez_docs) > 0) {
                                    while ($doc = mysqli_fetch_assoc($rez_docs)) {
                                        echo "<tr>";
                                        echo "<td data-label='Titlu Document'>" . htmlspecialchars($doc['titlu']) . "</td>";
                                        echo "<td data-label='Dată Încărcare'>" . date('d.m.Y - H:i', strtotime($doc['data_incarcare'])) . "</td>";
                                        echo "<td data-label='Acțiuni Administrative'>
                                                <div class='actiuni-flex'>
                                                    <a href='" . htmlspecialchars($doc['cale_fisier']) . "' download class='buton-tabel edit' style='text-decoration: none;'><i class='fa-solid fa-download'></i> Descarcă</a>
                                                    <a href='sterge_regulament.php?id=" . $doc['id_regulament'] . "' class='buton-tabel delete' style='text-decoration: none;' onclick='return confirm(\"Sigur doriți să ștergeți acest document definitiv de pe server și din baza de date?\")'><i class='fa-solid fa-trash'></i> Șterge</a>
                                                </div>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3' style='text-align: center; padding: 15px;'>Nu s-a încărcat niciun regulament până acum.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </article>

                    <article class="setari-cont">
                        <h3 style="color: var(--culoare-primara); border-bottom: 2px solid #ecf0f1; padding-bottom: 10px;">
                            <i class="fa-solid fa-bullhorn" style="color: var(--culoare-secundara);"></i> Anunțuri Personal
                        </h3>

                        <div class="slider-text-container">
                            <ul id="lista-anunturi">
                                <li class="activ">
                                    <div>1. Ședință de personal vineri la ora 09:00 privind noile norme de igienă și protecția muncii. Prezența este obligatorie.</div>
                                    <a href="#">Read more...</a>
                                </li>
                                <li>
                                    <div>2. S-a actualizat meniul pentru sezonul de toamnă. Vă rugăm să verificați noile preparate în secțiunea de gestiune a stocurilor.</div>
                                    <a href="#">Read more...</a>
                                </li>
                                <li>
                                    <div>3. Echipamentele de la bucătărie vor fi în revizie tehnică marți între orele 14:00 - 16:00. Vă rugăm să vă planificați activitatea corespunzător.</div>
                                    <a href="#">Read more...</a>
                                </li>
                                <li>
                                    <div>4. Programările de concediu pentru luna viitoare trebuie depuse la administrație până la finalul acestei săptămâni.</div>
                                    <a href="#">Read more...</a>
                                </li>
                            </ul>

                            <div class="butoane-slider-text">
                                <button id="btn-prev-text" class="btn btn-secundar" style="padding: 8px 15px;"><i class="fa-solid fa-chevron-left"></i> Previous</button>
                                <button id="btn-next-text" class="btn btn-secundar" style="padding: 8px 15px;">Next <i class="fa-solid fa-chevron-right"></i></button>
                            </div>
                        </div>
                    </article>

                </div>
            </section>
        </main>
    </div>

    <div id="modalAdmin" class="modal-admin">
        <div class="modal-continut">
            <span class="inchide-modal" id="btn-inchide">&times;</span>
            <h3 style="color: var(--culoare-primara); margin-bottom: 20px;">
                <i class="fa-solid fa-user-shield"></i> Creează Admin Nou
            </h3>
            
            <form action="procesare_admin_nou.php" method="POST">
                <div class="input-iconita" style="margin-bottom: 15px;">
                    <label>Nume Utilizator:</label>
                    <input type="text" name="nume_admin" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <div class="input-iconita" style="margin-bottom: 15px;">
                    <label>E-mail:</label>
                    <input type="email" name="email_admin" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <div class="input-iconita" style="margin-bottom: 20px;">
                    <label>Parolă:</label>
                    <input type="password" name="parola_admin" required style="width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn btn-primar" style="flex: 1;">Finalizează</button>
                    <button type="button" class="btn btn-secundar" id="btn-anuleaza" style="flex: 1;">Anulează</button>
                </div>
            </form>
        </div>
    </div>

    <footer>
        <p>&copy; Laborator Programare Web - Vasile Miruna</p>
    </footer>

    <script>
        $(document).ready(function() {
            $('#btn-deschide-modal').click(function() {
                $('#modalAdmin').fadeIn(300);
            });

            $('#btn-inchide, #btn-anuleaza').click(function() {
                $('#modalAdmin').fadeOut(300);
            });

            $(window).click(function(event) {
                if ($(event.target).is('#modalAdmin')) {
                    $('#modalAdmin').fadeOut(300);
                }
            });
        });
    </script>

    <script src="script.js?v=2"></script>
</body>
</html>