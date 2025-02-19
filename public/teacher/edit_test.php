<?php
    session_start();

    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $userId = $_SESSION['user_id'];

    // Pobranie ID testu z parametrów GET
    $testId = $_GET['test_id'];

    // Funkcja zwraca pojedynczy rekord na podstawie ID
    $testInfo = getRecordById($conn, 'tTesty', $testId);


    function getTestQuestions($conn, $id_testu){
        $query = "SELECT t.ID AS test_id, 
                     tp.id_testu, 
                     tp.id_pytania, 
                     p.ID AS pytanie_id, 
                     p.tresc AS tresc_pytania,
                     p.typ AS typ_pytania,
                     o.id_pytania,
                     o.tresc AS tresc_odpowiedzi,
                     o.correct AS czy_poprawna,
                     o.punkty AS punkty_odpowiedzi
              FROM tTesty t
              JOIN tTestPytania tp ON tp.id_testu = t.ID  
              JOIN tPytania p ON tp.id_pytania = p.ID  
              JOIN tOdpowiedzi o ON o.id_pytania = p.ID
              WHERE t.ID = $id_testu";; 

        return mysqli_query($conn, $query);
    }


    $test_id = $_GET['test_id'];
 

    $testQuestions = getTestQuestions($conn, $test_id);


    

    $questions = [];
    while ($row = mysqli_fetch_assoc($testQuestions)) {
        $questions[$row['pytanie_id']]['tresc'] = $row['tresc_pytania'];
        $questions[$row['pytanie_id']]['typ'] = $row['typ_pytania'];
        $questions[$row['pytanie_id']]['odpowiedzi'][] = [
            'tresc' => $row['tresc_odpowiedzi'],
            'czy_poprawna' => $row['czy_poprawna'],
            'punkty' => $row['punkty_odpowiedzi']
        ];

        // Sumowanie punktów za poprawne odpowiedzi
        if ($row['czy_poprawna'] == 1) {  // Jeśli odpowiedź jest poprawna
            $questions[$row['pytanie_id']]['suma_punktow'] = isset($questions[$row['pytanie_id']]['suma_punktow']) 
                ? $questions[$row['pytanie_id']]['suma_punktow'] + $row['punkty_odpowiedzi']
                : $row['punkty_odpowiedzi']; 
        }
    }

    function getQuestionsList($conn, $testId){
        $query ="SELECT p.ID as id_pytania, p.tresc as tresc_pytania, p.id_przedmiotu, t.id_przedmiotu, t.ID
                FROM tPytania p
                JOIN tTesty t ON t.id_przedmiotu = p.id_przedmiotu
                WHERE t.ID = $testId;
                ";

    return mysqli_query($conn, $query);

    }

    $SubjectQuestions = getQuestionsList($conn, $testId);


    function getAssignedTestQuestions($conn, $test_id){
        $query = "SELECT p.ID as id_pytania, p.tresc as tresc_pytania, t.ID AS test_id, tp.id_testu, tp.id_pytania
                    FROM tPytania p
                    JOIN tTestPytania tp ON tp.id_pytania = p.ID
                    JOIN tTesty t ON t.ID = tp.id_testu
                    WHERE t.ID = $test_id;
                ";

    return mysqli_query($conn, $query);

    }

    $assignedQuestions = getAssignedTestQuestions($conn, $test_id);

    // Sprawdzenie, czy wyniki zostały pobrane
    if ($assignedQuestions) {
        // Pobranie przypisanych pytań
        $assignedQuestionsIds = [];
        while ($question = mysqli_fetch_assoc($assignedQuestions)) {
            $assignedQuestionsIds[] = $question['id_pytania'];
        }
    } else {
        $assignedQuestionsIds = [];
    }

    $SubjectQuestions = getQuestionsList($conn, $test_id);

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

    <title>Edytuj test</title>
</head>
<body>
   
    <?php include '../../includes/header.php'; ?>

    <main class="main">
        <div class="container">
            <h1>Edytuj test</h1>
            <form action="../../includes/teacher/save_test.php" method="POST">
                <input type="hidden" name="test_id" value="<?php echo $testInfo['ID']; ?>">
                <?php $ilosc_prob = $testInfo['ilosc_prob']; // Pobranie wartości z bazy ?>

                <h4>Nazwa</h4>
                <div class="radio">
                    <div class="radio-section">
                        <input type="text" name="nazwa" value="<?php echo $testInfo['nazwa']; ?>">
                    </div>
                </div>


                <h4>Okres dostępności testu</h4>
                <div class="radio" style="margin-bottom: 15px;">

                    <div class="radio-section">
                        <div class="radio-input">
                            <input type="radio" name="limit" value="date"  
                                <?php echo (!empty($testInfo['data_rozpoczecia']) && !empty($testInfo['data_zakonczenia'])) ? 'checked' : ''; ?>>
                            <label>Limit czasowy</label>
                        </div>

                        <!-- Wybór daty i godziny dla limitu czasowego -->
                        <div class="time-limited-options">

                        <div class="time-limit-input">
                            <label>Od:</label>
                            <input type="datetime-local" id="start-time" name="start-time"
                                value="<?php echo !empty($testInfo['data_rozpoczecia']) ? date('Y-m-d\TH:i', strtotime($testInfo['data_rozpoczecia'])) : ""; ?>">
                        </div>

                        <div class="time-limit-input">
                            <label>Do:</label>
                            <input type="datetime-local" id="end-time" name="end-time"
                                value="<?php echo !empty($testInfo['data_zakonczenia']) ? date('Y-m-d\TH:i', strtotime($testInfo['data_zakonczenia'])) : ""; ?>">
                        </div>


                        </div>
                        <!-- Wybór daty i godziny dla limitu czasowego -->

                    </div>
                    <div class="radio-section">
                        <div class="radio-input">
                            <input type="radio" name="limit" value="unlimited"
                                <?php echo (!empty($testInfo['data_rozpoczecia']) && !empty($testInfo['data_zakonczenia'])) ? ' ' : 'checked'; ?>    >
                            <label>Bez limitu</label>
                        </div>
                    </div>

                </div>

                <!-- Czas trawania -->
                <h4>Czas trwania (min.)</h4>
                <div class="radio">
                    <div class="radio-section">
                    <input type="number" name="test-time" value="<?php echo $testInfo['czas_trwania']; ?>">
                    </div>
                </div>

              <!--
                <div style="display: flex; align-items: center;">
                    <h4 style="margin-right: 10px;">Ilość prób</h4> 
                    <img src="../../assets/images/icons/attention.svg" alt="attention" style="width: 20px" title="Wpisz -1, jeśli chcesz, aby liczba prób była nieograniczona">
                </div>

-->

                <!-- Ilość prób -->
                <h4>Ilość prób</h4>
                <div class="radio">
                    <div class="radio-section">
                        <div class="radio-input">
                            <input type="radio" name="attempts" value="unlimited" id="attempts-unlimited"
                                <?php echo ($ilosc_prob == -1) ? 'checked' : ''; ?>>
                            <label for="attempts-unlimited">Nieograniczona liczba</label>
                        </div>
                    </div>

                    <div class="radio-section">
                        <div class="radio-input">
                            <input type="radio" name="attempts" value="one" id="attempts-one"
                                <?php echo ($ilosc_prob == 1) ? 'checked' : ''; ?>>
                            <label for="attempts-one">Jedno podejście</label>
                        </div>
                    </div>

                    <div class="radio-section">
                        <div class="radio-input">
                            <input type="radio" name="attempts" value="multiple" id="attempts-multiple"
                                <?php echo ($ilosc_prob > 1) ? 'checked' : ''; ?>>
                            <label for="attempts-multiple">Wiele podejść</label>
                        </div>

                        <div class="time-limited-options">
                            <div class="time-limit-input">
                                <label for="number-of-attempts">Liczba</label>
                                <input type="number" id="number-of-attempts" name="number-of-attempts" value="<?php echo ($testInfo['ilosc_prob'] > 1) ? $testInfo['ilosc_prob'] : ''; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!--<input type="number" name="attempts" value="<?php echo $testInfo['ilosc_prob']; ?>" >-->


                <h1 style="margin-top: 20px;">Pytania</h1>

                <!-- Lista pytań do przypisania -->
                <label>Wybierz pytania</label>
                <select id="pytania" name="pytania[]" multiple>
                    <?php 
                    // Przechodzimy przez listę pytań
                    while ($questionsforSubject = mysqli_fetch_assoc($SubjectQuestions)): 
                        // Sprawdzenie, czy pytanie jest już przypisane do testu
                        $czyZaznaczony = in_array($questionsforSubject['id_pytania'], $assignedQuestionsIds) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $questionsforSubject['id_pytania']; ?>" <?php echo $czyZaznaczony; ?>>
                            <?php echo $questionsforSubject['tresc_pytania']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <!-- Lista pytań do przypisania -->



                

                <!-- Question card -->
                <!-- Wyświetlanie pytań -->
                <?php $index = 1; ?>
                <?php foreach ($questions as $pytanie_id => $pytanie): ?>
                    <div class="radio" style="margin-top: 20px;">
                        <div class="question-card">
                            <div class="question-left">

                                <div class="question-top">
                                    <div class="number">
                                        <label><?php echo $index; ?>/<?php echo count($questions); ?></label>
                                        <label><?php echo ucfirst($pytanie['typ']); ?></label>
                                    </div>
                                    <div class="points">
                                        <label>Punkty: <?php echo isset($pytanie['suma_punktow']) ? $pytanie['suma_punktow'] : 0; ?></label>
                                    </div>
                                </div>

                                <div class="question-top">
                                    <div class="question">
                                        <label><?php echo htmlspecialchars($pytanie['tresc']); ?></label>
                                    </div>
                                    <div class="answer">
                                        <?php foreach ($pytanie['odpowiedzi'] as $odpowiedz): ?>
                                            <?php if ($odpowiedz['czy_poprawna'] == 1): ?>
                                                <label>Odpowiedź: <?php echo htmlspecialchars($odpowiedz['tresc']); ?> 
                                                    (<?php echo "✔"; ?>)</label>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                            </div>

                            <!-- 
                            <div class="question-right">
                                <div class="question-action" id="delete-question-btn">
                                    <button>Usuń</button>
                                </div>
                            </div>
                                            -->
                        </div>
                    </div>
                    <?php $index++; ?>
                <?php endforeach; ?>
                <!-- Question card -->


                <button type="submit" name="action" value="update" class="submit-btn">Zapisz Zmiany</button>
                <button type="submit" name="action" value="delete" class="submit-btn" id="delete-btn">Usuń</button>

            </form>

            <!-- Okno modalne do potwierdzenia usunięcia Testu-->
            <div id="deleteCharacterModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" id="deleteCharacterModalClose">&times;</span>
                    <h2>Czy na pewno chcesz usunąć ten test?</h2>
                    <form action="../../includes/teacher/save_test.php" method="POST">
                        <input type="hidden" name="test_id" value="<?php echo $testInfo['ID']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="submit-btn" id="delete-btn">Tak, usuń</button>
                    </form>
                </div>
            </div>
            <!-- Okno modalne do potwierdzenia usunięcia Testu -->

        </div>
    </main>

    <!-- Pliki JavaScript --> 

    <script src="../../assets/js/modal_windows.js"></script>
    <script src="../../assets/js/multi_select.js"></script>


    <!-- multi_select.js --> 
    <script>
        new MultiSelectTag('pytania')  // id
    </script>
    <!-- multi_select.js -->   

</body>
</html>
