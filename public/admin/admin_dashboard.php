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
        <div class="container py-4">
            <div class="row g-3">
                <!-- Wykładowcy -->
                <div class="col-md-6 col-lg-3">
                    <a href="teachers.php" class="text-decoration-none text-dark">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <img src="../../assets/images/icons/teacher-icon.png" alt="Teacher Icon" class="img-fluid mb-2" style="width: 50px;">
                                <h3 class="card-title">Wykładowcy</h3>
                                <p class="card-text"><?php echo $teacherCount; ?></p>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Wykładowcy -->

                <!-- Studeńci-->
                <div class="col-md-6 col-lg-3">
                    <a href="students.php" class="text-decoration-none text-dark">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <img src="../../assets/images/icons/student-avatar.svg" alt="Student Icon" class="img-fluid mb-2" style="width: 50px;">
                                <h3 class="card-title">Studeńci</h3>
                                <p class="card-text"><?php echo $studentCount; ?></p>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Studeńci -->
              
                <!-- Przedmioty -->
                <div class="col-md-6 col-lg-3">
                    <a href="subjects.php" class="text-decoration-none text-dark">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <img src="../../assets/images/icons/book.svg" alt="Subject Icon" class="img-fluid mb-2" style="width: 50px;">
                                <h3 class="card-title">Przedmioty</h3>
                                <p class="card-text"><?php echo $subjectCount; ?></p>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Przedmioty -->

                <!-- Kierunki -->
                <div class="col-md-6 col-lg-3">
                    <a href="courses.php" class="text-decoration-none text-dark">
                        <div class="card text-center shadow-sm">
                            <div class="card-body">
                                <img src="../../assets/images/icons/map-signs.svg" alt="Course Icon" class="img-fluid mb-2" style="width: 50px;">
                                <h3 class="card-title">Kierunki</h3>
                                <p class="card-text"><?php echo $coursesCount; ?></p>
                            </div>
                        </div>
                    </a>
                </div>
                <!-- Kierunki -->
            </div>  
        </div>
    </main>


</body>
</html>