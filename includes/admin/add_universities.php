<?php

    session_start();
    require_once('../../config/connect.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nazwaUczelni = $_POST['nazwa_uczelni'];
        $miasto = $_POST['miasto'];
        $kraj = $_POST['kraj'];
        $kontynent = $_POST['kontynent'];
        $adres = $_POST['adres_uczelni'];
        $uwagi = $_POST['uwagi'];

        // Dodanie grupy do bazy danych
        $addUniversitiQuery = "INSERT INTO tUczelnie (nazwa_uczelni, miasto, kraj, kontynent, adres_uczelni, uwagi)  
                  VALUES ('$nazwaUczelni', '$miasto', '$kraj', '$kontynent', '$adres', '$uwagi')";

        if (mysqli_query($conn, $addUniversitiQuery)) {
            header("Location: ../../public/admin/universities.php"); // Przekierowanie po dodaniu
            exit();
        } else {
            echo "Błąd: " . mysqli_error($conn);
        }
    }
?>