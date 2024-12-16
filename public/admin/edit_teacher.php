<?php
    require_once('../../config/connect.php');
    $teacherId = $_GET['id'];
    $query = "SELECT * FROM tWykladowcy WHERE ID = $teacherId";
    $result = mysqli_query($conn, $query);
    $teacher = mysqli_fetch_assoc($result);
    if (!$teacher) {
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
    <title>Edytuj Wykładowcę</title>
</head>
<body>
    <main class="edit-teacher">
        <div class="container">
            <h1>Edytuj Wykładowcę</h1>
            <form action="../../includes/admin/update_teacher.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $teacher['ID']; ?>">

                <label for="imie">Imię</label>
                <input type="text" id="imie" name="imieWykladowcy" value="<?php echo htmlspecialchars($teacher['imie']); ?>" required>

                <label for="nazwisko">Nazwisko</label>
                <input type="text" id="nazwisko" name="nazwiskoWykladowcy" value="<?php echo htmlspecialchars($teacher['nazwisko']); ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="emailWykladowcy" value="<?php echo htmlspecialchars($teacher['email']); ?>" required>

                <label for="haslo">Hasło</label>
                <input type="password" id="haslo" name="hasloWykladowcy" value="<?php echo htmlspecialchars($teacher['haslo']); ?>" required>

                <label for="uwagi">Uwagi</label>
                <textarea id="uwagi" name="uwagiWykladowcy"><?php echo htmlspecialchars($teacher['uwagi']); ?></textarea>

                <button type="submit" name="action" value="update" class="submit-btn">Zapisz Zmiany</button>
                <button type="submit" name="action" value="delete" class="submit-btn" id="delete-btn">Usuń</button>
            </form>

            <!-- Okno modalne do potwierdzenia usunięcia Wykładowcy-->
            <div id="deleteTeacherModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" id="closeModal">&times;</span>
                    <h2>Czy na pewno chcesz usunąć tego wykładowcę?</h2>
                    <form action="../../includes/admin/update_teacher.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $teacher['ID']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="submit-btn" id="delete-btn">Tak, usuń</button>
                    </form>
                </div>
            </div>
            <!-- Okno modalne do potwierdzenia usunięcia -->

        </div>
    </main>

    <!-- Plik JavaScript --> 
    <script src="../../assets/js/admin/addTeacherModal.js"></script>  
</body>
</html>
