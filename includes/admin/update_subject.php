<?php
require_once('../../config/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subjectId = $_POST['idPrzedmiotu'];

    if ($_POST['action'] === 'delete') {
        // Zapytanie SQL do usunięcia przedmiotu
        $deleteSubjectQuery = "DELETE FROM tPrzedmioty WHERE ID = $subjectId";

        // Wykonanie zapytania
        if (mysqli_query($conn, $deleteSubjectQuery)) {
            // Przekierowanie po sukcesie
            header("Location: ../../public/admin/subjects.php");
            exit;
        } else {
            echo "Błąd podczas usuwania: " . mysqli_error($conn);
        }

    } elseif ($_POST['action'] === 'update') {
        // Logika do aktualizacji danych 
        $nazwa = $_POST['nazwaPrzedmiotu'];
        $uwagi = $_POST['uwagiPrzedmiotu'];

        $updateSubjectQuery = "UPDATE tPrzedmioty SET nazwa = '$nazwa', uwagi = '$uwagi' WHERE ID = $subjectId";
        if (mysqli_query($conn, $updateSubjectQuery)) {
            header("Location: ../../public/admin/subjects.php");
            exit;
        } else {
            echo "Błąd podczas aktualizacji: " . mysqli_error($conn);
        }
    }
}
?>
