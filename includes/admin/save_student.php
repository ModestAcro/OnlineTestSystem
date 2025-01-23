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
        $id_kierunku = $_POST['kierunekStudenta'];
        $rok = $_POST['rokStudenta'];
        $rocznik = $_POST['rocznikStudenta'];


        // Przekształcenie hasła na bezpieczną formę
        $hashedPassword = password_hash($hasloStudenta, PASSWORD_DEFAULT);

        // Dodanie wykładowcy do bazy danych
        $addStudentQuery = "INSERT INTO tStudenci (nr_albumu, imie, nazwisko, email, haslo, uwagi, aktywny, id_kierunku, rok, rocznik) 
                  VALUES ('$nr_albumu', '$imieStudenta', '$nazwiskoStudenta', '$emailStudenta', '$hashedPassword', '$uwagiStudenta', 'N', '$id_kierunku', '$rok', '$rocznik')";

        if (mysqli_query($conn, $addStudentQuery)) {
            header("Location: ../../public/admin/students.php"); // Przekierowanie po dodaniu
            exit();
        } else {
            echo "Błąd: " . mysqli_error($conn);
        }
    }
?>