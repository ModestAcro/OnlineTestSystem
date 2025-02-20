<?php
    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $user_id = $_SESSION['user_id'];


    function getSubjectsByTeacher($conn, $user_id) {
        $sql = "SELECT p.nazwa, p.ID
                FROM tPrzedmioty p
                JOIN tKierunkiPrzedmioty kp ON p.ID = kp.id_przedmiotu
                JOIN tKierunki k ON kp.id_kierunku = k.ID
                JOIN tWykladowcyKierunki wk ON wk.id_kierunku = k.ID
                WHERE wk.id_wykladowcy = $user_id";
    
        $result = mysqli_query($conn, $sql);
        
        return $result;
    }

    $subjectInfo = getSubjectsByTeacher($conn, $user_id);
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
    <div class="card shadow p-4">
        <form action="../../includes/teacher/save_question.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="type" value="Wielokrotnego wyboru">

            <div class="d-flex justify-content-between align-items-center">
                <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Pytanie wielokrotnego wyboru</h1>
                <button class="btn btn-outline-danger" id="add-answer-btn">
                    <i class="bi bi-plus-circle"></i> Dodaj odpowiedź
                </button>
             </div>

            <textarea type="text" class="form-control mb-3 mt-5 " rows="4" name="question_text" id="question_text" placeholder="Wpisz treść pytania" required></textarea>

            <div id="answers-container"></div>

            <h1 class="mt-4">Załączniki</h1>
            <input type="file" id="attachment-input" class="form-control" accept="image/*" name="attachment[]" multiple>
            <div id="image-preview" class="d-flex flex-wrap mt-3"></div>

            <h1 class="mt-4">Przedmiot</h1>
            <select name="subject" class="form-select mb-4" required>
                <option value="" disabled selected>Wybierz przedmiot</option>
                <?php while ($subject = mysqli_fetch_assoc($subjectInfo)): ?>
                    <option value="<?php echo $subject['ID']; ?>">
                        <?php echo $subject['nazwa']; ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit" class="btn btn-outline-danger">Zapisz pytanie</button>
        </form>
    </div>
</main>

<script>
// Funkcja do dodawania nowej odpowiedzi
document.getElementById('add-answer-btn').addEventListener('click', function () {
    const answersContainer = document.getElementById('answers-container');
    const answerIndex = answersContainer.children.length + 1;

    const newAnswerDiv = document.createElement('div');
    newAnswerDiv.className = 'answer-options d-flex flex-wrap align-items-center gap-3 mt-3 p-2 border rounded';

    newAnswerDiv.innerHTML = `
        <div class="flex-grow-1">
            <h4>Odpowiedź ${answerIndex}</h4>
            <textarea type="text" class="form-control" name="answers[]" rows="2" placeholder="Wpisz odpowiedź ${answerIndex}" required></textarea>
        </div>
        <div class="col-auto">
            <h4>Punkty</h4>
            <input type="number" class="form-control" name="points[]" value="0">
        </div>
        <div class="col-auto d-flex align-items-center">
            <div class="form-check mt-3">
                <input type="checkbox" class="form-check-input" name="correct[]" value="Option ${answerIndex}">
                <label class="form-check-label">Poprawna</label>
            </div>
        </div>
        <div class="col-auto">
            <button type="button" class="btn btn-danger remove-answer-btn">Usuń</button>
        </div>
    `;

    answersContainer.appendChild(newAnswerDiv);

    newAnswerDiv.querySelector('.remove-answer-btn').addEventListener('click', function() {
        answersContainer.removeChild(newAnswerDiv);
    });
});

// Funkcja do podglądu obrazów
document.getElementById('attachment-input').addEventListener('change', function(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('image-preview');
    previewContainer.innerHTML = '';

    Array.from(files).forEach(file => {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = "img-thumbnail m-2";
            img.style.width = "150px";
            previewContainer.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
});
</script>



</body>
</html>
