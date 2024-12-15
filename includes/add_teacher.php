<?php

    session_start();
    require_once('../config/connect.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $imieWykladowcy = $_POST['imieWykladowcy'];
        $nazwiskoWykladowcy = $_POST['nazwiskoWykladowcy'];
        $emailWykladowcy = $_POST['emailWykladowcy'];
        $hasloWykladowcy = $_POST['hasloWykladowcy'];
        $uwagiWykladowcy = $_POST['uwagiWykladowcy'];

        // Przekształcenie hasła na bezpieczną formę
        $hashedPassword = password_hash($hasloWykladowcy, PASSWORD_DEFAULT);

        // Dodanie wykładowcy do bazy danych
        $addTecherQuery = "INSERT INTO tWykladowcy (imie, nazwisko, email, haslo, uwagi, aktywny) 
                  VALUES ('$imieWykladowcy', '$nazwiskoWykladowcy', '$emailWykladowcy', '$hashedPassword', '$uwagiWykladowcy', 'N')";

        if (mysqli_query($conn, $addTecherQuery)) {
            header("Location: ../public/admin/teachers.php"); // Przekierowanie po dodaniu
            exit();
        } else {
            echo "Błąd: " . mysqli_error($conn);
        }
    }
?>
