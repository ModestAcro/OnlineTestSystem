<?php

    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $kierunekInfo = getTableInfo($conn, 'tKierunki');
    $przedmiotInfo = getTableInfo($conn, 'tPrzedmioty');

    $kierunekCount = getTableCount($conn, 'tKierunki');

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
                <!-- <a class="nav-btn" href="teachers.php">Wykładowcy</a>
                <a class="nav-btn" href="students.php">Studeńci</a>
                <a class="nav-btn" href="subjects.php">Przedmioty</a> -->
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
                <h1>Lista kierunków</h1>

                <!-- Przycisk "Dodaj Kierunek" -->
                <button class="add-btn" onclick="addEntity()">
                    <img src="../../assets/images/icons/plus.svg" class="add-icon">
                </button>
                <!-- Przycisk "Dodaj Kierunek" -->

                <!-- Okno modalne dodaj Kierunek-->
                <div id="openModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" id="closeModal">&times;</span>
                        <h1 class="modal-header">Dodaj kierunek</h1>
                        <form action="../../includes/admin/save_course.php" method="POST">

                            <label>Nazwa</label>
                            <input type="text" name="nazwaKierunku" required>

                            <!-- Lista przedmiotów do przypisania -->
                            <label>Wybierz przedmioty</label>
                            <select id="przedmioty" name="przedmioty[]" multiple>
                                <?php 
                                // Przechodzimy przez listę przedmiotów
                                while ($przedmiot = mysqli_fetch_assoc($przedmiotInfo)): ?>
                                    <option value="<?php echo $przedmiot['ID']; ?>">
                                        <?php echo $przedmiot['nazwa']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <!-- Lista przedmiotów do przypisania -->


                            <label>Uwagi</label>
                            <input type="text" name="uwagiKierunku">

                            <button type="submit" class="submit-btn">Dodaj</button>
                        </form>
                    </div>
                </div>
                <!-- Okno modalne dodaj Kierunek-->
            </div>

            <p>Ilość: <?php echo $kierunekCount; ?></p>
            <table>
                <thead>
                    <tr>
                        <th>Nazwa</th>
                        <th>Liczba przedmiotów</th>
                        <th>Uwagi</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($kierunekData = mysqli_fetch_assoc($kierunekInfo)): ?>
                        <tr>
                            <td><?php echo $kierunekData['nazwa']; ?></td>
                            <td>
                                <?php 
                                    // Wywołanie funkcji do zliczania liczby przedmiotów przypisanych do kierunku
                                    $courseId = $kierunekData['ID'];
                                    $subjectCount = getSubjectCountByCourse($conn, $courseId);
                                    echo $subjectCount; // Wyświetlanie liczby przedmiotów
                                ?>
                            </td>
                            <td><?php echo $kierunekData['uwagi']; ?></td>
                  
                            <!-- Przyciski "Modyfikuj" -->
                            <td>
                            <button class="btn-edit" onclick="window.location.href='edit_course.php?kierunek_id=<?php echo $kierunekData['ID']; ?>'">
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
    
    <!-- Pliki JavaScript --> 
    <script src="../../assets/js/modal_windows.js"></script>   
    <script src="../../assets/js/multi_select.js"></script>  

    <!-- multi_select.js --> 
    <script>
        new MultiSelectTag('przedmioty')  // id
    </script>
    <!-- multi_select.js --> 
</body>
</html>