<?php

    session_start();

    require_once('../../config/connect.php');
    require_once('../../config/functions.php');
    
    $universityId = $_GET['university_id'];
    $university = getRecordById($conn, 'tUczelnie', $universityId);

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
                <input type="hidden" name="idUczelni" value="<?php echo $university['ID']; ?>">

                <label>Nazwa</label>
                <input type="text" name="nazwaUczelni" value="<?php echo $university['nazwa']; ?>" required>

                <label>Miasto</label>
                <input type="text" name="miastoUczelni" value="<?php echo $university['miasto']; ?>" required>

                <label>Kraj</label>
                <input type="text" name="krajUczelni" value="<?php echo $university['kraj']; ?>" required>

                <label>Kontynent</label>
                <input type="text" name="kontynentUczelni" value="<?php echo $university['kontynent']; ?>" required>

                <label>Adres</label>
                <input type="text" name="adresUczelni" value="<?php echo $university['adres']; ?>" required>

                <label>Uwagi</label>
                <textarea name="uwagiUczelni"><?php echo $university['uwagi']; ?></textarea>

                <button type="submit" name="action" value="update" class="submit-btn">Zapisz Zmiany</button>
                <button type="submit" name="action" value="delete" class="submit-btn" id="delete-btn">Usuń</button>
            </form>

            <!-- Okno modalne do potwierdzenia usunięcia Studenta-->
            <div id="deleteCharacterModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" id="deleteCharacterModalClose">&times;</span>
                    <h2>Czy na pewno chcesz usunąć tę uczelnię?</h2>
                    <form action="../../includes/admin/update_universities.php" method="POST">
                        <input type="hidden" name="idUczelni" value="<?php echo $university['ID']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="submit-btn" id="delete-btn">Tak, usuń</button>
                    </form>
                </div>
            </div>
            <!-- Okno modalne do potwierdzenia usunięcia -->

        </div>
    </main>

    <!-- Plik JavaScript --> 
    <script src="../../assets/js/modal_windows.js"></script>  
</body>
</html>
