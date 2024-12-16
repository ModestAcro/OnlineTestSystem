<?php
require_once('../../config/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $teacherId = $_POST['id'];

    if ($_POST['action'] === 'delete') {
        // Zapytanie SQL do usunięcia nauczyciela
        $deleteQuery = "DELETE FROM tWykladowcy WHERE ID = $teacherId";

        // Wykonanie zapytania
        if (mysqli_query($conn, $deleteQuery)) {
            // Przekierowanie po sukcesie
            header("Location: ../../public/admin/teachers.php?delete_success=1");
            exit;
        } else {
            echo "Błąd podczas usuwania: " . mysqli_error($conn);
        }
    } elseif ($_POST['action'] === 'update') {
        // Logika do aktualizacji danych (jak poprzednio)
        $imie = $_POST['imieWykladowcy'];
        $nazwisko = $_POST['nazwiskoWykladowcy'];
        $email = $_POST['emailWykladowcy'];
        $haslo = $_POST['hasloWykladowcy'];
        $uwagi = $_POST['uwagiWykladowcy'];

        $updateQuery = "UPDATE tWykladowcy SET imie = '$imie', nazwisko = '$nazwisko', email = '$email', haslo = '$haslo', uwagi = '$uwagi' WHERE ID = $teacherId";
        if (mysqli_query($conn, $updateQuery)) {
            header("Location: ../../public/admin/teachers.php?update_success=1");
            exit;
        } else {
            echo "Błąd podczas aktualizacji: " . mysqli_error($conn);
        }
    }
}
?>
