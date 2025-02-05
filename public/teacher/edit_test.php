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



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Edytuj test</title>
</head>
<body>
    <main class="main">
        <div class="container">
            <h1>Edytuj test</h1>
            <form action="../../includes/teacher/update_test.php" method="POST">
                <input type="hidden" name="test_id" value="<?php echo $ttestInfoest['ID']; ?>">

                <label>Nazwa</label>
                <input type="text" name="nazwaTestu" value="<?php echo $testInfo['nazwa']; ?>">

                <label>Data rozpoczęcia</label>
                <input type="datetime-local" name="dataRozpoczeciaTestu" 
                    value="<?php echo !empty($testInfo['data_rozpoczecia']) ? date('Y-m-d\TH:i', strtotime($testInfo['data_rozpoczecia'])) : ""; ?>">

                <label>Data zakończenia</label>
                <input type="datetime-local" name="dataZakonczeniaTestu" 
                    value="<?php echo !empty($testInfo['data_zakonczenia']) ? date('Y-m-d\TH:i', strtotime($testInfo['data_zakonczenia'])) : ""; ?>">


                <label>Czas trwania (min.)</label>
                <input type="number" name="czasTrwaniaTestu" value="<?php echo $testInfo['czas_trwania']; ?>">

                <label>Ilość prób</label>
                <input type="text" name="iloscProbTestu" value="<?php echo $testInfo['ilosc_prob']; ?>">

                <h1>Pytania</h1>

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

                            <div class="question-right">
                                <div class="question-action" id="delete-question-btn">
                                    <button>Usuń</button>
                                </div>
                            </div>
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
                    <form action="../../includes/teacher/update_test.php" method="POST">
                        <input type="hidden" name="group_id" value="<?php echo $testInfo['ID']; ?>">
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
</body>
</html>
