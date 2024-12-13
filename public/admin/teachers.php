<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Wykładowcy</title>
</head>
<body>
    <header>
        <div class="left-header">
            <a class="nav-btn" href="admin_dashboard.php">Strona główna</a>
            <a class="nav-btn" href="students.php">Studeńci</a>
        </div>
        <div class="right-header">
            <span class="name"><?php echo $_SESSION['imie'] . ' ' . $_SESSION['nazwisko']; ?></span>

            <!-- Formularz wylogowania -->
            <form action="../../config/logout.php" method="POST">
                <button type="submit" class="logout-btn">Wyloguj</button>
            </form>
        </div>
    </header>
</body>
</html>