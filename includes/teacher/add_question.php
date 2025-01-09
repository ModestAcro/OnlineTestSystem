<?php
session_start();
require_once('../../config/connect.php');

// Sprawdzamy, czy formularz został wysłany
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pobranie danych pytania
    $question_text = $_POST['question_text'];
    $typ = $_POST['type'];
    $subject_id = $_POST['subject'];
    $user_id = $_SESSION['user_id'];  // Pobieramy id użytkownika

    // Obsługa pliku obrazu
    $obrazek = null;
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'][0] == UPLOAD_ERR_OK) {
        $upload_dir = "../../uploads/";
        $obrazek = $upload_dir . basename($_FILES['attachment']['name'][0]);
        move_uploaded_file($_FILES['attachment']['tmp_name'][0], $obrazek);
    }

    // Zapis pytania do bazy
    $query = "INSERT INTO tPytania (id_przedmiotu, id_wykladowcy, tresc, typ, data_stworzenia, data_aktualizacji, obrazek) 
              VALUES ('$subject_id', '$user_id', '$question_text', '$typ', NOW(), NOW(), '$obrazek')";
    mysqli_query($conn, $query);
    $question_id = mysqli_insert_id($conn);  // Pobieramy ID ostatnio dodanego pytania

    // Pobranie odpowiedzi
    $answers = $_POST['answers'];
    $points = $_POST['points'];
    $correct = isset($_POST['correct']) ? $_POST['correct'] : []; // Upewniamy się, że 'correct' zawsze istnieje

    // Zapis odpowiedzi do bazy
    foreach ($answers as $key => $answer) {
        // Sprawdzamy, czy odpowiedź jest poprawna
        $is_correct = in_array($key, $correct) ? 1 : 0;

        $query = "INSERT INTO tOdpowiedzi (id_pytania, tresc, data_stworzenia, data_aktualizacji, correct, punkty) 
                  VALUES ('$question_id', '$answer', NOW(), NOW(), '$is_correct', '$points[$key]')";
        mysqli_query($conn, $query);
    }

    // Obsługa plików załączników
    if (isset($_FILES['attachment']) && count($_FILES['attachment']['name']) > 0) {
        $file_names = $_FILES['attachment']['name'];
        $file_tmp_names = $_FILES['attachment']['tmp_name'];
        $file_errors = $_FILES['attachment']['error'];

        foreach ($file_names as $index => $file_name) {
            if ($file_errors[$index] === UPLOAD_ERR_OK) {
                // Ścieżka do folderu z załącznikami
                $target_dir = "../../uploads/";
                $target_file = $target_dir . basename($file_name);
                if (move_uploaded_file($file_tmp_names[$index], $target_file)) {
                    // Zapisujemy ścieżkę pliku w bazie
                    $query = "INSERT INTO tZałączniki (question_id, file_path) VALUES ('$question_id', '$target_file')";
                    mysqli_query($conn, $query);
                }
            }
        }
    }

    // Po zapisaniu pytania, odpowiedzi i załączników, przekierowujemy do listy pytań
    header("Location: ../../public/teacher/questions.php");
    exit;
}

// Zamknięcie połączenia z bazą
mysqli_close($conn);
?>
