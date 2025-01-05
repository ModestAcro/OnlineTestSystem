<?php

    session_start();
    require_once('../../config/connect.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nazwaUczelni = $_POST['nazwaUczelni'];
        $miasto = $_POST['miastouczelni'];
        $kraj = $_POST['krajUczelni'];
        $kontynent = $_POST['kontynentUczelni'];
        $adres = $_POST['adresuczelni'];
        $uwagi = $_POST['uwagiUczelni'];

        // Dodanie grupy do bazy danych
        $addUniversitiQuery = "INSERT INTO tUczelnie (nazwa, miasto, kraj, kontynent, adres, uwagi)  
                  VALUES ('$nazwaUczelni', '$miasto', '$kraj', '$kontynent', '$adres', '$uwagi')";

        if (mysqli_query($conn, $addUniversitiQuery)) {
            header("Location: ../../public/admin/universities.php"); // Przekierowanie po dodaniu
            exit();
        } else {
            echo "Błąd: " . mysqli_error($conn);
        }
    }
?>