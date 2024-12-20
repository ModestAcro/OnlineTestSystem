<?php
session_start();
require_once('../../config/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rokGrupy = $_POST['rok'];
    $miastoGrupy = $_POST['miasto'];
    $przedmiotGrupy = $_POST['przedmiot'];
    $nazwaGrupy = $_POST['nazwa'];
    $idWykladowcy = $_SESSION['ID']; 
    $studenci = $_POST['studenci'];  // Tablica wybranych studentów
    
    // Dodanie grupy do bazy danych
    $addGroupQuery = "INSERT INTO tGrupy (rok, miasto, przedmiot, nazwa, id_wykladowcy) 
                      VALUES ('$rokGrupy', '$miastoGrupy', '$przedmiotGrupy', '$nazwaGrupy', '$idWykladowcy')";
    
    if (mysqli_query($conn, $addGroupQuery)) {
        // Pobranie ID ostatnio dodanej grupy
        $groupId = mysqli_insert_id($conn);

        // Dodanie studentów do grupy
        if (!empty($studenci)) {
            foreach ($studenci as $nr_albumu) {
                // Sprawdzenie, czy student istnieje w tabeli tStudenci
                $checkStudentQuery = "SELECT ID FROM tStudenci WHERE nr_albumu = '$nr_albumu'";
                $result = mysqli_query($conn, $checkStudentQuery);
                
                if (mysqli_num_rows($result) > 0) {
                    // Pobranie ID studenta
                    $student = mysqli_fetch_assoc($result);
                    $idStudenta = $student['ID'];
                    
                    // Dodajemy studenta do tabeli pośredniej tGrupyStudenci
                    $addStudentToGroupQuery = "INSERT INTO tGrupyStudenci (id_grupy, id_studenta) 
                                               VALUES ('$groupId', '$idStudenta')";
                    mysqli_query($conn, $addStudentToGroupQuery);
                } else {
                    echo "Błąd: Student o numerze albumu $nr_albumu nie istnieje w bazie.";
                }
            }
        }

        header("Location: ../../public/teacher/student_groups.php"); // Przekierowanie po dodaniu
        exit();
    } else {
        echo "Błąd: " . mysqli_error($conn);
    }
}
?>
