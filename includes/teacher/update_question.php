<?php
session_start();
require_once('../../config/connect.php');
require_once('../../config/functions.php');

// Sprawdzanie, czy ID pytania zostało przesłane
if (!isset($_POST['question_id'])) {
    die("ID pytania nie zostało podane.");
}

$questionId = intval($_POST['question_id']);
$userId = $_SESSION['user_id'];

// Pobierz dane pytania
$query = "SELECT * FROM tPytania WHERE ID = $questionId";
$questionResult = mysqli_query($conn, $query);
$questionData = mysqli_fetch_assoc($questionResult);

if (!$questionData) {
    die("Nie znaleziono pytania o podanym ID.");
}

// Sprawdzamy, jaka akcja została wybrana: update lub delete
if (isset($_POST['action'])) {
    if ($_POST['action'] == 'update') {
        // Aktualizacja pytania
        $questionText = mysqli_real_escape_string($conn, $_POST['question_text']);
        $subject = intval($_POST['subject']);

        // Aktualizujemy dane pytania
        $updateQuery = "UPDATE tPytania SET tresc = '$questionText', id_przedmiotu = $subject WHERE ID = $questionId";
        $updateResult = mysqli_query($conn, $updateQuery);

        if (!$updateResult) {
            die("Błąd aktualizacji pytania: " . mysqli_error($conn));
        }

        // Aktualizowanie odpowiedzi
        foreach ($_POST['answers'] as $answerId => $answerData) {
            $answerText = mysqli_real_escape_string($conn, $answerData['text']);
            $points = intval($answerData['points']);
            $correct = isset($answerData['correct']) ? 1 : 0;

            // Sprawdzamy, czy odpowiedź już istnieje, czy trzeba ją zaktualizować
            if ($answerId == 'new') {
                // Dodajemy nową odpowiedź
                $insertQuery = "INSERT INTO tOdpowiedzi (id_pytania, tresc, punkty, correct) VALUES ($questionId, '$answerText', $points, $correct)";
                $insertResult = mysqli_query($conn, $insertQuery);
            } else {
                // Aktualizujemy istniejącą odpowiedź
                $updateAnswerQuery = "UPDATE tOdpowiedzi SET tresc = '$answerText', punkty = $points, correct = $correct WHERE ID = $answerId";
                $updateAnswerResult = mysqli_query($conn, $updateAnswerQuery);
            }
        }

    
       // Przesyłanie załączników (obrazków)
if (isset($_FILES['attachment'])) {
    $attachments = $_FILES['attachment'];

    // Załóżmy, że $userId zawiera identyfikator użytkownika
    $userId = $_SESSION['user_id'];  // Przykład pobierania ID użytkownika z sesji

    // Ustalamy ścieżkę do folderu użytkownika
    $uploadDir = '../../uploads/' . $userId;

    // Sprawdzamy, czy folder dla użytkownika już istnieje, jeśli nie, tworzymy go
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true); // Tworzymy folder z odpowiednimi uprawnieniami
    }

    // Usunięcie starych załączników z bazy danych i serwera
    $attachmentQuery = "SELECT * FROM tZalaczniki WHERE id_pytania = $questionId";
    $attachmentResult = mysqli_query($conn, $attachmentQuery);
    $attachmentPaths = [];

    // Zbieranie ścieżek do plików, które trzeba usunąć
    while ($attachmentData = mysqli_fetch_assoc($attachmentResult)) {
        $attachmentPaths[] = $attachmentData['file_path'];
    }

    // Usuwanie starych plików z serwera
    foreach ($attachmentPaths as $path) {
        if (file_exists($path)) {
            unlink($path); // Usuwanie pliku z serwera
        }
    }

    // Usunięcie starych załączników z bazy danych
    $deleteAttachmentsQuery = "DELETE FROM tZalaczniki WHERE id_pytania = $questionId";
    mysqli_query($conn, $deleteAttachmentsQuery);

    // Dodanie nowych załączników
    for ($i = 0; $i < count($attachments['name']); $i++) {
        $fileName = $attachments['name'][$i];
        $fileTmpName = $attachments['tmp_name'][$i];
        $fileError = $attachments['error'][$i];
        $fileSize = $attachments['size'][$i];

        if ($fileError === 0) {
            // Używamy oryginalnej nazwy pliku
            $fileName = basename($fileName);
            $fileDestination = $uploadDir . '/' . $fileName;

            // Sprawdzamy, czy plik o tej samej nazwie już istnieje
            if (file_exists($fileDestination)) {
                // Dodajemy numer do nazwy pliku, aby uniknąć nadpisania
                $fileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . time() . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
                $fileDestination = $uploadDir . '/' . $fileName;
            }

            // Przesyłamy plik do folderu użytkownika
            if (move_uploaded_file($fileTmpName, $fileDestination)) {
                // Dodanie nowego załącznika do bazy danych
                $insertAttachmentQuery = "INSERT INTO tZalaczniki (id_pytania, file_path) VALUES ($questionId, '$fileDestination')";
                mysqli_query($conn, $insertAttachmentQuery);
            }
        }
    }
}

        // Po pomyślnej aktualizacji, przekierowujemy użytkownika do strony z pytaniem
        header("Location: ../../public/teacher/questions.php");
        exit();
    }

    // Usuwanie pytania
    if ($_POST['action'] == 'delete') {
        // Usuwamy pytanie oraz odpowiedzi
        $deleteAnswersQuery = "DELETE FROM tOdpowiedzi WHERE id_pytania = $questionId";
        $deleteAnswersResult = mysqli_query($conn, $deleteAnswersQuery);

        if (!$deleteAnswersResult) {
            die("Błąd podczas usuwania odpowiedzi: " . mysqli_error($conn));
        }

        $deleteQuestionQuery = "DELETE FROM tPytania WHERE ID = $questionId";
        $deleteQuestionResult = mysqli_query($conn, $deleteQuestionQuery);

        if (!$deleteQuestionResult) {
            die("Błąd podczas usuwania pytania: " . mysqli_error($conn));
        }

        // Po usunięciu pytania, przekierowujemy użytkownika na stronę z listą pytań
        header("Location: ../../public/teacher/questions.php");
        exit();
    }
}
