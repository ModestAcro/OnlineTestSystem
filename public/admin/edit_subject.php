<?php
    require_once('../../config/connect.php');
    $subjectId = $_GET['id'];
    $query = "SELECT * FROM tPrzedmioty WHERE ID = $subjectId";
    $result = mysqli_query($conn, $query);
    $subject = mysqli_fetch_assoc($result);
    if (!$subject) {
        echo "Nie znaleziono wykładowcy.";
        exit;
    }
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
                <input type="hidden" name="id" value="<?php echo $subject['ID']; ?>">

                <label for="nazwa">Nazwa</label>
                <input type="text" id="nazwa" name="nazwa" value="<?php echo htmlspecialchars($subject['nazwa']); ?>">

                <label for="uwagi">Uwagi</label>
                <input type="text" id="uwagi" name="uwagi" value="<?php echo htmlspecialchars($subject['uwagi']); ?>">

                <button type="submit" name="action" value="update" class="submit-btn">Zapisz Zmiany</button>
                <button type="submit" name="action" value="delete" class="submit-btn" id="delete-btn">Usuń</button>
            </form>

            <!-- Okno modalne do potwierdzenia usunięcia Wykładowcy-->
            <div id="deleteCharacterModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" id="deleteCharacterModalClose">&times;</span>
                    <h2>Czy na pewno chcesz usunąć ten przedmiot?</h2>
                    <form action="../../includes/admin/update_subject.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $subject['ID']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="submit-btn" id="delete-btn">Tak, usuń</button>
                    </form>
                </div>
            </div>
            <!-- Okno modalne do potwierdzenia usunięcia -->

        </div>
    </main>

    <!-- Plik JavaScript --> 
    <script src="../../assets/js/admin/modalWindows.js"></script>  
</body>
</html>
