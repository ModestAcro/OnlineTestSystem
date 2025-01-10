<?php
session_start();
require_once('../../config/connect.php');

// Sprawdzamy, czy formularz został wysłany
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pobranie danych pytania
    $question_text = $_POST['question_text'];
    $typ = $_POST['type'];
    $subject_id = $_POST['subject'];
    $user_id = $_SESSION['user_id'];  

    // Tworzenie folderu dla nauczyciela, jeśli jeszcze nie istnieje
    $upload_dir = "../../uploads/" . $user_id . "/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);  // Tworzy folder dla nauczyciela, jeśli nie istnieje
    }

    // Zapis pytania do bazy
    $query = "INSERT INTO tPytania (id_przedmiotu, id_wykladowcy, tresc, typ, data_stworzenia, data_aktualizacji) 
              VALUES ('$subject_id', '$user_id', '$question_text', '$typ', NOW(), NOW())";
    mysqli_query($conn, $query);
    $question_id = mysqli_insert_id($conn);  // Pobieramy ID ostatnio dodanego pytania

    // Pobranie odpowiedzi
    $answers = $_POST['answers'];
    $points = $_POST['points'];
    $correct = $_POST['correct'];

    // Mapowanie poprawnych odpowiedzi
    $correct_indices = [];
    foreach ($correct as $correct_option) {
        // Wydobycie indeksu z nazwy opcji (np. "Option 1" -> 0)
        if (preg_match('/Option (\d+)/', $correct_option, $matches)) {
            $correct_indices[] = $matches[1] - 1; // Indeksy zaczynają się od 0
        }
    }

    // Zapis odpowiedzi do bazy
    foreach ($answers as $key => $answer) {
        // Sprawdzamy, czy odpowiedź jest poprawna
        $is_correct = in_array($key, $correct_indices) ? 1 : 0;

        $query = "INSERT INTO tOdpowiedzi (id_pytania, tresc, data_stworzenia, data_aktualizacji, correct, punkty) 
                  VALUES ('$question_id', '$answer', NOW(), NOW(), '$is_correct', '{$points[$key]}')";
        mysqli_query($conn, $query);
    }

    // Obsługa plików załączników
    if (isset($_FILES['attachment']) && count($_FILES['attachment']['name']) > 0) {
        $file_names = $_FILES['attachment']['name'];
        $file_tmp_names = $_FILES['attachment']['tmp_name'];
        $file_errors = $_FILES['attachment']['error'];

        // Iterujemy po wszystkich załącznikach
        foreach ($file_names as $index => $file_name) {
            if ($file_errors[$index] === UPLOAD_ERR_OK) {
                // Generujemy ścieżkę do folderu nauczyciela
                $target_file = $upload_dir . basename($file_name);

                // Przenosimy plik i zapisujemy ścieżkę w bazie
                if (move_uploaded_file($file_tmp_names[$index], $target_file)) {
                    // Zapisujemy ścieżkę pliku w tabeli tZalaczniki
                    $query = "INSERT INTO tZalaczniki (id_pytania, file_path, data_stworzenia, data_aktualizacji) 
                              VALUES ('$question_id', '$target_file', NOW(), NOW())";
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
