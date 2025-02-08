<?php
    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $student_id = $_SESSION['user_id'] ?? null;

    $studentInfo = getRecordById($conn, 'tStudenci', $student_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Panel studenta</title>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="left-header">
                <a class="nav-btn" href="tests.php">Testy</a>
                <a class="nav-btn" href="">Oceny</a>
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


    <!-- Plik JavaScript --> 
    <script src="../../assets/js/modal_windows.js"></script> 
</body>
</html>