<?php
    require_once('../../config/connect.php');
    $GroupId = $_GET['id'];
    $query = "SELECT * FROM tGrupyStudentow WHERE ID = $GroupId";
    $result = mysqli_query($conn, $query);
    $group = mysqli_fetch_assoc($result);
    if (!$group) {
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
    <title>Edytuj grupę</title>
</head>
<body>
    <main class="main">
        <div class="container">
            <h1>Edytuj grupę</h1>
            <form action="../../includes/teacher/update_group.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $group['ID']; ?>">

                <label for="rok">Rok</label>
                <input type="text" id="rok" name="rok" value="<?php echo htmlspecialchars($group['rok']); ?>">

                <label for="miasto">Miasto</label>
                <input type="text" id="miasto" name="miasto" value="<?php echo htmlspecialchars($group['miasto']); ?>">

                <label for="przedmiot">Przedmiot</label>
                <input type="text" id="przedmiot" name="przedmiot" value="<?php echo htmlspecialchars($group['przedmiot']); ?>">

                <label for="nazwa">Nazwa</label>
                <input type="text" id="nazwa" name="nazwa" value="<?php echo htmlspecialchars($group['nazwa']); ?>">

    

                <button type="submit" name="action" value="update" class="submit-btn">Zapisz Zmiany</button>
                <button type="submit" name="action" value="delete" class="submit-btn" id="delete-btn">Usuń</button>
            </form>

            <!-- Okno modalne do potwierdzenia usunięcia Wykładowcy-->
            <div id="deleteCharacterModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" id="deleteCharacterModalClose">&times;</span>
                    <h2>Czy na pewno chcesz usunąć tę grupę?</h2>
                    <form action="../../includes/teacher/update_group.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $group['ID']; ?>">
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
