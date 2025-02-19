<?php
    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $userId = $_SESSION['user_id'];

    // Pobiera liczbę grup związanych z nauczycielem
    $QuestionCount = getGroupCountByTeacherId($conn, 'tPytania', $userId); 
    $QuestionInfo = getTableInfoByUserId($conn, 'tPytania', $userId);

    function getAnswersByQuestionId($conn, $questionId) {
        $query = "SELECT * FROM tOdpowiedzi WHERE id_pytania = $questionId";
        return mysqli_query($conn, $query);
    }

    function getSubjectNameById($conn, $subjectId) {
        // Zapytanie SQL, aby pobrać nazwę przedmiotu na podstawie id
        $subjectQuery = "SELECT nazwa FROM tPrzedmioty WHERE id = $subjectId";
        
        // Wykonanie zapytania
        $result = mysqli_query($conn, $subjectQuery);
        
        // Sprawdzenie, czy zapytanie się powiodło
        if ($result) {
            // Pobranie wyniku jako tablica asocjacyjna
            $subject = mysqli_fetch_assoc($result);
            return $subject['nazwa'];
        } else {
            // Zwrócenie null w przypadku błędu
            return null;
        }
    }
    

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   

    <!-- Bootstrap 5.3 css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../assets/css/main.css">

    <title>Pytania</title>
</head>
<body>

    <?php include '../../includes/header.php'; ?>

    <main class="main">
        <div class="container">
            <div class="title">
                <h1>Lista Pytań</h1>

                <!-- Przycisk "Dodaj pytanie" -->
                <div class="questions">
                    <a class="" style="background: #45a049; color: white; padding: 10px; border-radius: 10px;" href="add_multichoice.php">Dodaj pytanie</a>
                </div>
                <!-- Przycisk "Dodaj pytanie" -->

            </div>

            <p>Ilość: <?php echo $QuestionCount; ?></p>
            <table>
                <thead>
                    <tr>
                        <th style="width: 20%;">Przedmiot</th> <!-- Najwięcej miejsca dla dłuższego tekstu -->
                        <th style="width: 25%;">Pytanie</th>   <!-- Średnio dużo miejsca -->
                        <th style="width: 10%;">Typ</th>       <!-- Krótki tekst -->
                        <th style="width: 30%;">Odpowiedzi</th> <!-- Dłuższy tekst -->
                        <th style="width: 10%;">Całkowita liczba punktów</th> <!-- Liczby -->
                        <th style="width: 5%;"></th> <!-- Pusta kolumna -->
                    </tr>

                </thead>
                <tbody>
                    <?php while ($QuestionData = mysqli_fetch_assoc($QuestionInfo)): ?>
                        <tr>
                        <?php
                            // Pobierz nazwę przedmiotu na podstawie id_przedmiotu
                            $subjectId = $QuestionData['id_przedmiotu'];
                            $subjectName = getSubjectNameById($conn, $subjectId);
                        ?>
                            <td><?php echo $subjectName; ?></td>
                            <td><?php echo $QuestionData['tresc']; ?></td>
                            <td><?php echo $QuestionData['typ']; ?></td>
                            <td>
                                <ul>
                                    <?php
                                    // Pobierz odpowiedzi dla pytania
                                    $answers = getAnswersByQuestionId($conn, $QuestionData['ID']);
                                    $totalPoints = 0; // Zmienna do sumowania punktów
                                    $questionNumber = 1; // Numer pytania

                                    while ($answer = $answers->fetch_assoc()): 
                                        $totalPoints += $answer['punkty']; // Dodaj punkty z bieżącej odpowiedzi
                                    ?>
                                        <li>
                                            Odpowiedź <?php echo $questionNumber; ?> 
                                            (Punkty: <?php echo number_format($answer['punkty'], 2); ?>)
                                            <?php if ($answer['correct'] == 1): ?>
                                                <strong>(Poprawna)</strong>
                                            <?php endif; ?>
                                        </li>
                                    <?php 
                                        $questionNumber++; // Zwiększ numer pytania
                                    endwhile; 
                                    ?>
                                </ul>
                            </td>
                            <!-- Kolumna z całkowitą liczbą punktów -->
                            <td>
                                <strong><?php echo $totalPoints; ?></strong>
                            </td>
                            <!-- Przyciski "Modyfikuj" -->
                            <td>
                                <a href="edit_question.php?question_id=<?php echo $QuestionData['ID']; ?>" class="btn-edit">
                                    <img src="../../assets/images/icons/edit.svg" class="edit-icon">
                                </a>
                            </td>
                            <!-- Przyciski "Modyfikuj" -->
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        </div>
    </main>    


    <!-- Plik JavaScript --> 
    <script src="../../assets/js/modal_windows.js"></script> 

</body>
</html>