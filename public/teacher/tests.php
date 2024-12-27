<?php

    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $userId = $_SESSION['user_id'];

    // Pobranie grup związanych z nauczycielem
    $groups = getGroupsByTeacher($conn, $userId);

    // Pobiera liczbę testów związanych z nauczycielem
    $testCount = getTestCountForTeacher($conn, $userId); 




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
                <h1>Lista testów</h1>

                <!-- Przycisk "Dodaj Test" -->
                <button class="add-btn" onclick="addEntity()">
                    <img src="../../assets/images/icons/plus.svg" alt="Plus icon" class="add-icon">
                </button>
                <!-- Przycisk "Dodaj Test" -->

                <!-- Okno modalne dodaj test-->
                <div id="openModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" id="closeModal">&times;</span>
                        <h1 class="modal-header">Dodaj Test</h1>
                        <form action="../../includes/teacher/add_group.php" method="POST">


                           <!-- Lista grup do przypisania -->
                        <?php if (!empty($groups)): ?>
                            <label>Wybierz grupę</label>
                            <select name="grupa" required>
                                <option disabled selected>Wybierz grupę</option>
                                <?php foreach ($groups as $group): ?>
                                    <option value="<?php echo $group['grupa_id']; ?>">

                                    <?php
                                        // Liczba studentów
                                        $studentCount = $group['liczba_studentow'];

                                        // Określenie końcówki
                                        if ($studentCount == 1) {
                                            $studentText = "student";
                                        } else {
                                            $studentText = "studentów";
                                        }

                                        // Wyświetlanie tekstu z odpowiednią końcówką
                                        echo $group['rok'] . " - " . $group['uczelnia'] . " - " . $group['przedmiot'] . " - " . $group['grupa_nazwa'] . " (" . $studentCount . " " . $studentText . ")";
                                    ?>

                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php else: ?>
                            <p>Brak dostępnych grup</p>
                        <?php endif; ?>
                        <!-- Lista grup do przypisania -->


                            <label>Nazwa</label>
                            <input type="text" name="nazwa" required>

                            <button type="submit" class="submit-btn">Dodaj test</button>
                        </form>
                    </div>
                </div>
                <!-- Okno modalne dodaj test-->
            </div>

            <p>Ilość: <?php echo $testCount; ?></p>
            <table>
                <thead>
                    <tr>
                        <th>Rok</th>
                        <th>Uczelnia</th>
                        <th>Przedmiot</th>
                        <th>Nazwa</th>
                        <th>Grupa</th>
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

    
    <script src="../../assets/js/modal_windows.js"></script>  
    <script src="../../assets/js/multi_select.js"></script>  

    <!-- multi_select.js --> 
    <script>
        new MultiSelectTag('studenci')  // id
    </script>
    <!-- multi_select.js --> 

</body>
</html>