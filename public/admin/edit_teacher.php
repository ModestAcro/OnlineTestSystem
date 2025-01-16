<?php
    session_start();

    require_once('../../config/connect.php');
    require_once('../../config/functions.php');
    
    $teacherId = $_GET['teacher_id'];
    $teacher = getRecordById($conn, 'tWykladowcy', $teacherId);
    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Edytuj Wykładowcę</title>
</head>
<body>
    <main class="main">
        <div class="container">
            <h1>Edytuj Wykładowcę</h1>
            <form action="../../includes/admin/update_teacher.php" method="POST">
                <input type="hidden" name="idWykladowcy" value="<?php echo $teacher['ID']; ?>">

                <label>Imię</label>
                <input type="text" name="imieWykladowcy" value="<?php echo $teacher['imie']; ?>" required>

                <label>Nazwisko</label>
                <input type="text" name="nazwiskoWykladowcy" value="<?php echo $teacher['nazwisko']; ?>" required>

                <label>Email</label>
                <input type="email" name="emailWykladowcy" value="<?php echo $teacher['email']; ?>" required>

                <label>Hasło</label>
                <input type="password" name="hasloWykladowcy" value="<?php echo $teacher['haslo']; ?>" required>

                <label>Uwagi</label>
                <textarea name="uwagiWykladowcy"><?php echo $teacher['uwagi']; ?></textarea>

                <button type="submit" name="action" value="update" class="submit-btn">Zapisz Zmiany</button>
                <button type="submit" name="action" value="delete" class="submit-btn" id="delete-btn">Usuń</button>
            </form>

            <!-- Okno modalne do potwierdzenia usunięcia wykładowcy -->
            <div id="deleteCharacterModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" id="deleteCharacterModalClose">&times;</span>
                    <h2>Czy na pewno chcesz usunąć tego wykładowcę?</h2>
                    <form action="../../includes/admin/update_teacher.php" method="POST">
                        <input type="hidden" name="idWykladowcy" value="<?php echo $teacher['ID']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="submit-btn" id="delete-btn">Tak, usuń</button>
                    </form>
                </div>
            </div>
            <!-- Okno modalne do potwierdzenia usunięcia wykładowcy -->

        </div>
    </main>

    <!-- Plik JavaScript --> 
    <script src="../../assets/js/modal_windows.js"></script>  
</body>
</html>
