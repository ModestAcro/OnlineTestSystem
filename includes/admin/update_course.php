<?php
require_once('../../config/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kierunekId = $_POST['idKierunku'];

    if ($_POST['action'] === 'delete') {

        $deleteKierunekQuery = "DELETE FROM tKierunki WHERE ID = $kierunekId";

        if (mysqli_query($conn, $deleteKierunekQuery)) {
            header("Location: ../../public/admin/courses.php");
            exit;
        } else {
            echo "Błąd podczas usuwania: " . mysqli_error($conn);
        }

    } elseif ($_POST['action'] === 'update') {
        // Logika do aktualizacji danych 
        $nazwa = $_POST['nazwaKierunku'];
        $uwagi = $_POST['uwagiKierunku'];

        $updateQuery = "UPDATE tKierunki SET nazwa = '$nazwa', uwagi = '$uwagi' WHERE ID = $kierunekId";
        if (mysqli_query($conn, $updateQuery)) {

            if(isset($_POST['przedmioty'])){
                // Pierwsze usunięcie poprzednich przypisań
                $deleteSubjectsQuery = "DELETE FROM tKierunkiPrzedmioty WHERE id_kierunku = $kierunekId";
                 mysqli_query($conn, $deleteSubjectsQuery);
 
                 // Dodanie nowych przedmitów do kierunku (po ID)
                 $subjects = $_POST['przedmioty'];
                 foreach ($subjects as $subject_id) { // Przypisanie ID przedmiotu
                     $insertQuery = "INSERT INTO tKierunkiPrzedmioty (id_kierunku, id_przedmiotu) VALUES ($kierunekId, '$subject_id')";
                     mysqli_query($conn, $insertQuery);
                 }
            }

            header("Location: ../../public/admin/courses.php");
            exit;

        } else {
            echo "Błąd podczas aktualizacji: " . mysqli_error($conn);
        }
    }
}
?>
