<?php

    session_start();

    require_once('../../config/connect.php');
    require_once('../../config/functions.php');


    $subjectId = $_GET['subject_id'];
    $subject = getRecordById($conn, 'tPrzedmioty', $subjectId);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Edytuj przedmiot</title>
</head>
<body>
    <main class="main">
        <div class="container">
            <h1>Edytuj przedmiot</h1>
            <form action="../../includes/admin/update_subject.php" method="POST">
                <input type="hidden" name="idPrzedmiotu" value="<?php echo $subject['ID']; ?>">

                <label>Nazwa</label>
                <input type="text" name="nazwaPrzedmiotu" value="<?php echo $subject['nazwa']; ?>">

                <label>Uwagi</label>
                <input type="text" name="uwagiPrzedmiotu" value="<?php echo $subject['uwagi']; ?>">

                <button type="submit" name="action" value="update" class="submit-btn">Zapisz Zmiany</button>
                <button type="submit" name="action" value="delete" class="submit-btn" id="delete-btn">Usuń</button>
            </form>

            <!-- Okno modalne do potwierdzenia usunięcia Wykładowcy-->
            <div id="deleteCharacterModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" id="deleteCharacterModalClose">&times;</span>
                    <h2>Czy na pewno chcesz usunąć ten przedmiot?</h2>
                    <form action="../../includes/admin/update_subject.php" method="POST">
                        <input type="hidden" name="idPrzedmiotu" value="<?php echo $subject['ID']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="submit-btn" id="delete-btn">Tak, usuń</button>
                    </form>
                </div>
            </div>
            <!-- Okno modalne do potwierdzenia usunięcia -->

        </div>
    </main>

    <!-- Plik JavaScript --> 
    <script src="../../assets/js/modalWindows.js"></script>  
</body>
</html>
