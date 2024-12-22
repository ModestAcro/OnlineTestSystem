<?php
    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $studentCount = getEntityCount($conn, 'tStudenci');  // Liczba studentów
    $teacherCount = getEntityCount($conn, 'tWykladowcy');  // Liczba nauczycieli
    $subjectCount = getEntityCount($conn, 'tPrzedmioty');
    $universityCount = getEntityCount($conn, 'tUczelnie');

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
                <a class="nav-btn" href="universities.php">Uczelnie</a>
            </div>
            <div class="right-header">
                <span class="name"><?php echo $_SESSION['imie'] . ' ' . $_SESSION['nazwisko']; ?></span>

                <!-- Formularz wylogowania -->
                <form action="../../config/logout.php" method="POST">
                    <button type="submit" class="logout-btn">Wyloguj</button>
                </form>
                <!-- Formularz wylogowania -->
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <div class="info">
                <div class="teachers">
                    <div class="card">
                        <img src="../../assets/images/icons/teacher-icon.png" alt="Teacher Avatar" class="card-avatar">
                        <h3 class="card-title">Wykładowcy</h3>
                        <p class="card-count"><?php echo $teacherCount; ?></p>
                    </div>
                </div>
                <div class="students">
                    <div class="card">
                        <img src="../../assets/images/icons/student-avatar.svg" alt="Students Avatar" class="card-avatar">
                        <h3 class="card-title">Studeńci</h3>
                        <p class="card-count"><?php echo $studentCount; ?></p>
                    </div>
                </div>
                <div class="subjects">
                    <div class="card">
                        <img src="../../assets/images/icons/book.svg" alt="Book" class="card-avatar">
                        <h3 class="card-title">Przedmioty</h3>
                        <p class="card-count"><?php echo $subjectCount; ?></p>
                    </div>
                </div>
                <div class="subjects">
                    <div class="card">
                        <img src="../../assets/images/icons/building.svg" alt="Book" class="card-avatar">
                        <h3 class="card-title">Uczelnie</h3>
                        <p class="card-count"><?php echo $universityCount; ?></p>
                    </div>
                </div>
            </div>  
        </div>
    </main>
</main>
</body>
</html>