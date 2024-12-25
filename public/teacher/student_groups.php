<?php

    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $userId = $_SESSION['user_id'];

    // Pobiera liczbę grup związanych z nauczycielem
    $studentGroupCount = getGroupCountByTeacherId($conn, 'tGrupy', $userId); 

    // Pobiera dane dla tabel tStudenci, tPrzedmioty, tUczelnie
    $studentInfo = getEntityInfo($conn, 'tStudenci'); 
    $subjectInfo = getEntityInfo($conn, 'tPrzedmioty');
    $universityInfo = getEntityInfo($conn, 'tUczelnie');

    // Wywołanie funkcji do zliczania studentów w konkretnej grupie
    $studentCountAtGroup = getStudentCountByGroup($conn, $userId);


    $studentGroupInfo = getStudentGroups($conn, $userId);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Grupy studentów</title>
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
                <form action="../../config/logout.php" method="POST">
                    <button type="submit" class="logout-btn">Wyloguj</button>
                </form>
                <!-- Formularz wylogowania -->

            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <div class="title">
                <h1>Lista grup</h1>

                <!-- Przycisk "Dodaj Grupę" -->
                <button class="add-btn" onclick="addEntity()">
                    <img src="../../assets/images/icons/plus.svg" alt="Plus icon" class="add-icon">
                </button>
                <!-- Przycisk "Dodaj Grupę" -->

                <!-- Okno modalne dodaj gupę studentów-->
                <div id="openModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" id="closeModal">&times;</span>
                        <h1 class="modal-header">Dodaj Grupę</h1>
                        <form action="../../includes/teacher/add_group.php" method="POST">

                            <label>Rok</label>
                            <input type="text" pattern="\d{4}" id="rok" name="rok" required>

                             <!-- Lista uczleni do przypisania -->
                            <label>Uczelnia</label>
                            <select name="uczelnia" required>
                                <option disabled selected>Wybierz uczelnię</option>
                                <?php while ($university = mysqli_fetch_assoc($universityInfo)): ?>
                                    <option value="<?php echo $university['ID']; ?>">
                                        <?php echo $university['nazwa']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <!-- Lista uczelni do przypisania -->


                            <!-- Lista przedmiotów do przypisania -->
                            <label>Przedmiot</label>
                            <select name="przedmiot" required>
                                <option disabled selected>Wybierz przedmiot</option>
                                <?php while ($subject = mysqli_fetch_assoc($subjectInfo)): ?>
                                    <option value="<?php echo $subject['ID']; ?>">
                                        <?php echo $subject['nazwa']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <!-- Lista przedmiotów do przypisania -->


                            <!-- Lista studentów do przypisania -->
                            <label>Wybierz studentów</label>
                            <select id="studenci" name="studenci[]" multiple>
                                <?php 
                                // Przechodzimy przez listę studentów
                                while ($student = mysqli_fetch_assoc($studentInfo)): ?>
                                    <option value="<?php echo $student['nr_albumu']; ?>">
                                        <?php echo htmlspecialchars($student['nr_albumu']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <!-- Lista studentów do przypisania -->

                            <label>Nazwa</label>
                            <input type="text" name="nazwa" required>

                            <button type="submit" class="submit-btn">Dodaj grupę</button>
                        </form>
                    </div>
                </div>
                <!-- Okno modalne dodaj Studenta-->
            </div>

            <p>Ilość: <?php echo $studentGroupCount; ?></p>
            <table>
                <thead>
                    <tr>
                        <th>Rok</th>
                        <th>Uczelnia</th>
                        <th>Przedmiot</th>
                        <th>Nazwa</th>
                        <th>Liczba studentów</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($groupData = mysqli_fetch_assoc($studentGroupInfo)): ?>
                        <tr>
                            <td><?php echo $groupData['rok']; ?></td>
                            <td><?php echo $groupData['nazwa_uczelni']; ?></td>
                            <td><?php echo $groupData['przedmiot']; ?></td>
                            <td><?php echo $groupData['nazwa_grupy']; ?></td>
                            <td>
                            <?php 
                                // Wyświetlanie liczby studentów przypisanych do danej grupy
                                $groupId = $groupData['ID'];
                                echo isset($studentCountAtGroup[$groupId]) ? $studentCountAtGroup[$groupId] : 0;
                            ?>
                            </td>

                            <!-- Przyciski "Modyfikuj" -->
                            <td>
                                <a href="edit_group.php?group_id=<?php echo $groupData['ID']; ?>" class="btn-edit">
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

    
    <script src="../../assets/js/modalWindows.js"></script>  
    <script src="../../assets/js/multi_select.js"></script>  

    <!-- multi_select.js --> 
    <script>
        new MultiSelectTag('studenci')  // id
    </script>
    <!-- multi_select.js --> 

</body>
</html>