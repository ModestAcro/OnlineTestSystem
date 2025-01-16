<?php

    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $universityInfo = getTableInfo($conn, 'tUczelnie');
    $universityCount = getTableCount($conn, 'tUczelnie');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Uczelnie</title>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="left-header">
                <a class="nav-btn" href="admin_dashboard.php">Strona główna</a>
                <a class="nav-btn" href="teachers.php">Wykładowcy</a>
                <a class="nav-btn" href="students.php">Studeńci</a>
                <a class="nav-btn" href="subjects.php">Przedmioty</a>
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
                <h1>Lista uczelni</h1>

                <!-- Przycisk "Dodaj Uczelnię" -->
                <button class="add-btn" onclick="addEntity()">
                    <img src="../../assets/images/icons/plus.svg" class="add-icon">
                </button>
                <!-- Przycisk "Dodaj Uczelnię" -->

                <!-- Okno modalne dodaj Uczelnię-->
                <div id="openModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" id="closeModal">&times;</span>
                        <h1 class="modal-header">Dodaj uczelnię</h1>
                        <form action="../../includes/admin/add_universities.php" method="POST">

                            <label>Nazwa</label>
                            <input type="text" name="nazwaUczelni" required>

                            <label>Miasto</label>
                            <input type="text" name="miastouczelni" required>

                            <label>Kraj</label>
                            <input type="text" name="krajUczelni" required>

                            <label>Kontynent</label>
                            <input type="text" name="kontynentUczelni" required>

                            <label>Adres</label>
                            <input type="text" name="adresuczelni" required>

                            <label>Uwagi</label>
                            <input type="text" name="uwagiUczelni" required>

                            <button type="submit" class="submit-btn">Dodaj uczelnię</button>
                        </form>
                    </div>
                </div>
                <!-- Okno modalne dodaj Uczelnię-->
            </div>

            <p>Ilość: <?php echo $universityCount; ?></p>
            <table>
                <thead>
                    <tr>
                        <th>Nazwa</th>
                        <th>Miasto</th>
                        <th>Kraj</th>
                        <th>Kontynent</th>
                        <th>Adres</th>
                        <th>Uwagi</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($universityData = mysqli_fetch_assoc($universityInfo)): ?>
                        <tr>
                            <td><?php echo $universityData['nazwa']; ?></td>
                            <td><?php echo $universityData['miasto']; ?></td>
                            <td><?php echo $universityData['kraj']; ?></td>
                            <td><?php echo $universityData['kontynent']; ?></td>
                            <td><?php echo $universityData['adres']; ?></td>
                            <td><?php echo $universityData['uwagi']; ?></td>
                  
                            <!-- Przyciski "Modyfikuj" -->
                            <td>
                            <button class="btn-edit" onclick="window.location.href='edit_universities.php?university_id=<?php echo $universityData['ID']; ?>'">
                                <img src="../../assets/images/icons/edit.svg" alt="Edit University" class="edit-icon">
                            </button>
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