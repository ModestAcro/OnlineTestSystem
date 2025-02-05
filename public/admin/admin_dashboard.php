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
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Panel administratora</title>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="left-header">
                <a class="nav-btn" href="teachers.php">Wykładowcy</a>
                <a class="nav-btn" href="students.php">Studeńci</a>
                <a class="nav-btn" href="subjects.php">Przedmioty</a>
                <a class="nav-btn" href="courses.php">Kierunki</a>
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