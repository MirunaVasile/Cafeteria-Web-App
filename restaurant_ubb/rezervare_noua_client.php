<?php
session_start();
if (!isset($_SESSION['id_utilizator'])) {
    header("Location: index.php?eroare=Trebuie să te autentifici!");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervare Nouă - Restaurant UBB</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>

    <header>
        <img src="https://cdn-icons-png.flaticon.com/512/3075/3075977.png" alt="Logo">
        <div>
            <h1>Rezervări Online</h1>
            <p>Procesul de rezervare pentru Restaurantul UBB</p>
        </div>
    </header>

    <div class="layout-principal">

        <nav>
            <h2>Navigare Principală</h2>
            <ul>
                <li><a href="home_client.php">Acasă</a></li>
                <li><a href="rezervare_noua_client.php"><b>Faceți o rezervare</b></a></li>
                <li><a href="rezervarile_mele_client.php">Rezervările mele</a></li>
                <li><a href="cont_client.php">Contul meu</a></li>
                <li><a href="logout.php">Ieșire</a></li>
            </ul>
        </nav>

        <main>
            <form action="procesare_rezervare.php" method="POST" id="formular-rezervare">
                
                <section>
                    <h2>Pasul 1: Alegeți data, ora și numărul de persoane</h2>
                    <fieldset>
                        <legend>1. Detalii Programare</legend>
                        <div class="grup-rezervare">
                            <div class="input-iconita">
                                <label>Data Rezervării:</label>
                                <input type="date" name="data_rezervare" id="data_id" required>
                            </div>
                            <div class="input-iconita">
                                <label>Ora Sosirii:</label>
                                <input type="time" name="ora_rezervare" id="ora_id" value="19:00" min="08:00" max="20:00" required>
                            </div>
                            <div class="input-iconita">
                                <label>Număr Persoane:</label>
                                <input type="number" name="numar_persoane" id="pers_id" min="1" max="20" value="4" required>
                            </div>
                        </div>
                        <div style="margin-top: 25px;">
                            <label style="font-weight: bold; color: var(--culoare-primara);">Observații Speciale:</label>
                            <textarea name="observatii" rows="2" style="width: 100%; padding: 10px; border: 1px solid #ccc;"></textarea>
                        </div>
                    </fieldset>
                </section>

                <section>
                    <h2>Pasul 2: Alegeți Masa</h2>
                    <fieldset>
                        <legend>2. Harta restaurantului</legend>
                        <p id="mesaj-harta" style="margin-bottom: 20px; color: #e67e22; font-weight: bold;">
                            <i class="fa-solid fa-spinner fa-spin"></i> Alegeți o dată și o oră pentru a vedea disponibilitatea...
                        </p>

                        <div class="harta-restaurant">
                            
                            <label class="masa-wrapper" id="wrapper-M1">
                                <input type="checkbox" name="mese_selectate[]" value="M1" class="checkbox-masa">
                                <img src="icons/masa-22.png" alt="Masa 2">
                                <span class="detalii-masa">Masa 1<br><small>Geam (2 pers)</small></span>
                            </label>

                            <label class="masa-wrapper" id="wrapper-M2">
                                <input type="checkbox" name="mese_selectate[]" value="M2" class="checkbox-masa">
                                <img src="icons/masa-2.png" alt="Masa 2">
                                <span class="detalii-masa">Masa 2<br><small>Interior (2 pers)</small></span>
                            </label>

                            <label class="masa-wrapper" id="wrapper-M3">
                                <input type="checkbox" name="mese_selectate[]" value="M3" class="checkbox-masa">
                                <img src="icons/masa-4.png" alt="Masa 4">
                                <span class="detalii-masa">Masa 3<br><small>Terasă (4 pers)</small></span>
                            </label>

                            <label class="masa-wrapper" id="wrapper-M4">
                                <input type="checkbox" name="mese_selectate[]" value="M4" class="checkbox-masa">
                                <img src="icons/masa-2.png" alt="Masa 4">
                                <span class="detalii-masa">Masa 4<br><small>Geam (4 pers)</small></span>
                            </label>

                            <label class="masa-wrapper" id="wrapper-M5">
                                <input type="checkbox" name="mese_selectate[]" value="M5" class="checkbox-masa">
                                <img src="icons/masa-6.png" alt="Masa 6">
                                <span class="detalii-masa">Masa 5<br><small>VIP (6 pers)</small></span>
                            </label>

                            <label class="masa-wrapper" id="wrapper-M6">
                                <input type="checkbox" name="mese_selectate[]" value="M6" class="checkbox-masa">
                                <img src="icons/masa-6.png" alt="Masa 6">
                                <span class="detalii-masa">Masa 6<br><small>Terasă (6 pers)</small></span>
                            </label>

                        </div>
                    </fieldset>
                </section>

                <p style="text-align: right; margin-top: 20px;">
                    <button type="submit" class="btn btn-primar" id="btn-submit" disabled><i class="fa-solid fa-check"></i> Finalizare Rezervare</button>
                </p>
            </form>
        </main>
    </div>

    <script>
        $(document).ready(function() {
            let azi = new Date().toISOString().split('T')[0];
            $('#data_id').attr('min', azi);
            $('#data_id').val(azi); 

            function verificaMese() {
                let data = $('#data_id').val();
                let ora = $('#ora_id').val();

                if(data && ora) {
                    $('#mesaj-harta').html('<i class="fa-solid fa-spinner fa-spin"></i> Se verifică disponibilitatea...');
                    $('#btn-submit').prop('disabled', true);

                    $.ajax({
                        url: 'verifica_mese.php',
                        method: 'POST',
                        data: { data: data, ora: ora },
                        success: function(response) {
                            let meseOcupate = JSON.parse(response);

                            $('.masa-wrapper').removeClass('masa-ocupata').addClass('masa-libera');
                            $('.checkbox-masa').prop('disabled', false).prop('checked', false);

                            meseOcupate.forEach(function(masaID) {
                                let wrapper = $('#wrapper-' + masaID);
                                wrapper.removeClass('masa-libera').addClass('masa-ocupata');
                                wrapper.find('.checkbox-masa').prop('disabled', true); 
                            });

                            $('#mesaj-harta').html('<i class="fa-solid fa-hand-pointer"></i> Selectați mesele dorite (Verde = Liber, Roșu = Ocupat).');
                            $('#btn-submit').prop('disabled', false);
                        }
                    });
                }
            }

            $('#data_id, #ora_id').on('change', verificaMese);

            verificaMese();
        });
    </script>
</body>
</html>