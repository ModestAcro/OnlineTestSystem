<?php

    session_start();
    require_once('../../config/connect.php');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $imieWykladowcy = $_POST['imieWykladowcy'];
        $nazwiskoWykladowcy = $_POST['nazwiskoWykladowcy'];
        $emailWykladowcy = $_POST['emailWykladowcy'];
        $hasloWykladowcy = $_POST['hasloWykladowcy'];
        $uwagiWykladowcy = $_POST['uwagiWykladowcy'];
        $stopienWykladowcy = $_POST['stopienWykladowcy'];
        $kierunkiWykladowcy = $_POST['kierunki'];

        // Przekształcenie hasła na bezpieczną formę
        $hashedPassword = password_hash($hasloWykladowcy, PASSWORD_DEFAULT);

        // Dodanie wykładowcy do bazy danych
        $addTecherQuery = "INSERT INTO tWykladowcy (imie, nazwisko, email, haslo, uwagi, aktywny, stopien) 
                  VALUES ('$imieWykladowcy', '$nazwiskoWykladowcy', '$emailWykladowcy', '$hashedPassword', '$uwagiWykladowcy', 'N', '$stopienWykladowcy')";

        if (mysqli_query($conn, $addTecherQuery)) {

            // Pobranie id_wykładowcy, którego dodaliśmy wyej 
            $id_wykladowcy = mysqli_insert_id($conn);

             // Dodanie kierunków dla wykladowcy
             if (!empty($kierunkiWykladowcy)) {
                foreach ($kierunkiWykladowcy as $id_kierunku) {
                    // Sprawdzenie, czy kierunek istnieje w tabeli tKierunki
                    $checkKierunekQuery = "SELECT ID FROM tKierunki WHERE ID = '$id_kierunku'";
                    $result = mysqli_query($conn, $checkKierunekQuery);
                    
                    if (mysqli_num_rows($result) > 0) {
                        // Pobranie ID kierunku
                        $kierunek = mysqli_fetch_assoc($result);
                        $idKierunku = $kierunek['ID'];
                        
                        // Dodajemy ud_kierunku i id_wykladowcy do tabeli pośredniej tWykladowcyKierunki
                        $addIdQuery = "INSERT INTO tWykladowcyKierunki (id_kierunku, id_wykladowcy) 
                        VALUES ('$id_kierunku', '$id_wykladowcy')";

                        mysqli_query($conn, $addIdQuery);

                    } else {
                        echo "Błąd: Kierunek o id $id_kierunku nie istnieje w bazie.";
                    }
                }
            }

            header("Location: ../../public/admin/teachers.php"); // Przekierowanie po dodaniu
            exit();
        } else {
            echo "Błąd: " . mysqli_error($conn);
        }
    }
?>