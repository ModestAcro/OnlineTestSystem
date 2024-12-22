<?php
    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $characterId = $_SESSION['ID'];

    // Zapytanie SQL, które filtruje grupy według ID wykładowcy
    $studentGroupInfoResult = getEntityInfoByCharacter($conn, 'tGrupy', $characterId);
    $studentGroupCount = getEntityCountByCharacter($conn, 'tGrupy', $characterId); // Liczba grup

    $studentList = getEntityInfo($conn, 'tStudenci'); 
    $subjectInfo = getEntityInfo($conn, 'tPrzedmioty');
    $universityInfo = getEntityInfo($conn, 'tUczelnie');

    // Wywołanie funkcji do zliczania studentów w grupach
    $studentCountData = getStudentCountByGroup($conn, $characterId);

    $studentGroupInfoQuery = "
    SELECT g.ID, g.rok, g.nazwa, u.nazwa, p.nazwa AS przedmiot 
    FROM tGrupy g
    JOIN tUczelnie u ON g.id_uczelni = u.ID
    JOIN tPrzedmioty p ON g.id_przedmiotu = p.ID
    WHERE g.id_wykladowcy = '$characterId'
    ";

    $studentGroupInfoResult = mysqli_query($conn, $studentGroupInfoQuery);


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
            <div class="title">
                <h1>Lista grup studentów</h1>
                <!-- Przycisk "Dodaj Grupę" -->
                <button class="add-btn" onclick="addCharacter()">
                    <img src="../../assets/images/icons/plus.svg" alt="Plus icon" class="add-icon">
                </button>
                <!-- Przycisk "Dodaj Grupę" -->

                <!-- Okno modalne dodaj gupę studentów-->
                <div id="addModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" id="addModalClose">&times;</span>
                        <h1 class="modal-header">Dodaj Grupę</h1>
                        <form action="../../includes/teacher/add_group.php" method="POST">

                            <label for="nr_albumu">Rok</label>
                            <input type="text" pattern="\d{4}" id="rok" name="rok" required>

                             <!-- Lista uczleni do przypisania -->
                            <label for="uczelnia">Uczelnia</label>
                            <select id="uczelnia" name="uczelnia" required>
                                <option value="" disabled selected>Wybierz uczelnię</option>
                                <?php while ($university = mysqli_fetch_assoc($universityInfo)): ?>
                                    <option value="<?php echo $university['ID']; ?>">
                                        <?php echo htmlspecialchars($university['nazwa']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <!-- Lista uczelni do przypisania -->


                            <!-- Lista przedmiotów do przypisania -->
                            <label for="przedmiot">Przedmiot</label>
                            <select id="przedmiot" name="przedmiot" required>
                                <option value="" disabled selected>Wybierz przedmiot</option>
                                <?php while ($subject = mysqli_fetch_assoc($subjectInfo)): ?>
                                    <option value="<?php echo $subject['ID']; ?>">
                                        <?php echo htmlspecialchars($subject['nazwa']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <!-- Lista przedmiotów do przypisania -->



                            <!-- Lista studentów do przypisania -->
                            <label for="studenci">Wybierz studentów</label>
                            <select id="studenci" name="studenci[]" multiple>
                                <?php 
                                // Przechodzimy przez listę studentów
                                while ($student = mysqli_fetch_assoc($studentList)): ?>
                                    <option value="<?php echo $student['nr_albumu']; ?>">
                                        <?php echo htmlspecialchars($student['nr_albumu']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <!-- Lista studentów do przypisania -->

                            <label for="nazwa">Nazwa</label>
                            <input type="text" id="nazwa" name="nazwa" required>

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
                    <?php while ($row = mysqli_fetch_assoc($studentGroupInfoResult)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['rok']); ?></td>
                            <td><?php echo htmlspecialchars($row['nazwa']); ?></td>
                            <td><?php echo htmlspecialchars($row['przedmiot']); ?></td>
                            <td><?php echo htmlspecialchars($row['nazwa']); ?></td>
                            <td>
                            <?php 
                                // Wyświetlanie liczby studentów przypisanych do danej grupy
                                $groupId = $row['ID'];
                                echo isset($studentCountData[$groupId]) ? $studentCountData[$groupId] : 0;
                            ?>
                            </td>
                            <!-- Przyciski "Modyfikuj" -->
                            <td>
                            <button class="btn-edit" onclick="window.location.href='edit_group.php?id=<?php echo $row['ID']; ?>'">
                                <img src="../../assets/images/icons/edit.svg" alt="Edit Student" class="edit-icon">
                            </button>
                            </td>
                            <!-- Przyciski "Modyfikuj" -->
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>    

    
    <script src="../../assets/js/admin/modalWindows.js"></script>  
    <script src="../../assets/js/multi_select.js"></script>  

    <!-- multi_select.js --> 
    <script>
        new MultiSelectTag('studenci')  // id
    </script>
    <!-- multi_select.js --> 

</body>
</html>