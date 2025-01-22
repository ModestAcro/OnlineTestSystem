<?php

    session_start();
    require_once('../../config/connect.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nazwa = $_POST['nazwaTestu'];
        $dataRozpoczecia = $_POST['dataRozpoczeciaTestu'];
        $dataZakonczenia = $_POST['dataZakonczeniaTestu'];
        $czasTrwania = $_POST['czasTrwaniaTestu'];
        $iloscProb = $_POST['iloscProbTestu'];
        $idWykladowcy = $_SESSION['user_id']; 

        // Dodanie testu do bazy danych
        $addTestQuery = "INSERT INTO tTesty (nazwa, data_utworzenia, data_rozpoczecia, data_zakonczenia, czas_trwania, ilosc_prob, id_grupy, id_wykladowcy) 
                  VALUES ('$nazwa', CURDATE(), '$dataRozpoczecia', '$dataZakonczenia', '$czasTrwania', '$iloscProb', NULL, '$idWykladowcy')";

        if (mysqli_query($conn, $addTestQuery)) {
            header("Location: ../../public/teacher/tests.php"); // Przekierowanie po dodaniu
            exit();
        } else {
            echo "Błąd: " . mysqli_error($conn);
        }
    }
?>