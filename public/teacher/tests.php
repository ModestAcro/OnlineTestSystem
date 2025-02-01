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
                        <th>Data utworzenia</th>
                        <th>Data rozpoczęcia</th>
                        <th>Data zakończenia</th>
                        <th>Czas trwania (min.)</th>
                        <th>Ilość prób</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($testData = mysqli_fetch_assoc($tTestInfo)): ?>
                        <tr>
                            <td><?php echo $testData['nazwa']; ?></td>
                            <td><?php echo date('Y-m-d', strtotime($testData['data_utworzenia'])); ?></td>

                            <!-- Zobacz jezeli data_rozpoczecia rowna NULL to wyswietl swoj nadpis -->
                            <td>
                                <?php 
                                    echo $testData['data_rozpoczecia'] ? date('Y-m-d', strtotime($testData['data_rozpoczecia'])) : 'Brak';
                                ?>
                            </td>
                            
                            <!-- Zobacz jezeli data-zakonczenia rowna NULL to wyswietl swoj nadpis -->
                            <td>
                                <?php 
                                    echo $testData['data_zakonczenia'] ? date('Y-m-d', strtotime($testData['data_zakonczenia'])) : 'Brak';
                                ?>
                            </td>

                            <td><?php echo $testData['czas_trwania']; ?></td>
                            <td>
                                <?php 
                                    echo $testData['ilosc_prob'] == -1 ? 'Nieograniczona' : $testData['ilosc_prob'];
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