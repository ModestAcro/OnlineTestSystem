<?php
require_once('../../config/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjectId = $_POST['id'];

    if ($_POST['action'] === 'delete') {
        // Zapytanie SQL do usunięcia nauczyciela
        $deleteQuery = "DELETE FROM tPrzedmioty WHERE ID = $subjectId";

        // Wykonanie zapytania
        if (mysqli_query($conn, $deleteQuery)) {
            // Przekierowanie po sukcesie
            header("Location: ../../public/admin/subjects.php?delete_success=1");
            exit;
        } else {
            echo "Błąd podczas usuwania: " . mysqli_error($conn);
        }
    } elseif ($_POST['action'] === 'update') {
        // Logika do aktualizacji danych (jak poprzednio)
        $nazwa = $_POST['nazwa'];
        $uwagi = $_POST['uwagi'];

        $updateQuery = "UPDATE tPrzedmioty SET nazwa = '$nazwa', uwagi = '$uwagi' WHERE ID = $subjectId";
        if (mysqli_query($conn, $updateQuery)) {
            header("Location: ../../public/admin/subjects.php?update_success=1");
            exit;
        } else {
            echo "Błąd podczas aktualizacji: " . mysqli_error($conn);
        }
    }
}
?>
