<?php
require_once 'config.php';

if (isset($_POST['data']) && isset($_POST['ora'])) {
    $data = $_POST['data'];
    $ora = $_POST['ora'];
    $id_editare = isset($_POST['id_editare']) ? intval($_POST['id_editare']) : 0;
    
    $data_ora_cautata = $data . ' ' . $ora . ':00';
    
    $sql = "SELECT mese_selectate FROM rezervari 
            WHERE DATE(data_ora) = '$data' 
            AND status_rezervare != 'respinsa'
            AND id_rezervare != $id_editare
            AND ABS(TIMESTAMPDIFF(HOUR, data_ora, '$data_ora_cautata')) < 2";
            
    $rezultat = mysqli_query($conexiune, $sql);
    $mese_ocupate = [];
    
    while ($rand = mysqli_fetch_assoc($rezultat)) {
        $mese = explode(',', $rand['mese_selectate']);
        foreach ($mese as $masa) {
            $mese_ocupate[] = trim($masa);
        }
    }
    echo json_encode(array_unique($mese_ocupate));
}
?>