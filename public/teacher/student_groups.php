<?php
    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $studentGroupInfoResult = getEntityInfo($conn, 'tGrupyStudentow');
    $studentGroupCount = getEntityCount($conn, 'tGrupyStudentow');

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

                <!-- Okno modalne dodaj Studenta-->
                <div id="addModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" id="addModalClose">&times;</span>
                        <h1 class="modal-header">Dodaj Grupę</h1>
                        <form action="../../includes/teacher/add_group.php" method="POST">

                            <label for="nr_albumu">Rok</label>
                            <input type="text" pattern="\d{4}" id="rok" name="rok" required>

                            <label for="miasto">Miasto</label>
                            <input type="text" id="miasto" name="miasto" required>

                            <label for="przedmiot">Przedmiot</label>
                            <input type="text" id="przedmiot" name="przedmiot" required>

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
                        <th>Miasto</th>
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
                            <td><?php echo htmlspecialchars($row['miasto']); ?></td>
                            <td><?php echo htmlspecialchars($row['przedmiot']); ?></td>
                            <td><?php echo htmlspecialchars($row['nazwa']); ?></td>
                            <td></td>
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
    
    <!-- Plik JavaScript --> 
    <script src="../../assets/js/admin/modalWindows.js"></script>  
</body>
</html>