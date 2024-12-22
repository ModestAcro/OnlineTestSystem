<?php
require_once('../../config/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $universityId = $_POST['idUczelni'];

    if ($_POST['action'] === 'delete') {
        // Zapytanie SQL do usunięcia uczelni
        $deleteUniversityQuery = "DELETE FROM tUczelnie WHERE ID = $universityId";

        // Wykonanie zapytania
        if (mysqli_query($conn, $deleteUniversityQuery)) {
            // Przekierowanie po sukcesie
            header("Location: ../../public/admin/universities.php");
            exit;
        } else {
            echo "Błąd podczas usuwania: " . mysqli_error($conn);
        }

    } elseif ($_POST['action'] === 'update') {
        // Logika do aktualizacji danych (jak poprzednio)
        $nazwa = $_POST['nazwaUczelni'];
        $miasto = $_POST['miastoUczelni'];
        $kraj = $_POST['krajUczelni'];
        $kontynent = $_POST['kontynentUczelni'];
        $adres = $_POST['adresUczelni'];
        $uwagi = $_POST['uwagiUczelni'];

        $updateUniversityQuery = "UPDATE tUczelnie SET nazwa = '$nazwa', miasto = '$miasto', kraj = '$kraj', kontynent = '$kontynent', adres = '$adres', uwagi = '$uwagi' WHERE ID = $universityId";
        if (mysqli_query($conn, $updateUniversityQuery)) {
            header("Location: ../../public/admin/universities.php");
            exit;
        } else {
            echo "Błąd podczas aktualizacji: " . mysqli_error($conn);
        }
    }
}
?>
