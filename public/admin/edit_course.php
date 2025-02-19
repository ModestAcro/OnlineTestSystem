<?php

    session_start();

    require_once('../../config/connect.php');
    require_once('../../config/functions.php');
    
    $kierunekId = $_GET['kierunek_id'];
    $kierunek = getRecordById($conn, 'tKierunki', $kierunekId);

    $subjectInfo = getTableInfo($conn, 'tPrzedmioty');

    $assignetSubjects = getSubjectsByKierunek($conn, $kierunekId);

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

    <title>Edytuj kierunek</title>
</head>
<body>

    <?php include '../../includes/header.php'; ?>
    
    <main class="main">
        <div class="container">
            <h1>Edytuj kierunek</h1>
            <form action="../../includes/admin/update_course.php" method="POST">
                <input type="hidden" name="idKierunku" value="<?php echo $kierunek['ID']; ?>">

                <label>Nazwa</label>
                <input type="text" name="nazwaKierunku" value="<?php echo $kierunek['nazwa']; ?>" required>

                <!-- Lista przedmiotów -->
                <label>Wybierz przedmioty</label>
                <select id="przedmioty" name="przedmioty[]" multiple>
                    <?php
                    // Przechodzimy przez wszystkie przedmioty
                    while ($subject = mysqli_fetch_assoc($subjectInfo)): 
                        // Pobranie listy ID przypisanych studentów
                        $assignetSubjectsIds = array_column($assignetSubjects, 'id_przedmiotu');
                        
                        // Sprawdzenie, czy przedmiot jest już przypisany do kierunku
                        $czyZaznaczony = in_array($subject['ID'], $assignetSubjectsIds) ? 'selected' : '';
                    ?>
                        <option value="<?php echo $subject['ID']; ?>" <?php echo $czyZaznaczony; ?>>
                            <?php echo $subject['nazwa']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <!-- Lista przedmiotów -->

                <label>Uwagi</label>
                <textarea name="uwagiKierunku"><?php echo $kierunek['uwagi']; ?></textarea>

                <button type="submit" name="action" value="update" class="submit-btn">Zapisz Zmiany</button>
                <button type="submit" name="action" value="delete" class="submit-btn" id="delete-btn">Usuń</button>
            </form>

            <!-- Okno modalne do potwierdzenia usunięcia Studenta-->
            <div id="deleteCharacterModal" class="modal">
                <div class="modal-content">
                    <span class="close-btn" id="deleteCharacterModalClose">&times;</span>
                    <h2>Czy na pewno chcesz usunąć ten kierunek?</h2>
                    <form action="../../includes/admin/update_course.php" method="POST">
                        <input type="hidden" name="idKierunku" value="<?php echo $kierunek['ID']; ?>">
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
        new MultiSelectTag('przedmioty')  // id
    </script>
    <!-- multi_select.js --> 
</body>
</html>
