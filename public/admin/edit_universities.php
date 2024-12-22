<?php
    require_once('../../config/connect.php');

    $universityId = $_GET['id'];
    $query = "SELECT * FROM tUczelnie WHERE ID = $universityId";
    $result = mysqli_query($conn, $query);
    $university = mysqli_fetch_assoc($result);
    if (!$university) {
        echo "Nie znaleziono uczelni.";
        exit;
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Edytuj uczelnię</title>
</head>
<body>
    <main class="main">
        <div class="container">
            <h1>Edytuj uczelnię</h1>
            <form action="../../includes/admin/update_universities.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $university['ID']; ?>">

                <label for="nazwa">Nazwa</label>
                <input type="text" id="nazwa" name="nazwa_uczelni" value="<?php echo htmlspecialchars($university['nazwa_uczelni']); ?>" required>

                <label for="miasto">Miasto</label>
                <input type="text" id="miasto" name="miasto" value="<?php echo htmlspecialchars($university['miasto']); ?>" required>

                <label for="kraj">Kraj</label>
                <input type="text" id="kraj" name="kraj" value="<?php echo htmlspecialchars($university['kraj']); ?>" required>

                <label for="kontynent">Kontynent</label>
                <input type="text" id="kontynent" name="kontynent" value="<?php echo htmlspecialchars($university['kontynent']); ?>" required>

                <label for="adres">Adres</label>
                <input type="text" id="adres" name="adres_uczelni" value="<?php echo htmlspecialchars($university['adres_uczelni']); ?>" required>

                <label for="uwagi">Uwagi</label>
                <textarea id="uwagi" name="uwagi"><?php echo htmlspecialchars($university['uwagi']); ?></textarea>

                <button type="submit" name="action" value="update" class="submit-btn">Zapisz Zmiany</button>
                <button type="submit" name="action" value="delete" class="submit-btn" id="delete-btn">Usuń</button>
            </form>

            <!-- Okno modalne do potwierdzenia usunięcia Studenta-->
            <div id="deleteCharacterModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" id="deleteCharacterModalClose">&times;</span>
                    <h2>Czy na pewno chcesz usunąć tę uczelnię?</h2>
                    <form action="../../includes/admin/update_universities.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $university['ID']; ?>">
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
