<?php

    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $userId = $_SESSION['user_id'];

    // Pobiera liczbę testów związanych z nauczycielem
    $testCount = getTestCountByTeacherId($conn, 'tTesty', $userId); 

    $tTestyInfo = getTestsByTeacherId($conn, $userId);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Lista testów</title>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="left-header">
                <a class="nav-btn" href="teacher_dashboard.php">Strona główna</a>
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
                <h1>Lista testów</h1>

               <!-- Przycisk "Dodaj Test" -->
                <button class="add-btn" onclick="location.href='add_test.php'">
                    <img src="../../assets/images/icons/plus.svg" alt="Plus icon" class="add-icon">
                </button>
                <!-- Przycisk "Dodaj Test" -->


                <!-- Okno modalne dodaj test-->
                <div id="openModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" id="closeModal">&times;</span>
                        <h1 class="modal-header">Dodaj Test</h1>
                        <form action="../../includes/teacher/save_test.php" method="POST">

                            <label>Nazwa</label>
                            <input type="text" name="nazwaTestu" required>

                            <label>Data rozpoczęcia</label>
                            <input type="date" name="dataRozpoczeciaTestu" required>

                            <label>Data zakończenia</label>
                            <input type="date" name="dataZakonczeniaTestu" required>

                            <label>Czas trwania (min.)</label>
                            <input type="number" name="czasTrwaniaTestu" required>

                            <label>Ilość prób</label>
                            <input type="text" name="iloscProbTestu" required>

                            <button type="submit" class="submit-btn">Dodaj</button>
                        </form>
                    </div>
                </div>
                <!-- Okno modalne dodaj test-->
            </div>

            <p>Ilość: <?php echo $testCount; ?></p>
            <table>
                <thead>
                    <tr>
                        <th>Nazwa</th>
                        <th>Data utworzenia</th>
                        <th>Data rozpoczęcia</th>
                        <th>Data zakończenia</th>
                        <th>Czas trwania (min.)</th>
                        <th>Ilość prób</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($testData = mysqli_fetch_assoc($tTestyInfo)): ?>
                        <tr>
                            <td><?php echo $testData['nazwa']; ?></td>
                            <td><?php echo date('Y-m-d', strtotime($testData['data_utworzenia'])); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($testData['data_rozpoczecia'])); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($testData['data_zakonczenia'])); ?></td>
                            <td><?php echo $testData['czas_trwania']; ?></td>
                            <td><?php echo $testData['ilosc_prob']; ?></td>
                    
                            <!-- Przyciski "Modyfikuj" -->
                            <td>
                                <a href="edit_test.php?test_id=<?php echo $testData['ID']; ?>" class="btn-edit">
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

    
    <script src="../../assets/js/modal_windows.js"></script>  

</body>
</html>