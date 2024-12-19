<?php
require_once('../../config/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $groupId = $_POST['id'];

    if ($_POST['action'] === 'delete') {
        // Zapytanie SQL do usunięcia nauczyciela
        $deleteQuery = "DELETE FROM tGrupyStudentow WHERE ID = $groupId";

        // Wykonanie zapytania
        if (mysqli_query($conn, $deleteQuery)) {
            // Przekierowanie po sukcesie
            header("Location: ../../public/teacher/student_groups.php?delete_success=1");
            exit;
        } else {
            echo "Błąd podczas usuwania: " . mysqli_error($conn);
        }
    } elseif ($_POST['action'] === 'update') {
        // Logika do aktualizacji danych (jak poprzednio)
        $rok = $_POST['rok'];
        $miasto = $_POST['miasto'];
        $przedmiot = $_POST['przedmiot'];
        $nazwa = $_POST['nazwa'];

        $updateQuery = "UPDATE tGrupyStudentow SET rok = '$rok', miasto = '$miasto', przedmiot = '$przedmiot', nazwa = '$nazwa' WHERE ID = $groupId";
        if (mysqli_query($conn, $updateQuery)) {
            header("Location: ../../public/teacher/student_groups.php?update_success=1");
            exit;
        } else {
            echo "Błąd podczas aktualizacji: " . mysqli_error($conn);
        }
    }
}
?>
