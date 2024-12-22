<?php
require_once('../../config/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $universitiId = $_POST['id'];

    if ($_POST['action'] === 'delete') {
        // Zapytanie SQL do usunięcia nauczyciela
        $deleteQuery = "DELETE FROM tUczelnie WHERE ID = $universitiId";

        // Wykonanie zapytania
        if (mysqli_query($conn, $deleteQuery)) {
            // Przekierowanie po sukcesie
            header("Location: ../../public/admin/universities.php?delete_success=1");
            exit;
        } else {
            echo "Błąd podczas usuwania: " . mysqli_error($conn);
        }
    } elseif ($_POST['action'] === 'update') {
        // Logika do aktualizacji danych (jak poprzednio)
        $nazwaUczelni = $_POST['nazwa_uczelni'];
        $miasto = $_POST['miasto'];
        $kraj = $_POST['kraj'];
        $kontynent = $_POST['kontynent'];
        $adres = $_POST['adres_uczelni'];
        $uwagi = $_POST['uwagi'];

        $updateQuery = "UPDATE tUczelnie SET nazwa_uczelni = '$nazwaUczelni', miasto = '$miasto', kraj = '$kraj', kontynent = '$kontynent', adres_uczelni = '$adres', uwagi = '$uwagi' WHERE ID = $universitiId";
        if (mysqli_query($conn, $updateQuery)) {
            header("Location: ../../public/admin/universities.php?update_success=1");
            exit;
        } else {
            echo "Błąd podczas aktualizacji: " . mysqli_error($conn);
        }
    }
}
?>
