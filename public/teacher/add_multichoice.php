<?php
    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $userId = $_SESSION['user_id'];

    $subjectInfo = getTableInfo($conn, 'tPrzedmioty');
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
                <a class="nav-btn" href="questions.php">Pytania</a>
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
            <form action="../../includes/teacher/save_question.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="type" value="Wielokrotnego wyboru">

            <div class="title">
                <h1>Pytanie wielokrotnego wyboru</h1>
                <button type="button" id="add-answer-btn" style="background: #45a049; color: white; padding: 10px; border-radius: 10px;">Dodaj odpowiedź</button>
            </div>

            <h4>Treść pytania</h4>
            <input type="text" name="question_text" id="question_text" placeholder="Wpisz treść pytania">

            <div id="image-preview" style="margin-bottom: 20px;">
                <!-- Wybrane obrazki będą wyświetlane tutaj -->
            </div>

            <div id="answers-container">
                
            </div>

            <h4>Załącznik</h4>
            <input type="file" id="attachment-input" accept="image/*" name="attachment[]" multiple>

            <!-- Lista przedmiotów do przypisania -->
            <label>Przedmiot</label>
                <select name="subject" required>
                    <option disabled selected>Wybierz przedmiot</option>
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
            <input type="text" class="answer-input-field" name="answers[]" placeholder="Wpisz odpowiedź">
        </div>
        <div style="flex: 1;">
            <h4>Punkty</h4>
            <input type="number" class="points-field" name="points[]" value="0">
        </div>
        <div style="flex: 1;">
            <h4>Poprawna?</h4>
            <input type="checkbox" class="is-correct" name="correct[]" value="Option ${currentAnswerCount}">
        </div>
    `;

    // Dodanie nowego elementu do kontenera odpowiedzi
    answersContainer.appendChild(newAnswerDiv);
});

</script>


</body>
</html>
