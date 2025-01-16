<?php

    session_start();
    require_once('../../config/connect.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nazwaPrzedmiotu = $_POST['nazwa'];
        $uwagiPrzedmiotu = $_POST['uwagi'];

        // Dodanie grupy do bazy danych
        $addSubjectQuery = "INSERT INTO tPrzedmioty (nazwa, uwagi) 
                  VALUES ('$nazwaPrzedmiotu', '$uwagiPrzedmiotu')";

        if (mysqli_query($conn, $addSubjectQuery)) {
            header("Location: ../../public/admin/subjects.php"); // Przekierowanie po dodaniu
            exit();
        } else {
            echo "Błąd: " . mysqli_error($conn);
        }
    }
?>