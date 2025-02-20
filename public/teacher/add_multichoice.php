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
    <link rel="stylesheet" href="../../assets/css/main.css">

    <title>Pytania</title>
</head>
<body>

    <?php include '../../includes/header.php'; ?>

    <main class="main">
        <div class="container">
            <form action="../../includes/teacher/save_question.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="type" value="Wielokrotnego wyboru">

            <div class="title">
                <h1>Pytanie wielokrotnego wyboru</h1>
                <button type="button" id="add-answer-btn" style="background: #45a049; color: white; padding: 10px; border-radius: 10px;">Dodaj odpowiedź</button>
            </div>

            <input type="text" name="question_text" id="question_text" placeholder="Wpisz treść pytania" required>

            
            <div id="answers-container">
                
            </div>

            <h1>Załączniki</h1>
            <input type="file" id="attachment-input" accept="image/*" name="attachment[]" multiple>

            <div id="image-preview" style="margin-bottom: 20px;">
                <!-- Wybrane obrazki będą wyświetlane tutaj -->
            </div>

            <!-- Lista przedmiotów do przypisania -->
            <h1>Przedmiot</h1>
                <select name="subject" required>
                    <option value="" disabled selected>Wybierz przedmiot</option>
                        <?php while ($subject = mysqli_fetch_assoc($subjectInfo)): ?>
                            <option value="<?php echo $subject['ID']; ?>">
                                <?php echo $subject['nazwa']; ?>
                            </option>
                        <?php endwhile; ?>
                </select>
            <!-- Lista przedmiotów do przypisania -->


            <button type="submit" class="add-btn" style="width: 150px; color: white;">Zapisz pytanie</button>

            </form>
        </div>
    </main>    

    <script>
    // Funkcja obsługująca dodawanie nowych pól odpowiedzi
    document.getElementById('add-answer-btn').addEventListener('click', function () {
    const answersContainer = document.getElementById('answers-container');

    // Licznik odpowiedzi na podstawie aktualnej liczby odpowiedzi
    const currentAnswerCount = answersContainer.children.length + 1;

    // Tworzenie nowego elementu div z klasą "answer-options"
    const newAnswerDiv = document.createElement('div');
    newAnswerDiv.className = 'answer-options';
    newAnswerDiv.style.display = 'flex';
    newAnswerDiv.style.justifyContent = 'space-between';
    newAnswerDiv.style.gap = '10px';
    newAnswerDiv.style.marginTop = '10px';

    // Tworzenie pól dla odpowiedzi, punktów i poprawności
    newAnswerDiv.innerHTML = `
        <div style="flex: 9;">
            <h4>Odpowiedź</h4>
            <input type="text" name="answers[]" placeholder="Wpisz odpowiedź ${currentAnswerCount}" required>
        </div>
        <div style="flex: 1;">
            <h4>Punkty</h4>
            <input type="number" name="points[]" value="0" >
        </div>
        <div style="flex: 1;">
            <h4>Poprawna?</h4>
            <input type="checkbox" name="correct[]" value="Option ${currentAnswerCount}" >
        </div>
        <div style="flex: 1; align-self: flex-end;">
            <button type="button" class="remove-answer-btn" style="background: #e74c3c; color: white; padding: 5px 10px; border-radius: 5px; border: none;">Usuń odpowiedź</button>
        </div>
    `;

        // Dodanie nowego elementu do kontenera odpowiedzi
        answersContainer.appendChild(newAnswerDiv);

        // Obsługa kliknięcia na przycisk "Usuń odpowiedź"
        const removeButton = newAnswerDiv.querySelector('.remove-answer-btn');
        removeButton.addEventListener('click', function() {
            answersContainer.removeChild(newAnswerDiv);
        });
    });

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


</body>
</html>
