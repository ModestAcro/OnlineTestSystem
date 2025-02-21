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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/main.css">

    <title>Pytania</title>
</head>
<body>

    <?php include '../../includes/header.php'; ?>

   
    <main class="container my-5">
    <div class="container card shadow p-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Lista pytań</h1>
            <a class="btn btn-outline-danger" href="add_multichoice.php">
                <i class="bi bi-plus-circle"></i> Utwórz pytanie
            </a>
        </div>
        <p>Ilość: <?php echo $QuestionCount; ?></p>



        <div class="table-responsive mt-5">
            <table class="table">
                <thead class="table-active">
                    <tr>
                        <th>Przedmiot</th>
                        <th>Pytanie</th>
                        <th>Typ</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($QuestionData = mysqli_fetch_assoc($QuestionInfo)): ?>
                        <tr>
                            <?php
                                $subjectId = $QuestionData['id_przedmiotu'];
                                $subjectName = getSubjectNameById($conn, $subjectId);
                            ?>

                            <td><?php echo $subjectName; ?></td>
                            <td><?php echo $QuestionData['tresc']; ?></td>
                            <td><?php echo $QuestionData['typ']; ?></td>
                            <td>
                                <a href="edit_question.php?question_id=<?php echo $QuestionData['ID']; ?>" class="btn">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    </main>


    <!-- Plik JavaScript --> 
    <script src="../../assets/js/modal_windows.js"></script> 

</body>
</html>