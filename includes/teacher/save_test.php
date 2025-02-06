<?php
session_start();
require_once('../../config/connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['action'] == 'save') {
    // Ukryte pola
    $user_id = $_SESSION['user_id'];
    $course_id = $_POST['course_id'];
    $subject_id = $_POST['subject_id'];
    $group_id = $_POST['grupa'];

    // Nie ukryte
    $name = $_POST['nazwa'];
    $limit = $_POST['limit'];
    $start_time = $_POST['start-time'];
    $end_time = $_POST['end-time'];
    $test_time = $_POST['test-time'];
    $attempts = $_POST['attempts'];

    
    // Jeśli daty są w formacie "YYYY-MM-DDTHH:MM" (np. 2025-02-01T14:00),
    // usuń "T" i przekształć je do formatu MySQL "YYYY-MM-DD HH:MM:SS"
    if ($start_time) {
        $start_time = str_replace("T", " ", $start_time) . ":00"; // Dodanie sekund
    } else {
        $start_time = NULL; // Brak daty, ustaw NULL
    }

    if ($end_time) {
        $end_time = str_replace("T", " ", $end_time) . ":00"; // Dodanie sekund
    } else {
        $end_time = NULL; // Brak daty, ustaw NULL
    }

    // Ustalanie liczby prób
    if ($attempts == 'multiple') {
        $attempts = $_POST['number-of-attempts']; 
    } elseif ($attempts == 'unlimited') {
        $attempts = -1;  // -1 oznacza brak limitu
    } else {
        $attempts = 1;  // Jeśli jedno podejście, to przypisujemy 1
    }

            // Zapytanie SQL
        $query = "
        INSERT INTO tTesty 
        (nazwa, data_utworzenia, data_rozpoczecia, data_zakonczenia, czas_trwania, ilosc_prob, id_grupy, id_wykladowcy, id_przedmiotu, id_kierunku)
        VALUES 
        ('$name', CURDATE(), " . ($start_time ? "'$start_time'" : "NULL") . ", " . ($end_time ? "'$end_time'" : "NULL") . ", '$test_time', '$attempts', '$group_id', '$user_id', '$subject_id', '$course_id')
        ";

        // Wykonanie zapytania
        if (mysqli_query($conn, $query)) {
            // Pobierz ID dodanego testu
            $test_id = mysqli_insert_id($conn);

        // Odczytaj wybrane pytania
        if (isset($_POST['pytania']) && is_array($_POST['pytania'])) {
            // Zapytanie do dodania pytań do tabeli tTestyPytania
            foreach ($_POST['pytania'] as $question_id) {
                // Wstawienie połączenia testu i pytania do tabeli tTestyPytania
                $insert_question_query = "
                    INSERT INTO tTestPytania (id_testu, id_pytania) 
                    VALUES ('$test_id', '$question_id')
                ";
                mysqli_query($conn, $insert_question_query);
            }
        }

        // Przekierowanie po dodaniu testu
        header("Location: ../../public/teacher/tests.php");
        exit();
        } else {
            echo "Błąd: " . mysqli_error($conn);
        }

        } elseif ($_POST['action'] == 'update') {

        $test_id = $_POST['test_id'];
        $limit = $_POST['limit'];
        $name = $_POST['nazwa'];
        $start_time = $_POST['start-time'];
        $end_time = $_POST['end-time'];
        $test_time = $_POST['test-time'];
        $attempts = $_POST['attempts'];

        if ($limit === "date") {
            // Jeśli daty są w formacie "YYYY-MM-DDTHH:MM", przekształć na "YYYY-MM-DD HH:MM:SS"
            $start_time = !empty($start_time) ? str_replace("T", " ", $start_time) . ":00" : NULL;
            $end_time = !empty($end_time) ? str_replace("T", " ", $end_time) . ":00" : NULL;
        } elseif ($limit === "unlimited") {
            // Jeśli limit to "unlimited", ustaw obie daty na NULL
            $start_time = NULL;
            $end_time = NULL;
        }        
        
            
        // Ustalanie liczby prób
        if ($attempts == 'multiple') {
            $attempts = $_POST['number-of-attempts']; 
        } elseif ($attempts == 'unlimited') {
            $attempts = -1;  // -1 oznacza brak limitu
        } else {
            $attempts = 1;  // Jeśli jedno podejście, to przypisujemy 1
        }

            $updateQuery = "UPDATE tTesty SET nazwa = '$name', data_rozpoczecia = " . ($start_time ? "'$start_time'" : "NULL") . ", data_zakonczenia = " . ($end_time ? "'$end_time'" : "NULL") . ", czas_trwania = '$test_time', ilosc_prob = '$attempts' WHERE ID = $test_id";

            if (mysqli_query($conn, $updateQuery)) {
                // Sprawdzenie, czy pytania są przypisane do pytania
                if (isset($_POST['pytania'])) {
                    // Pierwsze usunięcie poprzednich przypisań
                    $deleteQuestionsQuery = "DELETE FROM tTestPytania WHERE id_testu = $test_id";
                    mysqli_query($conn, $deleteQuestionsQuery);
    
                    // Dodanie nowych pytań do testu (po ID)
                    $questions = $_POST['pytania'];
                    foreach ($questions as $question_id) { // Przypisanie ID studenta
                        $insertQuery = "INSERT INTO tTestPytania (id_testu, id_pytania) VALUES ($test_id, '$question_id')";
                        mysqli_query($conn, $insertQuery);
                    }
                }
                // Przekierowanie po sukcesie
                header("Location: ../../public/teacher/edit_test.php?test_id=$test_id");
                exit;
                
            } else {
                echo "Błąd podczas aktualizacji: " . mysqli_error($conn);
            }
        } elseif ($_POST['action'] == 'delete') {

            $test_id = $_POST['test_id'];

            // Usunięcie poprzednich przypisań pytań do testu
            $deleteQuestionsQuery = "DELETE FROM tTestPytania WHERE id_testu = $test_id";
            mysqli_query($conn, $deleteQuestionsQuery);
        
            $deleteQuery = "DELETE FROM tTesty WHERE ID = $test_id";
        
            if (mysqli_query($conn, $deleteQuery)) {
                header("Location: ../../public/teacher/tests.php");
                exit;
    
            } else {
                echo "Błąd podczas usuwania: " . mysqli_error($conn);
            }    

        } else {
        echo "Błąd: metoda POST nie została użyta.";
        }
    }

  
?>
