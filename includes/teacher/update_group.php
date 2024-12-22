<?php
require_once('../../config/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $groupId = $_POST['id'];

    if ($_POST['action'] === 'delete') {
        // Zapytanie SQL do usunięcia grupy
        $deleteQuery = "DELETE FROM tGrupy WHERE ID = $groupId";

        // Wykonanie zapytania
        if (mysqli_query($conn, $deleteQuery)) {
            // Przekierowanie po sukcesie
            header("Location: ../../public/teacher/student_groups.php?delete_success=1");
            exit;
        } else {
            echo "Błąd podczas usuwania: " . mysqli_error($conn);
        }
    } elseif ($_POST['action'] === 'update') {
        // Logika do aktualizacji danych
        $rok = $_POST['rok'];
        $miasto = $_POST['miasto'];
        $przedmiot = $_POST['przedmiot'];
        $nazwa = $_POST['nazwa'];

        // Aktualizacja tabeli tGrupy
        $updateQuery = "UPDATE tGrupy SET rok = '$rok', miasto = '$miasto', przedmiot = '$przedmiot', nazwa = '$nazwa' WHERE ID = $groupId";
        
        if (mysqli_query($conn, $updateQuery)) {
            // Sprawdzenie, czy studenci są przypisani do grupy
            if (isset($_POST['studenci'])) {
                // Pierwsze usunięcie poprzednich przypisań
                $deleteStudentsQuery = "DELETE FROM tGrupyStudenci WHERE id_grupy = $groupId";
                mysqli_query($conn, $deleteStudentsQuery);

                // Dodanie nowych studentów do grupy (po ID)
                $studenci = $_POST['studenci'];
                foreach ($studenci as $id_studenta) { // Przypisanie ID studenta
                    $insertQuery = "INSERT INTO tGrupyStudenci (id_grupy, id_studenta) VALUES ($groupId, '$id_studenta')";
                    mysqli_query($conn, $insertQuery);
                }
            }

            // Przekierowanie po sukcesie
            header("Location: ../../public/teacher/student_groups.php?update_success=1");
            exit;
        } else {
            echo "Błąd podczas aktualizacji: " . mysqli_error($conn);
        }
    }
}
?>
