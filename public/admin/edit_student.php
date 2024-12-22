<?php

    session_start();    

    require_once('../../config/connect.php');
    require_once('../../config/functions.php');


    $studentId = $_GET['student_id'];
    $student = getRecordById($conn, 'tStudenci', $studentId);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Edytuj Studenta</title>
</head>
<body>
    <main class="main">
        <div class="container">
            <h1>Edytuj Studenta</h1>
            <form action="../../includes/admin/update_student.php" method="POST">
                <input type="hidden" name="idStudenta" value="<?php echo $student['ID']; ?>">

                <label for="nrAlbumu">Nr. albumu</label>
                <input type="text" name="nrAlbumuStudenta" value="<?php echo $student['nr_albumu']; ?>" required>

                <label for="imie">Imię</label>
                <input type="text" name="imieStudenta" value="<?php echo $student['imie']; ?>" required>

                <label for="nazwisko">Nazwisko</label>
                <input type="text" name="nazwiskoStudenta" value="<?php echo $student['nazwisko']; ?>" required>

                <label for="email">Email</label>
                <input type="email" name="emailStudenta" value="<?php echo $student['email']; ?>" required>

                <label for="haslo">Hasło</label>
                <input type="password" name="hasloStudenta" value="<?php echo $student['haslo']; ?>" required>

                <label for="uwagi">Uwagi</label>
                <textarea name="uwagiStudenta"><?php echo $student['uwagi']; ?></textarea>

                <button type="submit" name="action" value="update" class="submit-btn">Zapisz Zmiany</button>
                <button type="submit" name="action" value="delete" class="submit-btn" id="delete-btn">Usuń</button>
            </form>

            <!-- Okno modalne do potwierdzenia usunięcia Studenta-->
            <div id="deleteCharacterModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" id="deleteCharacterModalClose">&times;</span>
                    <h2>Czy na pewno chcesz usunąć tego studenta?</h2>
                    <form action="../../includes/admin/update_student.php" method="POST">
                        <input type="hidden" name="idStudenta" value="<?php echo $student['ID']; ?>">
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
