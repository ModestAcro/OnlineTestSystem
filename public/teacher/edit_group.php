<?php
    session_start();

    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

  // Pobranie ID grupy z parametrów GET
    $GroupId = $_GET['id'];

    // Wywołanie funkcji z functions.php, aby pobrać dane grupy
    $group = getGroupById($conn, $GroupId);

    // Pobranie danych uczelni
    $universityInfo = getEntityInfo($conn, 'tUczelnie');
    $subjectInfo = getEntityInfo($conn, 'tPrzedmioty');
    $studentList = getEntityInfo($conn, 'tStudenci'); 

    // Wywołanie funkcji do zliczania studentów w grupach
    $studentCountData = getStudentCountByGroup($conn, $characterId);

    // Wywołanie wynkcji do zliczania studentów do 
    $assignedStudents = getStudentsByGroupId($conn, $GroupId);

    $assignedUniversityName = $group['uczelnia']; // Uczelnia przypisana do grupy
    $assignedSubjectName = $group['przedmiot']; // lub inne odpowiednie pole z bazy danych

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

                
                <!-- Lista uczelni -->
                <label for="uczelnia">Uczelnia</label>
                <select id="uczelnia" name="uczelnia" required>
                    <option value="<?php echo htmlspecialchars($assignedUniversityName); ?>" selected>
                        <?php echo htmlspecialchars($assignedUniversityName); ?>
                    </option>
                    <?php while ($university = mysqli_fetch_assoc($universityInfo)): ?>
                        <option value="<?php echo $university['nazwa_uczelni']; ?>">
                            <?php echo htmlspecialchars($university['nazwa_uczelni']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <!-- Lista uczelni -->
            
                <!-- Lista przedmiotów -->
                <label for="przedmiot">Przedmiot</label>
                <select id="przedmiot" name="przedmiot" required>
                    <option value="<?php echo htmlspecialchars($assignedSubjectName); ?>" selected>
                        <?php echo htmlspecialchars($assignedSubjectName); ?>
                    </option>
                    <?php while ($subject = mysqli_fetch_assoc($subjectInfo)): ?>
                        <option value="<?php echo $subject['nazwa']; ?>">
                            <?php echo htmlspecialchars($subject['nazwa']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <!-- Lista przedmiotów -->


               <!-- Lista studentów -->
               <label for="studenci">Wybierz studentów</label>
               <select id="studenci" name="studenci[]" multiple>
                    <?php 
                    // Przechodzimy przez wszystkich studentów
                    while ($student = mysqli_fetch_assoc($studentList)): 
                        // Sprawdzamy, czy student jest już przypisany do grupy
                        $isSelected = in_array($student['ID'], array_column($assignedStudents, 'id_studenta')) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $student['ID']; ?>" <?php echo $isSelected; ?>>
                            <?php echo htmlspecialchars($student['nr_albumu'] . ' - ' . $student['imie'] . ' ' . $student['nazwisko']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <!-- Lista studentów -->



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

    <!-- Pliki JavaScript --> 
    <script src="../../assets/js/multi_select.js"></script>  
    <script src="../../assets/js/admin/modalWindows.js"></script>  


    <!-- multi_select.js --> 
    <script>
        new MultiSelectTag('studenci')  // id
    </script>
    <!-- multi_select.js --> 
</body>
</html>
