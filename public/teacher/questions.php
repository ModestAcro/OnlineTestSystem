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
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Pytania</title>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="left-header">
                <a class="nav-btn" href="teacher_dashboard.php">Strona główna</a>
                <a class="nav-btn" href="student_groups.php">Grupy studentów</a>
            </div>
            <div class="right-header">
                <span class="name"><?php echo $_SESSION['user_name'] . ' ' . $_SESSION['user_surname']; ?></span>

                <!-- Formularz wylogowania -->
                <?php
                    include('../../includes/logout_modal.php');
                ?>
                <!-- Formularz wylogowania -->

            </div>
        </div>
    </header>

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
                        <th>Przedmiot</th>
                        <th>Pytanie</th>
                        <th>Typ</th>
                        <th>Odpowiedzi</th>
                        <th>Całkowita liczba punktów</th>
                        <th></th>
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

                                    while ($answer = $answers->fetch_assoc()): 
                                        $totalPoints += $answer['punkty']; // Dodaj punkty z bieżącej odpowiedzi
                                    ?>
                                        <li>
                                            <?php echo $answer['tresc']; ?>
                                            (Punkty: <?php echo $answer['punkty']; ?>)
                                            <?php if ($answer['correct'] == 1): ?>
                                                <strong>(Poprawna)</strong>
                                            <?php endif; ?>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            </td>
                            <!-- Kolumna z całkowitą liczbą punktów -->
                            <td>
                                <strong><?php echo $totalPoints; ?></strong>
                            </td>
                            <!-- Przyciski "Modyfikuj" -->
                            <td>
                                <a href="edit_group.php?question_id=<?php echo $QuestionData['ID']; ?>" class="btn-edit">
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