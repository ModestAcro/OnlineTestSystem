<?php
    session_start();

    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $userId = $_SESSION['user_id'];

    // Pobranie ID grupy z parametrów GET
    $groupId = $_GET['group_id'];

    // Funkcja zwraca pojedynczy rekord na podstawie ID
    $group = getRecordById($conn, 'tGrupy', $groupId);

    // Pobranie danych uczelni
    $universityInfo = getEntityInfo($conn, 'tUczelnie');
    $subjectInfo = getEntityInfo($conn, 'tPrzedmioty');
    $studentInfo = getEntityInfo($conn, 'tStudenci'); 

    // Pobietranie studentów w konkretnej grupi
    $assignedStudents = getStudentsByGroupId($conn, $groupId);

    // Pobranie nazwy uczelni którą wybrał nauczyciel gdy tworzył grupę
    $assignedUniversityName = getEntityNameById($conn, 'tUczelnie', $group['id_uczelni']);
    $assignedUniversityID = $group['id_uczelni'];  // Dodaj tę linię

    // Pobranie nazwy przedmiotu którą wybrał nauczyciel gdy tworzył grupę
    $assignedSubjectName = getEntityNameById($conn, 'tPrzedmioty', $group['id_przedmiotu']);
    $assignedSubjectID = $group['id_przedmiotu'];

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
                <input type="hidden" name="group_id" value="<?php echo $group['ID']; ?>">

                <label>Rok</label>
                <input type="text" name="rok" value="<?php echo $group['rok']; ?>">

               <!-- Lista uczelni -->
                <select name="uczelnia" required>
                    <option value="<?php echo $assignedUniversityID; ?>" selected>
                        <?php echo $assignedUniversityName; ?>
                    </option>
                    <?php while ($university = mysqli_fetch_assoc($universityInfo)): ?>
                        <option value="<?php echo $university['ID']; ?>">
                            <?php echo $university['nazwa']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <!-- Lista uczelni -->

                <!-- Lista przedmiotów -->
                <select name="przedmiot" required>
                    <option value="<?php echo $assignedSubjectID; ?>" selected>
                        <?php echo $assignedSubjectName; ?>
                    </option>
                    <?php while ($subject = mysqli_fetch_assoc($subjectInfo)): ?>
                        <option value="<?php echo $subject['ID']; ?>">
                            <?php echo $subject['nazwa']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <!-- Lista przedmiotów -->

               <!-- Lista studentów -->
               <label>Wybierz studentów</label>
               <select id="studenci" name="studenci[]" multiple>
                    <?php 
                    // Przechodzimy przez wszystkich studentów
                    while ($student = mysqli_fetch_assoc($studentInfo)): 
                        // Sprawdzamy, czy student jest już przypisany do grupy
                        $isSelected = in_array($student['ID'], array_column($assignedStudents, 'id_studenta')) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $student['ID']; ?>" <?php echo $isSelected; ?>>
                            <?php echo $student['nr_albumu'] . ' - ' . $student['imie'] . ' ' . $student['nazwisko']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <!-- Lista studentów -->

                <label>Nazwa</label>
                <input type="text" name="nazwa" value="<?php echo htmlspecialchars($group['nazwa']); ?>">

                <button type="submit" name="action" value="update" class="submit-btn">Zapisz Zmiany</button>
                <button type="submit" name="action" value="delete" class="submit-btn" id="delete-btn">Usuń</button>
            </form>

            <!-- Okno modalne do potwierdzenia usunięcia Wykładowcy-->
            <div id="deleteCharacterModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" id="deleteCharacterModalClose">&times;</span>
                    <h2>Czy na pewno chcesz usunąć tę grupę?</h2>
                    <form action="../../includes/teacher/update_group.php" method="POST">
                        <input type="hidden" name="group_id" value="<?php echo $group['ID']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="submit" class="submit-btn" id="delete-btn">Tak, usuń</button>
                    </form>
                </div>
            </div>
            <!-- Okno modalne do potwierdzenia usunięcia -->

        </div>
    </main>

    <!-- Pliki JavaScript --> 
    <script src="../../assets/js/multi_select.js"></script>  
    <script src="../../assets/js/modalWindows.js"></script>  


    <!-- multi_select.js --> 
    <script>
        new MultiSelectTag('studenci')  // id
    </script>
    <!-- multi_select.js --> 

</body>
</html>
