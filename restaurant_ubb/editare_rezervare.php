<?php
session_start();
require_once 'config.php';

if (!isset($_GET['id']) || !isset($_SESSION['id_utilizator'])) {
    header("Location: rezervarile_mele_client.php");
    exit();
}

$id_rez = intval($_GET['id']);
$id_client = $_SESSION['id_utilizator'];

// Extragem datele rezervării curente
$sql = "SELECT * FROM rezervari WHERE id_rezervare = $id_rez AND id_client = $id_client";
$rezultat = mysqli_query($conexiune, $sql);
$rezervare = mysqli_fetch_assoc($rezultat);

if (!$rezervare) {
    header("Location: rezervarile_mele_client.php?eroare=Rezervarea nu a fost găsită.");
    exit();
}

// Separăm data de oră pentru input-uri
$timestamp = strtotime($rezervare['data_ora']);
$data_existenta = date('Y-m-d', $timestamp);
$ora_existenta = date('H:i', $timestamp);
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Modifică Rezervarea - Restaurant UBB</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header><h1>Modifică Rezervarea #<?php echo $id_rez; ?></h1></header>
    <div class="layout-principal">
        <main>
            <form action="procesare_editare_rezervare.php" method="POST">
                <input type="hidden" name="id_rezervare" id="id_rez_edit" value="<?php echo $id_rez; ?>">
                
                <section>
                    <h2>Pasul 1: Modifică data și ora</h2>
                    <div class="grup-rezervare">
                        <div class="input-iconita">
                            <label>Data:</label>
                            <input type="date" name="data_rezervare" id="data_id" value="<?php echo $data_existenta; ?>" required>
                        </div>
                        <div class="input-iconita">
                            <label>Ora:</label>
                            <input type="time" name="ora_rezervare" id="ora_id" value="<?php echo $ora_existenta; ?>" required>
                        </div>
                        <div class="input-iconita">
                            <label>Persoane:</label>
                            <input type="number" name="numar_persoane" value="<?php echo $rezervare['nr_persoane']; ?>" min="1" max="20" required>
                        </div>
                    </div>
                </section>

                <section>
                    <h2>Pasul 2: Alege Mesele (Cele verzi sunt libere)</h2>
                    <p id="mesaj-harta" style="font-weight:bold; color:#e67e22;">Se verifică disponibilitatea...</p>
                    <div class="harta-restaurant">
                        <?php for($i=1; $i<=6; $i++): $mID = "M".$i; ?>
                        <label class="masa-wrapper" id="wrapper-<?php echo $mID; ?>">
                            <input type="checkbox" name="mese_selectate[]" value="<?php echo $mID; ?>" class="checkbox-masa">
                            <img src="icons/masa-<?php echo ($i==1 || $i==3 || $i==6) ? '6' : '2'; ?>.png" alt="Masa">
                            <span class="detalii-masa">Masa <?php echo $i; ?></span>
                        </label>
                        <?php endfor; ?>
                    </div>
                </section>
                <button type="submit" class="btn btn-primar" style="margin-top:20px;">Salvează Modificările</button>
            </form>
        </main>
    </div>

    <script>
        $(document).ready(function() {
            // Mesele care erau deja selectate în baza de date
            const meseBazaDeDate = "<?php echo $rezervare['mese_selectate']; ?>".split(',').map(s => s.trim());

            function verificaMese() {
                let data = $('#data_id').val();
                let ora = $('#ora_id').val();
                let idEdit = $('#id_rez_edit').val();

                $.ajax({
                    url: 'verifica_mese.php',
                    method: 'POST',
                    data: { data: data, ora: ora, id_editare: idEdit },
                    success: function(response) {
                        let meseOcupate = JSON.parse(response);
                        $('.masa-wrapper').removeClass('masa-ocupata').addClass('masa-libera');
                        $('.checkbox-masa').prop('disabled', false);

                        meseOcupate.forEach(function(masaID) {
                            let w = $('#wrapper-' + masaID);
                            w.removeClass('masa-libera').addClass('masa-ocupata');
                            w.find('.checkbox-masa').prop('disabled', true);
                        });

                        // Bifăm automat mesele pe care utilizatorul le avea deja selectate
                        meseBazaDeDate.forEach(function(masaID) {
                            let input = $('#wrapper-' + masaID + ' .checkbox-masa');
                            if(!input.prop('disabled')) input.prop('checked', true);
                        });
                        
                        $('#mesaj-harta').text('Harta s-a actualizat pentru data și ora aleasă.');
                    }
                });
            }
            $('#data_id, #ora_id').on('change', verificaMese);
            verificaMese();
        });
    </script>
</body>
</html>