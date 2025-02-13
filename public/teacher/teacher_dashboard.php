<?php

    session_start();

    require_once('../../config/connect.php');

    // Funkcja zwraca liczbę pytan konkretnego nauczyciela              
    function getTableCountByTeacherId($conn, $table, $id) {
    $query = "SELECT COUNT(*) AS liczba FROM $table WHERE id_wykladowcy = '$id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Błąd zapytania: " . mysqli_error($conn));
    }

    return mysqli_fetch_assoc($result)['liczba'] ?? 0;

    }

    $teacher_id = $_SESSION['user_id'] ?? null;

    $questionCount = getTableCountByTeacherId($conn, 'tPytania', $teacher_id);
    $testCount = getTableCountByTeacherId($conn, 'tTesty', $teacher_id);
    $groupCount = getTableCountByTeacherId($conn, 'tGrupy', $teacher_id);


    function getCompletedTestsCount($conn, $teacher_id){
        $query = "SELECT COUNT(*) AS liczba_zakonczonych_testow
                    FROM tTesty 
                    WHERE data_zakonczenia IS NOT NULL 
                    AND data_zakonczenia <= NOW()
                    AND id_wykladowcy = $teacher_id";
        
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($result)['liczba_zakonczonych_testow'];
    }

    $completedTestCount = getCompletedTestsCount($conn, $teacher_id);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Panel wykładowcy</title>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="left-header">
                <a class="nav-btn" href="tests.php">Testy</a>
                <a class="nav-btn" href="questions.php">Pytania</a>
                <a class="nav-btn" href="student_groups.php">Grupy studentów</a>
                <a class="nav-btn" href="completed_tests.php">Wyniki testów</a>
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
            <div class="info">
                 <!-- Informacja o testach -->
                 <div class="teachers">
                    <a href="tests.php" style="color: black;">
                        <div class="card">
                            <img src="../../assets/images/icons/online-test.svg" alt="Teacher Avatar" class="card-avatar">
                            <h3 class="card-title">Testy</h3>
                            <p class="card-count"><?php echo $testCount; ?></p>
                        </div>
                    </a>
                </div>
                <!-- Informacja o testach -->

                <!-- Informacja o pytania -->
                <div class="students">
                    <a href="questions.php" style="color: black;">
                        <div class="card">
                            <img src="../../assets/images/icons/question.svg" alt="Students Avatar" class="card-avatar">
                            <h3 class="card-title">Pytania</h3>
                            <p class="card-count"><?php echo $questionCount; ?></p>
                        </div>
                    </a>
                </div>
                <!-- Informacja o pytania -->

                <!-- Informacja o grupy -->
                <div class="subjects">
                    <a href="student_groups.php" style="color: black;">
                        <div class="card">
                            <img src="../../assets/images/icons/group.svg" alt="Book" class="card-avatar">
                            <h3 class="card-title">Grupy studentów</h3>
                            <p class="card-count"><?php echo $groupCount; ?></p>
                        </div>
                    </a>
                </div>
                <!-- Informacja o grupy -->


                <!-- Informacja o wykonanych testach -->
                <div class="subjects">
                    <a href="completed_tests.php" style="color: black;">
                        <div class="card">
                            <img src="../../assets/images/icons/checked.svg" alt="Book" class="card-avatar">
                            <h3 class="card-title">Wyniki testów</h3>
                            <p class="card-count"><?php echo $completedTestCount; ?></p>
                        </div>
                    </a>
                </div>
                <!-- Informacja o wykonanych testach -->
            </div>
        </div>
    </main>

    <!-- Plik JavaScript --> 
    <script src="../../assets/js/modal_windows.js"></script> 
</body>
</html>