<?php
session_start();
require_once('../../config/connect.php');
require_once('../../config/functions.php');

// Sprawdzanie, czy ID pytania zostało przesłane
if (!isset($_GET['question_id'])) {
    die("ID pytania nie zostało podane.");
}

$questionId = intval($_GET['question_id']);
$userId = $_SESSION['user_id'];

// Pobierz dane pytania
$query = "SELECT * FROM tPytania WHERE ID = $questionId";
$questionResult = mysqli_query($conn, $query);
$questionData = mysqli_fetch_assoc($questionResult);

if (!$questionData) {
    die("Nie znaleziono pytania o podanym ID.");
}

// Pobierz odpowiedzi dla pytania
$answers = getAnswersByQuestionId($conn, $questionId);

// Pobierz wybrany przedmiot dla pytania
$subjectQuery = "
    SELECT tPytania.*, tPrzedmioty.nazwa AS nazwa_przedmiotu
    FROM tPytania
    INNER JOIN tPrzedmioty ON tPytania.id_przedmiotu = tPrzedmioty.ID
    WHERE tPytania.id_wykladowcy = $userId
";
$subjectInfo = mysqli_query($conn, $subjectQuery);

// Pobierz wszystkie przedmioty
$allSubjectsQuery = "SELECT * FROM tPrzedmioty";
$allSubjectsInfo = mysqli_query($conn, $allSubjectsQuery);



function getAnswersByQuestionId($conn, $questionId) {
    $query = "SELECT * FROM tOdpowiedzi WHERE id_pytania = $questionId";
    return mysqli_query($conn, $query);
}

// Pobierz załączniki dla pytania
$attachmentQuery = "SELECT * FROM tZalaczniki WHERE id_pytania = $questionId";
$attachmentResult = mysqli_query($conn, $attachmentQuery);

// Jeśli są załączniki, wyświetl je
$attachmentPaths = [];
while ($attachmentData = mysqli_fetch_assoc($attachmentResult)) {
    $attachmentPaths[] = $attachmentData['file_path']; // Przechowuj ścieżki do plików
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

    <title>Edytuj pytanie</title>
</head>
<body>
    
    <?php include '../../includes/header.php'; ?>

    <main class="container my-5">
        <div class="card shadow p-4">
            <h1 class="fs-2 fs-md-3 fs-lg-5">Edytuj pytanie</h1>

            <form action="../../includes/teacher/update_question.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="question_id" value="<?php echo $questionData['ID']; ?>">

                <!-- Treść pytania -->
                <div>
                    <h1 class="mt-5">Treść</h1>
                    <textarea type="text" class="form-control" name="question_text" rows="4" required><?php echo $questionData['tresc']; ?></textarea>
                </div>


                <!-- Odpowiedzi -->
                <h1 class="mt-5">Odpowiedzi</h1>
                <div id="answers-container">
                    <?php $counter = 1; ?>
                    <?php while ($answer = mysqli_fetch_assoc($answers)): ?>
                        <div class="answer-options d-flex flex-wrap align-items-center gap-3 mt-3 p-2 border rounded shadow-sm">
                            <div class="flex-grow-1">
                                <label class="form-label">Odpowiedź <?php echo $counter++; ?></label>
                                <textarea type="text" class="form-control" name="answers[<?php echo $answer['ID']; ?>][text]" required><?php echo $answer['tresc']; ?></textarea>
                            </div>
                            <div class="col-auto">
                                <label class="form-label">Punkty</label>
                                <input type="number" class="form-control" name="answers[<?php echo $answer['ID']; ?>][points]" value="<?php echo $answer['punkty']; ?>" required>
                            </div>
                            <div class="col-auto d-flex align-items-center">
                                <div class="form-check mt-3">
                                    <input type="checkbox" class="form-check-input" name="answers[<?php echo $answer['ID']; ?>][correct]" value="1" <?php echo ($answer['correct'] == 1) ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Poprawna</label>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Załączniki -->
                <h4 class="mt-4">Załączniki</h4>
                <div class="mb-3">
                    <input type="file" class="form-control" id="attachment-input" accept="image/*" name="attachment[]" multiple>
                </div>

                <div id="image-preview" class="row g-3">
                    <?php foreach ($attachmentPaths as $path): ?>
                        <?php if (file_exists($path)): ?>
                            <div class="col-md-3">
                                <div class="card">
                                    <img src="<?php echo $path; ?>" class="card-img-top img-thumbnail">
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <!-- Przedmiot -->
                <h4 class="mt-4">Przedmiot</h4>
                <div class="mb-3">
                    <select class="form-select" name="subject" required>
                        <option disabled selected>Wybierz przedmiot</option>
                        <?php while ($allSubject = mysqli_fetch_assoc($allSubjectsInfo)): ?>
                            <option value="<?php echo $allSubject['ID']; ?>" <?php echo ($allSubject['ID'] == $questionData['id_przedmiotu']) ? 'selected' : ''; ?>>
                                <?php echo $allSubject['nazwa']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <!-- Przyciski akcji -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" name="action" value="update" class="btn btn-outline-danger">Zapisz zmiany</button>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteQuestionModal">Usuń pytanie</button>
                </div>
            </form>
        </div>
    </main>

    <!-- Okno modalne do potwierdzenia usunięcia -->
    <div class="modal fade" id="deleteQuestionModal" tabindex="-1" aria-labelledby="deleteQuestionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteQuestionModalLabel">Potwierdzenie usunięcia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Zamknij"></button>
                </div>
                <div class="modal-body">
                    Czy na pewno chcesz usunąć to pytanie?
                </div>
                <div class="modal-footer">
                    <form action="../../includes/teacher/update_question.php" method="POST">
                        <input type="hidden" name="question_id" value="<?php echo $questionData['ID']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn btn-outline-danger">Tak, usuń</button>
                    </form>
                    <button type="button"class="btn btn-outline-danger" data-bs-dismiss="modal">Anuluj</button>
                </div>
            </div>
        </div>
    </div>
</body>

   <!-- Pliki JavaScript --> 
   <script src="../../assets/js/modal_windows.js"></script> 


   <script>
          document.getElementById('attachment-input').addEventListener('change', function(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = ''; // Czyszczenie poprzednich podglądów

        Array.from(files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = "300px"; // Ustawianie rozmiaru podglądu
                img.style.marginRight = "20px";
                previewContainer.appendChild(img);
            };
                reader.readAsDataURL(file);
            });
        });

    </script>

</html>
