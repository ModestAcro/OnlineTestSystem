<?php
    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $universitiesInfoResult = getEntityInfo($conn, 'tUczelnie');
    $universitiesCount = getEntityCount($conn, 'tUczelnie');

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
                <a class="nav-btn" href="subjects.php">Przedmioty</a>
                <a class="nav-btn" href="students.php">Studeńci</a>
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
                <h1>Lista uczelni</h1>
                <!-- Przycisk "Dodaj Przedmiot" -->
                <button class="add-btn" onclick="addCharacter()">
                    <img src="../../assets/images/icons/plus.svg" alt="Plus icon" class="add-icon">
                </button>
                <!-- Przycisk "Dodaj Przedmiot" -->

                <!-- Okno modalne dodaj Przedmiot-->
                <div id="addModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" id="addModalClose">&times;</span>
                        <h1 class="modal-header">Dodaj uczelnię</h1>
                        <form action="../../includes/admin/add_universities.php" method="POST">

                            <label for="nazwa">Nazwa</label>
                            <input type="text" id="nazwa" name="nazwa_uczelni" required>

                            <label for="miasto">Miasto</label>
                            <input type="text" id="miasto" name="miasto" required>

                            <label for="kraj">Kraj</label>
                            <input type="text" id="kraj" name="kraj" required>

                            <label for="kontynent">Kontynent</label>
                            <input type="text" id="kontynent" name="kontynent" required>

                            <label for="adres">Adres</label>
                            <input type="text" id="adres" name="adres_uczelni" required>

                            <label for="uwagi">Uwagi</label>
                            <input type="text" id="uwagi" name="uwagi" required>

                            <button type="submit" class="submit-btn">Dodaj uczelnię</button>
                        </form>
                    </div>
                </div>
                <!-- Okno modalne dodaj Studenta-->
            </div>

            <p>Ilość: <?php echo $universitiesCount; ?></p>
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
                    <?php while ($row = mysqli_fetch_assoc($universitiesInfoResult)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nazwa_uczelni']); ?></td>
                            <td><?php echo htmlspecialchars($row['miasto']); ?></td>
                            <td><?php echo htmlspecialchars($row['kraj']); ?></td>
                            <td><?php echo htmlspecialchars($row['kontynent']); ?></td>
                            <td><?php echo htmlspecialchars($row['adres_uczelni']); ?></td>
                            <td><?php echo htmlspecialchars($row['uwagi'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                  
                            <!-- Przyciski "Modyfikuj" -->
                            <td>
                            <button class="btn-edit" onclick="window.location.href='edit_universities.php?id=<?php echo $row['ID']; ?>'">
                                <img src="../../assets/images/icons/edit.svg" alt="Edit City" class="edit-icon">
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