<?php
    session_start();
    require_once('../../config/connect.php');

    $studentQuery = "SELECT COUNT(*) AS studentCount FROM tStudenci";
    $studentResult = mysqli_query($conn, $studentQuery);
    $studentRow = mysqli_fetch_assoc($studentResult);
    $studentCount = $studentRow['studentCount'];  // Liczba studentów

    $teacherQuery = "SELECT COUNT(*) AS teacherCount FROM tWykladowcy";
    $teacherResult = mysqli_query($conn, $teacherQuery);
    $teacherRow = mysqli_fetch_assoc($teacherResult);
    $teacherCount = $teacherRow['teacherCount'];  // Liczba nauczycieli
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
            </div>
            <div class="right-header">
                <span class="name"><?php echo $_SESSION['imie'] . ' ' . $_SESSION['nazwisko']; ?></span>

                <!-- Formularz wylogowania -->
                <form action="../../config/logout.php" method="POST">
                    <button type="submit" class="logout-btn">Wyloguj</button>
                </form>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <div class="info">
                <div class="teachers">
                    <div class="card">
                        <img src="../../assets/images/icons/owl-avatar.svg" alt="Teacher Avatar" class="card-avatar">
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
            </div>  
        </div>
    </main>
</main>
</body>
</html>