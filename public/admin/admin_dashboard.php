<?php
    session_start();
    
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $studentCount = getTableCount($conn, 'tStudenci');     // Liczba studentów
    $teacherCount = getTableCount($conn, 'tWykladowcy');   // Liczba nauczycieli
    $subjectCount = getTableCount($conn, 'tPrzedmioty');   // Liczba przedmiotów
    $coursesCount = getTableCount($conn, 'tKierunki');  // Liczba uczelni

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


    <title>Panel administratora</title>
</head>
<body>
    
    <?php include '../../includes/header.php'; ?>

    <main class="main">
        <div class="container">
            <div class="info">
                <!-- Informacja o wykładowcach -->
                <div class="teachers">
                    <a href="teachers.php" style="color: black;">
                        <div class="card">
                            <img src="../../assets/images/icons/teacher-icon.png" alt="Teacher Avatar" class="card-avatar">
                            <h3 class="card-title">Wykładowcy</h3>
                            <p class="card-count"><?php echo $teacherCount; ?></p>
                        </div>
                    </a>
                </div>
                <!-- Informacja o wykładowcach -->

                <!-- Informacja o studentach -->
                <div class="students">
                    <a href="students.php" style="color: black;">
                        <div class="card">
                            <img src="../../assets/images/icons/student-avatar.svg" alt="Students Avatar" class="card-avatar">
                            <h3 class="card-title">Studeńci</h3>
                            <p class="card-count"><?php echo $studentCount; ?></p>
                        </div>
                    </a>
                </div>
                <!-- Informacja o studentach -->

                <!-- Informacja o przedmiotach -->
                <div class="subjects">
                    <a href="subjects.php" style="color: black;">
                        <div class="card">
                            <img src="../../assets/images/icons/book.svg" alt="Book" class="card-avatar">
                            <h3 class="card-title">Przedmioty</h3>
                            <p class="card-count"><?php echo $subjectCount; ?></p>
                        </div>
                    </a>
                </div>
                <!-- Informacja o przedmiotach -->

                <!-- Informacja o kierunkach -->
                <div class="subjects">
                    <a href="courses.php" style="color: black;">
                        <div class="card">
                            <img src="../../assets/images/icons/building.svg" alt="Book" class="card-avatar">
                            <h3 class="card-title">Kierunki</h3>
                            <p class="card-count"><?php echo $coursesCount; ?></p>
                        </div>
                    </a>
                </div>
                <!-- Informacja o kierunkach -->
            </div>  
        </div>
    </main>
</main>


    <!-- Plik JavaScript --> 
    <script src="../../assets/js/modal_windows.js"></script>  
</body>
</html>