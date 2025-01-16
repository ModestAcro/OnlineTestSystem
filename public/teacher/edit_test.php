<?php
    session_start();

    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $userId = $_SESSION['user_id'];

    // Pobranie ID testu z parametrów GET
    $testId = $_GET['test_id'];

    // Funkcja zwraca pojedynczy rekord na podstawie ID
    $test = getRecordById($conn, 'tTesty', $testId);


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
                <input type="hidden" name="test_id" value="<?php echo $test['ID']; ?>">

                <label>Nazwa</label>
                <input type="text" name="nazwaTestu" value="<?php echo $test['nazwa']; ?>">

                <label>Data rozpoczęcia</label>
                <input type="date" name="dataRozpoczeciaTestu" value="<?php echo $test['data_rozpoczecia']; ?>">

                <label>Data zakończenia</label>
                <input type="date" name="dataZakonczeniaTestu" value="<?php echo $test['data_zakonczenia']; ?>">

                <label>Czas trwania (min.)</label>
                <input type="number" name="czasTrwaniaTestu" value="<?php echo $test['czas_trwania']; ?>">

                <label>Ilość prób</label>
                <input type="text" name="iloscProbTestu" value="<?php echo $test['ilosc_prob']; ?>">


                <button type="submit" name="action" value="update" class="submit-btn">Zapisz Zmiany</button>
                <button type="submit" name="action" value="delete" class="submit-btn" id="delete-btn">Usuń</button>
            </form>

            <!-- Okno modalne do potwierdzenia usunięcia Testu-->
            <div id="deleteCharacterModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" id="deleteCharacterModalClose">&times;</span>
                    <h2>Czy na pewno chcesz usunąć ten test?</h2>
                    <form action="../../includes/teacher/update_test.php" method="POST">
                        <input type="hidden" name="group_id" value="<?php echo $test['ID']; ?>">
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
