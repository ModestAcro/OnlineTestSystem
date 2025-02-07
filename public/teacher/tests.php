<?php

    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $user_id = $_SESSION['user_id'];

    // Pobiera liczbę testów związanych z nauczycielem
    $testCount = getTestCountByTeacherId($conn, 'tTesty', $user_id); 

    $tTestInfo = getTestsByTeacherId($conn, $user_id);

    // Funkcja do pobierania kierunków przypisanych do nauczyciela
    function getCoursesByTeacher2($conn, $user_id) {
        // Zapytanie SQL do pobrania kierunków przypisanych do nauczyciela
        $sql = "SELECT k.nazwa, k.ID
                FROM tKierunki k
                JOIN twykladowcykierunki t ON t.id_kierunku = k.ID
                WHERE t.id_wykladowcy = $user_id";  // Wstawienie $teacherId bezpośrednio w zapytaniu
        
        // Wykonanie zapytania
        $result = mysqli_query($conn, $sql);

        return $result;
    }

    $courseByTeacher = getCoursesByTeacher2($conn, $user_id);


    function getSubjectsByTeacher($conn, $user_id) {
        $sql = "SELECT p.nazwa, p.ID
                FROM tPrzedmioty p
                JOIN tKierunkiPrzedmioty kp ON p.ID = kp.id_przedmiotu
                JOIN tKierunki k ON kp.id_kierunku = k.ID
                JOIN tWykladowcyKierunki wk ON wk.id_kierunku = k.ID
                WHERE wk.id_wykladowcy = $user_id";
    
        $result = mysqli_query($conn, $sql);
        
        return $result;
    }

    $subjectsByTeacher = getSubjectsByTeacher($conn, $user_id);


    function getTestDetails($conn, $test_id) {
        $query = "SELECT t.ID, t.id_przedmiotu, p.nazwa AS nazwa_przedmiotu, COUNT(tp.id_pytania) AS liczba_pytan
                  FROM tTesty t
                  JOIN tPrzedmioty p ON t.id_przedmiotu = p.ID
                  LEFT JOIN tTestPytania tp ON tp.id_testu = t.ID
                  WHERE t.ID = $test_id
                  GROUP BY t.ID, t.id_przedmiotu, p.nazwa";
    
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
    
        return $row ? ['nazwa_przedmiotu' => $row['nazwa_przedmiotu'], 'liczba_pytan' => $row['liczba_pytan']] : ['nazwa_przedmiotu' => null, 'liczba_pytan' => 0];
    }


    function getGroupDetails($conn, $test_id){
        $query = "
        SELECT 
            t.id_grupy, 
            g.ID AS id_grupy,
            g.nazwa AS nazwa_grupy,
            g.rok AS rok_grupy,
            COUNT(gs.id_studenta) AS liczba_studentów,
            GROUP_CONCAT(CONCAT(s.imie, ' ', s.nazwisko, ' (', s.nr_albumu, ')') SEPARATOR '; ') AS studenci
        FROM tTesty t
        JOIN tGrupy g ON t.id_grupy = g.ID
        LEFT JOIN tGrupyStudenci gs ON g.ID = gs.id_grupy
        LEFT JOIN tStudenci s ON gs.id_studenta = s.id
        WHERE t.id = $test_id
        GROUP BY t.id_grupy, g.ID, g.nazwa, g.rok;
    ";

    $result = mysqli_query($conn, $query);

    return $result;

    }




    


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Lista testów</title>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="left-header">
                <a class="nav-btn" href="teacher_dashboard.php">Strona główna</a>
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
            <div class="title">
                <h1>Lista testów</h1>


                <!-- Przycisk "Dodaj Test" -->
                <button class="add-btn" onclick="addEntity()">
                    <img src="../../assets/images/icons/plus.svg" alt="Plus icon" class="add-icon">
                </button>
                <!-- Przycisk "Dodaj Test" -->


                <!-- Okno modalne dodaj test-->
                <div id="openModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" id="closeModal">&times;</span>
                        <h1 class="modal-header">Dodaj Test</h1>
                        <form action="add_test.php" method="POST">


                        <label>Nazwa</label>
                        <input type="text" name="nazwa" required>


                        <!-- Lista kierunków do przypisania -->
                        <label>Kierunek</label>
                        <select name="kierunek" required>
                            <option disabled selected>Wybierz kierunek</option>
                            <?php while ($courses = mysqli_fetch_assoc($courseByTeacher)): ?>
                                <option value="<?php echo $courses['ID']; ?>">
                                    <?php echo $courses['nazwa']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <!-- Lista kierunków do przypisania -->


                        <!-- Lista przedmiotów do przypisania -->
                        <label>Przedmiot</label>
                        <select name="przedmiot" required>
                            <option disabled selected>Wybierz kierunek</option>
                            <?php while ($subjects = mysqli_fetch_assoc($subjectsByTeacher)): ?>
                                <option value="<?php echo $subjects['ID']; ?>">
                                    <?php echo $subjects['nazwa']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                        <!-- Lista przedmiotów do przypisania -->

                            <button type="submit" class="submit-btn">Wybierz</button>
                        </form>
                    </div>
                </div>
                <!-- Okno modalne dodaj test-->
            </div>

            <p>Ilość: <?php echo $testCount; ?></p>
            <table>
                <thead>
                    <tr>
                        <th>Nazwa</th>
                        <th>Przedmiot</th>
                        <th>Grupa</th>
                        <th>Data utworzenia</th>
                        <th>Data</th>
                        <th>Czas trwania (min.)</th>
                        <th>Ilość prób</th>
                        <th>Ilość pytań</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($testData = mysqli_fetch_assoc($tTestInfo)): ?>
                        <?php      $test_id = $testData['ID']; ?>
                        <tr>
                            <td><?php echo $testData['nazwa']; ?></td>

                            <td>
                                <?php 
                                     $testDetails = getTestDetails($conn, $test_id);
                                     echo ($testDetails['nazwa_przedmiotu'] ?? 'Brak danych') . "<br>";
                                ?>
                            </td>

                            <td>
                                <?php
                                    $groupDetails = getGroupDetails($conn, $test_id);
                                    if ($groupDetails && $group = mysqli_fetch_assoc($groupDetails)) {
                                        $studentList = $group['studenci']; // Lista studentów z funkcji getGroupDetails
                                        echo "<span class='group-name' data-students='$studentList'>{$group['nazwa_grupy']}</span>"; 
                                    } else {
                                        echo 'Brak grupy';
                                    }
                                ?>
                            </td>

                            <td><?php echo date('Y-m-d', strtotime($testData['data_utworzenia'])); ?></td>
                            

                            <td>
                                <?php
                                    $start = $testData['data_rozpoczecia'] ? date('Y-m-d', strtotime($testData['data_rozpoczecia'])) : 'Brak';
                                    $end = $testData['data_zakonczenia'] ? date('Y-m-d', strtotime($testData['data_zakonczenia'])) : 'Brak';
                                    echo "$start / $end";
                                ?>
                            </td>

                            <td><?php echo $testData['czas_trwania']; ?></td>
                            <td>
                                <?php 
                                    echo $testData['ilosc_prob'] == -1 ? 'Nieograniczona' : $testData['ilosc_prob'];
                                ?>
                            </td>
                            <td>
                                <?php
                                    $testDetails = getTestDetails($conn, $test_id);
                                    echo $testDetails['liczba_pytan'];
                                ?>
                            </td>


                    
                            <!-- Przyciski "Modyfikuj" -->
                            <td>
                                <a href="edit_test.php?test_id=<?php echo $testData['ID']; ?>" class="btn-edit">
                                    <img src="../../assets/images/icons/edit.svg" class="edit-icon">
                                </a>
                            </td>
                            <!-- Przyciski "Modyfikuj" -->
                             
                        </tr>
                    <?php endwhile; ?> 
                </tbody>
            </table>
        </div>
    </main>    

    
    <script src="../../assets/js/modal_windows.js"></script>  

</body>
</html>