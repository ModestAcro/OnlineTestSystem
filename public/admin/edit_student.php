<?php

    session_start();    

    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $studentId = $_GET['student_id'];
    $student = getRecordById($conn, 'tStudenci', $studentId);

    $courseInfo = getTableInfo($conn, 'tKierunki');

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <!-- Bootstrap 5.3 css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../assets/css/main.css">

    <title>Edytuj Studenta</title>
</head>
<body>

    <?php include '../../includes/header.php'; ?>
    
    <main class="main">
        <div class="container">
            <h1>Edytuj Studenta</h1>
            <form action="../../includes/admin/update_student.php" method="POST">
                <input type="hidden" name="idStudenta" value="<?php echo $student['ID']; ?>">

                <label for="nrAlbumu">Nr. albumu</label>
                <input type="text" name="nrAlbumuStudenta" value="<?php echo $student['nr_albumu']; ?>" required>

                <!-- Lista kierunków do przypisania -->
                <label>Kierunek</label>
                <select name="kierunekStudenta" required>
                    <option value="" disabled>Wybierz kierunek</option>
                    <?php while ($course = mysqli_fetch_assoc($courseInfo)): ?>
                        <option value="<?php echo $course['ID']; ?>" 
                            <?php echo $course['ID'] == $student['id_kierunku'] ? 'selected' : ''; ?>>
                            <?php echo $course['nazwa']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <!-- Lista kierunków do przypisania -->

                <label for="rok">Rok</label>
                <input type="number" name="rokStudenta" value="<?php echo $student['rok']; ?>" required>

                <label for="rok">Rocznik</label>
                <input type="number" name="rocznikStudenta" value="<?php echo $student['rocznik']; ?>" required>

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
    <script src="../../assets/js/modal_windows.js"></script>  
</body>
</html>
