<?php
require_once('../../config/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = $_POST['id'];

    if ($_POST['action'] === 'delete') {
        // Zapytanie SQL do usunięcia studenta
        $deleteQuery = "DELETE FROM tStudenci WHERE ID = $studentId";

        // Wykonanie zapytania
        if (mysqli_query($conn, $deleteQuery)) {
            // Przekierowanie po sukcesie
            header("Location: ../../public/admin/students.php?delete_success=1");
            exit;
        } else {
            echo "Błąd podczas usuwania: " . mysqli_error($conn);
        }
    } elseif ($_POST['action'] === 'update') {
        // Logika do aktualizacji danych (jak poprzednio)
        $nr_albumu = $_POST['nrAlbumuStudenta'];
        $imie = $_POST['imieStudenta'];
        $nazwisko = $_POST['nazwiskoStudenta'];
        $email = $_POST['emailStudenta'];
        $haslo = $_POST['hasloStudenta'];
        $uwagi = $_POST['uwagiStudenta'];

        $updateQuery = "UPDATE tStudenci SET nr_albumu = '$nr_albumu', imie = '$imie', nazwisko = '$nazwisko', email = '$email', haslo = '$haslo', uwagi = '$uwagi' WHERE ID = $studentId";
        if (mysqli_query($conn, $updateQuery)) {
            header("Location: ../../public/admin/students.php?update_success=1");
            exit;
        } else {
            echo "Błąd podczas aktualizacji: " . mysqli_error($conn);
        }
    }
}
?>
