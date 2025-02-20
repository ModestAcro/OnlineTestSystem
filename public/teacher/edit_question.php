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

    <main class="main">
        <div class="container">
            
            <h1>Edytuj pytanie</h1>

            <form action="../../includes/teacher/update_question.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="question_id" value="<?php echo $questionData['ID']; ?>">

                <!-- Treść pytania -->
                <h4>Treść pytania</h4>
                <input type="text" name="question_text" value="<?php echo $questionData['tresc']; ?>" required>

                <!-- Odpowiedzi -->
                <div id="answers-container">
                        <?php 
                        $counter = 1; // Licznik odpowiedzi
                        while ($answer = mysqli_fetch_assoc($answers)): ?>
                     
                                <div class="answer-options" style="display: flex; justify-content: space-between; gap: 10px; margin-top: 10px;">
                                    <div style="flex: 9;">
                                        <h4>Odpowiedź <?php echo $counter++; ?></h4> <!-- Wypisanie numeru odpowiedzi -->
                                        <input type="text" name="answers[<?php echo $answer['ID']; ?>][text]" value="<?php echo $answer['tresc']; ?>" required>
                                    </div>
                                
                                    <div style="flex: 1;">
                                        <h4>Punkty</h4>
                                        <input type="number" name="answers[<?php echo $answer['ID']; ?>][points]" value="<?php echo $answer['punkty']; ?>" required>
                                    </div>
                               
                                    <div style="flex: 1;">
                                        <h4>Poprawna</h4>
                                        <input type="checkbox" name="answers[<?php echo $answer['ID']; ?>][correct]" value="1" <?php echo ($answer['correct'] == 1) ? 'checked' : ''; ?>>
                                    </div>
                                </div>
                            
                        <?php endwhile; ?>
                </div>

                <h1>Załączniki</h1>
                <input type="file" id="attachment-input" accept="image/*" name="attachment[]" multiple>

                <div id="image-preview" style="margin-bottom: 20px;">
                    <!-- Wyświetlanie wcześniej załadowanych obrazków -->
                    <?php foreach ($attachmentPaths as $path): ?>
                        <?php if (file_exists($path)): ?>
                            <div class="image-preview-item" style="display: inline-block; margin-right: 10px;">
                                <img src="<?php echo $path; ?>" style="width: 150px; height: auto; border-radius: 5px;">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>




              <!-- Przedmiot -->
                <h1>Przedmiot</h1>
                <select name="subject" required>
                    <option disabled selected>Wybierz przedmiot</option>
                    
                    <!-- Dodaj wszystkie przedmioty z tabeli tPrzedmioty -->
                    <?php while ($allSubject = mysqli_fetch_assoc($allSubjectsInfo)): ?>
                        <option value="<?php echo $allSubject['ID']; ?>" 
                                <?php echo ($allSubject['ID'] == $questionData['id_przedmiotu']) ? 'selected' : ''; ?>>
                            <?php echo $allSubject['nazwa']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>


                <!-- Przyciski akcji -->
                <button type="submit" name="action" value="update" class="submit-btn">Zapisz zmiany</button>
                <button type="submit" name="action" value="delete" class="submit-btn" id="delete-btn">Usuń pytanie</button>
            </form>
        </div>

        <!-- Okno modalne do potwierdzenia usunięcia Wykładowcy-->
        <div id="deleteCharacterModal" class="modal">
            <div class="modal-content">
                <span class="close-btn" id="deleteCharacterModalClose">&times;</span>
                <h2>Czy na pewno chcesz usunąć te pytanie?</h2>
                <form action="../../includes/teacher/update_question.php" method="POST">
                    <input type="hidden" name="question_id" value="<?php echo $questionId; ?>">
                    <input type="hidden" name="action" value="delete">
                    <button type="submit" class="submit-btn" id="delete-btn">Tak, usuń</button>
                </form>
            </div>
        </div>
        <!-- Okno modalne do potwierdzenia usunięcia -->

    </main>

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
