<?php
    require_once('../../config/connect.php');
    $studentId = $_GET['id'];
    $query = "SELECT * FROM tStudenci WHERE ID = $studentId";
    $result = mysqli_query($conn, $query);
    $student = mysqli_fetch_assoc($result);
    if (!$student) {
        echo "Nie znaleziono studenta.";
        exit;
    }
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
                <input type="hidden" name="id" value="<?php echo $student['ID']; ?>">

                <label for="nrAlbumu">Nr. albumu</label>
                <input type="text" id="nrAlbumu" name="nrAlbumuStudenta" value="<?php echo htmlspecialchars($student['nr_albumu']); ?>" required>

                <label for="imie">Imię</label>
                <input type="text" id="imie" name="imieStudenta" value="<?php echo htmlspecialchars($student['imie']); ?>" required>

                <label for="nazwisko">Nazwisko</label>
                <input type="text" id="nazwisko" name="nazwiskoStudenta" value="<?php echo htmlspecialchars($student['nazwisko']); ?>" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="emailStudenta" value="<?php echo htmlspecialchars($student['email']); ?>" required>

                <label for="haslo">Hasło</label>
                <input type="password" id="haslo" name="hasloStudenta" value="<?php echo htmlspecialchars($student['haslo']); ?>" required>

                <label for="uwagi">Uwagi</label>
                <textarea id="uwagi" name="uwagiStudenta"><?php echo htmlspecialchars($student['uwagi']); ?></textarea>

                <button type="submit" name="action" value="update" class="submit-btn">Zapisz Zmiany</button>
                <button type="submit" name="action" value="delete" class="submit-btn" id="delete-btn">Usuń</button>
            </form>

            <!-- Okno modalne do potwierdzenia usunięcia Studenta-->
            <div id="deleteTeacherModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" id="closeModal">&times;</span>
                    <h2>Czy na pewno chcesz usunąć tego studenta?</h2>
                    <form action="../../includes/admin/update_student.php" method="POST">
                        <input type="hidden" name="id" value="<?php echo $student['ID']; ?>">
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
