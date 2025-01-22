<?php

    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $subjectInfo = getTableInfo($conn, 'tPrzedmioty');
    $subjectCount = getTableCount($conn, 'tPrzedmioty');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Przedmioty</title>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="left-header">
                <a class="nav-btn" href="admin_dashboard.php">Strona główna</a>
               <!-- <a class="nav-btn" href="teachers.php">Wykładowcy</a>
                <a class="nav-btn" href="students.php">Studeńci</a>
                <a class="nav-btn" href="courses.php">Kierunki</a> -->
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
                <h1>Lista przedmiotów</h1>

                <!-- Przycisk "Dodaj Przedmiot" -->
                <button class="add-btn" onclick="addEntity()">
                    <img src="../../assets/images/icons/plus.svg" class="add-icon">
                </button>
                <!-- Przycisk "Dodaj Przedmiot" -->

                <!-- Okno modalne dodaj Przedmiot-->
                <div id="openModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" id="closeModal">&times;</span>
                        <h1 class="modal-header">Dodaj przedmiot</h1>
                        <form action="../../includes/admin/save_subject.php" method="POST">

                            <label for="nazwa">Nazwa</label>
                            <input type="text" id="nazwa" name="nazwa" required>

                            <label for="uwagi">Uwagi</label>
                            <input type="text" id="uwagi" name="uwagi" required>

                            <button type="submit" class="submit-btn">Dodaj przedmiot</button>
                        </form>
                    </div>
                </div>
                <!-- Okno modalne dodaj Studenta-->
            </div>

            <p>Ilość: <?php echo $subjectCount; ?></p>
            <table>
                <thead>
                    <tr>
                        <th>Nazwa</th>
                        <th>Uwagi</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($subjectData = mysqli_fetch_assoc($subjectInfo)): ?>
                        <tr>
                            <td><?php echo $subjectData['nazwa']; ?></td>
                            <td><?php echo $subjectData['uwagi']; ?></td>
                  
                            <!-- Przyciski "Modyfikuj" -->
                            <td>
                                <a href="edit_subject.php?subject_id=<?php echo $subjectData['ID']; ?>" class="btn-edit">
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
    
    <!-- Plik JavaScript --> 
    <script src="../../assets/js/modal_windows.js"></script>  
</body>
</html>