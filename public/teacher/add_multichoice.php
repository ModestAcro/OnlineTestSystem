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
            <form action="../../includes/teacher/add_question.php" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="type" value="Wielokrotnego wyboru">

            <div class="title">
                <h1>Pytanie wielokrotnego wyboru</h1>
                <button type="button" id="add-answer-btn" class="add-btn" style="width: 150px; color: white;">Dodaj odpowiedź</button>
            </div>

            <h4>Treść pytania</h4>
            <input type="text" name="question_text" id="question_text" placeholder="Wpisz treść pytania">

            <h4>Załącznik</h4>
            <input type="file" id="attachment-input" accept="image/*" name="attachment[]" multiple>

            <div id="image-preview" style="margin-bottom: 20px;">
                <!-- Wybrane obrazki będą wyświetlane tutaj -->
            </div>

            <div id="answers-container">
                
            </div>

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
       document.getElementById('add-answer-btn').addEventListener('click', function() {
        const answerContainer = document.createElement('div');
        answerContainer.classList.add('answer-container');
        answerContainer.style.cssText = "padding: 5px; background-color: #f4f4f4; border-radius: 10px; margin-bottom: 20px;";

        const answerOptions = `
            <div class="answer-options" style="display: flex; justify-content: space-between; gap: 10px;">
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
                    <input type="checkbox" class="is-correct" name="correct[]" value="0">
                </div>
            </div>`;

        answerContainer.innerHTML = answerOptions;

        const removeAnswerBtn = document.createElement('button');
        removeAnswerBtn.type = 'button';
        removeAnswerBtn.classList.add('remove-answer-btn');
        removeAnswerBtn.innerText = 'Usuń odpowiedź';
        removeAnswerBtn.style.cssText = "background-color: red; padding: 5px; border-radius: 10px;";
        answerContainer.appendChild(removeAnswerBtn);

        removeAnswerBtn.addEventListener('click', () => answerContainer.remove());

        document.getElementById('answers-container').appendChild(answerContainer);
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
