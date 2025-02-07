<?php
    session_start();

    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $userId = $_SESSION['user_id'];

    // Pobranie ID grupy z parametrów GET
    $groupId = $_GET['group_id'];

    // Funkcja wyszukuje rekord w tabeli po ID i pobiera wszystkie dane z tego rekordu
    $group = getRecordById($conn, 'tGrupy', $groupId);

    // Pobieranie ID uniwersytetu oraz przedmiotu którą wybrał nauczyciel gdy tworzył grupę
    $assignedUniversityID = $group['id_kierunku'];
    $assignedSubjectID = $group['id_przedmiotu'];

    // Pobranie danych uczelni
    $universityInfo = getTableInfo($conn, 'tKierunki');
    $subjectInfo = getTableInfo($conn, 'tPrzedmioty');
    $studentInfo = getTableInfo($conn, 'tStudenci'); 

    // Pobietranie studentów w konkretnej grupie
    $assignedStudents = getStudentsByGroupId($conn, $groupId);

    // Pobranie nazwy uczelni którą wybrał nauczyciel gdy tworzył grupę
    $assignedUniversityName = getRecordNameById($conn, 'tKierunki', $assignedUniversityID);

    // Pobranie nazwy przedmiotu którą wybrał nauczyciel gdy tworzył grupę
    $assignedSubjectName = getRecordNameById($conn, 'tPrzedmioty', $assignedSubjectID);

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
    <header>
        <div class="header-content">
            <div class="left-header">
                <a class="nav-btn" href="student_groups.php">Grupy studentów</a>
            </div>
            <div class="right-header">
                <span class="name"><?php echo $_SESSION['user_name'] . ' ' . $_SESSION['user_surname']; ?></span>

                <!-- Formularz wylogowania -->
                <?php
                    include('../../includes/logout_modal.php');
                ?>
                <!-- Formularz wylogowania -->

            </div>
        </div>
    </header>
    <main class="main">
        <div class="container">
            <h1>Edytuj grupę</h1>
            <form action="../../includes/teacher/update_group.php" method="POST">
                <input type="hidden" name="group_id" value="<?php echo $group['ID']; ?>">

                <label>Rok</label>
                <input type="text" name="rok" value="<?php echo $group['rok']; ?>">

                <!-- Lista uczelni -->
                <label>Uczelnia</label>
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
                <label>Przedmiot</label>
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
                        // Pobranie listy ID przypisanych studentów
                        $assignedStudentIds = array_column($assignedStudents, 'id_studenta');
                        
                        // Sprawdzenie, czy student jest już przypisany do grupy
                        $czyZaznaczony = in_array($student['ID'], $assignedStudentIds) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $student['ID']; ?>" <?php echo $czyZaznaczony; ?>>
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
    <script src="../../assets/js/modal_windows.js"></script>  


    <!-- multi_select.js --> 
    <script>
        new MultiSelectTag('studenci')  // id
    </script>
    <!-- multi_select.js --> 

</body>
</html>
