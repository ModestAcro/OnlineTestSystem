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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/main.css">

    <title>Edytuj test</title>
</head>
<body>
   
    <?php include '../../includes/header.php'; ?>

    <main class="main my-5">
    <div class="container card shadow p-4">
        <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Edytuj test</h1>
        <form action="../../includes/teacher/save_test.php" method="POST">
            <input type="hidden" name="test_id" value="<?php echo $testInfo['ID']; ?>">
            <?php $ilosc_prob = $testInfo['ilosc_prob']; // Pobranie wartości z bazy ?>

            <h5 class="card-title fs-4 mt-2">Nazwa</h5>
            <div class="mb-3">
                <input type="text" name="nazwa" class="form-control" value="<?php echo $testInfo['nazwa']; ?>">
            </div>

            
            <!-- Wybór daty i godziny dla limitu czasowego -->
            <div class="mb-3">
                <h5 class="card-title fs-4 mt-2">Okres dostępności testu</h5>

                <!-- Przełącznik (switch) do włączania/wyłączania limitu czasowego -->
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="limitSwitch" 
                        <?php echo (!empty($testInfo['data_rozpoczecia'])) ? 'checked' : ''; ?>>
                    <label class="form-check-label" for="limitSwitch">Włącz/Wyłącz</label>
                </div>
        
                <!-- Sekcja z wyborem daty, domyślnie ukryta -->
                <div id="timeLimitSection" class="row g-2 mt-2 d-none">
                    <div class="col-md-6">
                        <label class="form-label">Od:</label>
                        <input type="datetime-local" class="form-control" name="start-time" 
                            value="<?php echo !empty($testInfo['data_rozpoczecia']) ? date('Y-m-d\TH:i', strtotime($testInfo['data_rozpoczecia'])) : ""; ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Do:</label>
                        <input type="datetime-local" class="form-control" name="end-time" 
                            value="<?php echo !empty($testInfo['data_zakonczenia']) ? date('Y-m-d\TH:i', strtotime($testInfo['data_zakonczenia'])) : ""; ?>">
                    </div>
                </div>
            </div>

            <!-- Skrypt do obsługi przełącznika -->
            <script>
                // Funkcja do ustawienia widoczności sekcji na podstawie stanu przełącznika
                function toggleTimeLimitSection() {
                    const timeLimitSection = document.getElementById('timeLimitSection');
                    const limitSwitch = document.getElementById('limitSwitch');
                    if (limitSwitch.checked) {
                        timeLimitSection.classList.remove('d-none');
                    } else {
                        timeLimitSection.classList.add('d-none');
                    }
                }

                // Wywołanie funkcji przy załadowaniu strony, aby ustawić początkowy stan
                window.onload = function() {
                    toggleTimeLimitSection();
                };

                // Obsługa zmiany stanu przełącznika
                document.getElementById('limitSwitch').addEventListener('change', toggleTimeLimitSection);
            </script>


            <!-- Czas trwania -->
            <h5 class="card-title fs-4 mt-2">Czas trwania (min.)</h5>
            <div class="mb-3">
                <input type="number" name="test-time" class="form-control" value="<?php echo $testInfo['czas_trwania']; ?>">
            </div>

            <!-- Ilość prób -->
            <div class="mb-3">
                <h5 class="card-title fs-4 mt-2">Ilość prób</h5>
                <div class="form-check">
                    <input type="radio" name="attempts" value="unlimited" id="attempts-unlimited" class="form-check-input"
                        <?php echo ($ilosc_prob == -1) ? 'checked' : ''; ?>>
                    <label for="attempts-unlimited" class="form-check-label">Nieograniczona liczba</label>
                </div>

                <div class="form-check">
                    <input type="radio" name="attempts" value="one" id="attempts-one" class="form-check-input"
                        <?php echo ($ilosc_prob == 1) ? 'checked' : ''; ?>>
                    <label for="attempts-one" class="form-check-label">Jedno podejście</label>
                </div>

                <div class="form-check">
                    <input type="radio" name="attempts" value="multiple" id="attempts-multiple" class="form-check-input"
                        <?php echo ($ilosc_prob > 1) ? 'checked' : ''; ?>>
                    <label for="attempts-multiple" class="form-check-label">Wiele podejść</label>
                </div>

                <!-- Pole do wpisania liczby prób (domyślnie ukryte) -->
                <div class="mt-2 <?php echo ($ilosc_prob > 1) ? '' : 'd-none'; ?>" id="attemptsNumberSection">
                    <input type="number" class="form-control" id="number-of-attempts" name="number-of-attempts" min="1" placeholder="Wpisz liczbę"
                        value="<?php echo ($ilosc_prob > 1) ? $ilosc_prob : ''; ?>">
                </div>
            </div>

            <!-- Skrypt do obsługi przełączania -->
            <script>
                document.querySelectorAll('input[name="attempts"]').forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        const attemptsNumberSection = document.getElementById('attemptsNumberSection');

                        if (this.value === 'multiple') {
                            attemptsNumberSection.classList.remove('d-none'); // Pokaż pole do liczby prób
                        } else {
                            attemptsNumberSection.classList.add('d-none'); // Ukryj pole do liczby prób
                        }
                    });
                });
            </script>



            <h5 class="card-title fs-4 mt-2">Pytania</h5>

            <!-- Lista pytań do przypisania -->
            <div class="mb-5">
                <select id="pytania" name="pytania[]" class="form-select" multiple>
                    <?php 
                    while ($questionsforSubject = mysqli_fetch_assoc($SubjectQuestions)): 
                        $czyZaznaczony = in_array($questionsforSubject['id_pytania'], $assignedQuestionsIds) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $questionsforSubject['id_pytania']; ?>" <?php echo $czyZaznaczony; ?>>
                            <?php echo $questionsforSubject['tresc_pytania']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <!-- Lista pytań do przypisania -->

            <!-- Wyświetlanie pytań -->
            <?php $index = 1; ?>
            <?php foreach ($questions as $pytanie_id => $pytanie): ?>
                <div class="mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex justify-content-between">
                                    <label class="d-block"><?php echo $index; ?>/<?php echo count($questions); ?></label>
                                    <label class="d-block ms-3 text-end"><?php echo ucfirst($pytanie['typ']); ?></label>
                                </div>
                                <div>
                                    <label class="d-block">Punkty: <?php echo isset($pytanie['suma_punktow']) ? $pytanie['suma_punktow'] : 0; ?></label>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="d-block"><?php echo htmlspecialchars($pytanie['tresc']); ?></label>
                                <div class="mt-2">
                                    <?php foreach ($pytanie['odpowiedzi'] as $odpowiedz): ?>
                                        <?php if ($odpowiedz['czy_poprawna'] == 1): ?>
                                            <label>Odpowiedź: <?php echo htmlspecialchars($odpowiedz['tresc']); ?> 
                                                (<?php echo "✔"; ?>)</label><br>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $index++; ?>
            <?php endforeach; ?>
            <!-- Wyświetlanie pytań -->

            <div class="mt-4">
                <button type="submit" name="action" value="update" class="btn btn-outline-danger">Zapisz Zmiany</button>
                <a href="#" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Usuń</a>
            </div>
        </form>

       
        <!-- Modal potwierdzenia usunięcia testu -->
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="card-title fs-4 mt-2" id="logoutModalLabel">Potwierdzenie usunięcia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Czy na pewno chcesz usunąć ten test?
                </div>
                <div class="modal-footer">
                    <form action="../../includes/teacher/save_test.php" method="POST">
                        <input type="hidden" name="test_id" value="<?php echo $testInfo['ID']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="btn btn-outline-danger">Usuń</button>
                    </form>
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Anuluj</button>
                </div>
                </div>
            </div>
        </div>
    </div>
    </main>

    <!-- Pliki JavaScript --> 
    <script src="../../assets/js/multi_select.js"></script>


    <!-- multi_select.js --> 
    <script>
        new MultiSelectTag('pytania')  // id
    </script>
    <!-- multi_select.js -->   

</body>
</html>
