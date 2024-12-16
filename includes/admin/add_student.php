<?php

    session_start();
    require_once('../../config/connect.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nr_albumu = $_POST['nrAlbumuStudenta'];
        $imieStudenta = $_POST['imieStudenta'];
        $nazwiskoStudenta = $_POST['nazwiskoStudenta'];
        $emailStudenta = $_POST['emailStudenta'];
        $hasloStudenta = $_POST['hasloStudenta'];
        $uwagiStudenta = $_POST['uwagiStudenta'];

        // Przekształcenie hasła na bezpieczną formę
        $hashedPassword = password_hash($hasloStudenta, PASSWORD_DEFAULT);

        // Dodanie wykładowcy do bazy danych
        $addStudentQuery = "INSERT INTO tStudenci (nr_albumu, imie, nazwisko, email, haslo, uwagi, aktywny) 
                  VALUES ('$nr_albumu', '$imieStudenta', '$nazwiskoStudenta', '$emailStudenta', '$hashedPassword', '$uwagiStudenta', 'N')";

        if (mysqli_query($conn, $addStudentQuery)) {
            header("Location: ../../public/admin/students.php"); // Przekierowanie po dodaniu
            exit();
        } else {
            echo "Błąd: " . mysqli_error($conn);
        }
    }
?>