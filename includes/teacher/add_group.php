<?php

    session_start();
    require_once('../../config/connect.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $rokGrupy = $_POST['rok'];
        $miastoGrupy = $_POST['miasto'];
        $przedmiotGrupy = $_POST['przedmiot'];
        $nazwaGrupy = $_POST['nazwa'];
        $idWykladowcy = $_SESSION['ID']; 


        // Dodanie grupy do bazy danych
        $addGroupQuery = "INSERT INTO tGrupy (rok, miasto, przedmiot, nazwa, id_wykladowcy) 
                  VALUES ('$rokGrupy', '$miastoGrupy', '$przedmiotGrupy', '$nazwaGrupy', '$idWykladowcy')";

        if (mysqli_query($conn, $addGroupQuery)) {
            header("Location: ../../public/teacher/student_groups.php"); // Przekierowanie po dodaniu
            exit();
        } else {
            echo "Błąd: " . mysqli_error($conn);
        }
    }
?>