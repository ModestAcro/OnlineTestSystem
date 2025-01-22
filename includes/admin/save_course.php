<?php

    session_start();
    require_once('../../config/connect.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nazwa = $_POST['nazwaKierunku'];
        $uwagi = $_POST['uwagiKierunku'];
        $przedmioty = $_POST['przedmioty'];  // Tablica wybranych przedmiotów

        // Dodanie kierunku do bazy danych
        $addKierunekQuery = "INSERT INTO tKierunki (nazwa, uwagi)  
                  VALUES ('$nazwa', '$uwagi')";

        if (mysqli_query($conn, $addKierunekQuery)) {
            // Pobranie ID ostatnio dodanego kierunku
            $kierunekId = mysqli_insert_id($conn);

            // Dodanie przedmiotów do kierunku
            if (!empty($przedmioty)) {
                foreach ($przedmioty as $nrPrzedmiotu) {
                    // Sprawdzenie, czy przedmiot istnieje w tabeli tPrzedmioty
                    $checkPrzedmiotQuery = "SELECT ID FROM tPrzedmioty WHERE ID = '$nrPrzedmiotu'";
                    $result = mysqli_query($conn, $checkPrzedmiotQuery);
                    
                    if (mysqli_num_rows($result) > 0) {
                        // Pobranie ID przedmiotu
                        $przedmiot = mysqli_fetch_assoc($result);
                        $idPrzedmiotu = $przedmiot['ID'];
                        
                        // Dodajemy przedmiot do tabeli pośredniej tKierunkiPrzedmioty
                        $addPrzedmiotTokierunekQuery = "INSERT INTO tKierunkiPrzedmioty (id_kierunku, id_przedmiotu) 
                        VALUES ('$kierunekId', '$idPrzedmiotu')";

                        mysqli_query($conn, $addPrzedmiotTokierunekQuery);

                    } else {
                        echo "Błąd: Przedmiot o numerze  $idPrzedmiotu nie istnieje w bazie.";
                    }
                }
            }

            header("Location: ../../public/admin/courses.php"); // Przekierowanie po dodaniu
            exit();
        } else {
            echo "Błąd: " . mysqli_error($conn);
        }
    }
?>