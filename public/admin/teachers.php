<?php

    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $teacherInfo = getTableInfo($conn, 'tWykladowcy'); 
    $teacherCount = getTableCount($conn, 'tWykladowcy');

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
        <div class="header-content">
            <div class="left-header">
                <a class="nav-btn" href="admin_dashboard.php">Strona główna</a>
                <a class="nav-btn" href="students.php">Studeńci</a>
                <a class="nav-btn" href="subjects.php">Przedmioty</a>
                <a class="nav-btn" href="universities.php">Uczelnie</a>
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
                <h1>Lista wykładowców</h1>

                <!-- Przycisk "Dodaj Wykładowę" -->
                <button class="add-btn" onclick="addEntity()">
                    <img src="../../assets/images/icons/plus.svg" class="add-icon">
                </button>
                <!-- Przycisk "Dodaj Wykładowę" -->

                <!-- Okno modalne dodaj Wykładowcę -->
                <div id="openModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" id="closeModal">&times;</span>
                        <h1 class="modal-header">Dodaj wykładowcę</h1>
                        <form action="../../includes/admin/add_teacher.php" method="POST">

                            <label>Imię</label>
                            <input type="text" name="imieWykladowcy" required>

                            <label>Nazwisko</label>
                            <input type="text" name="nazwiskoWykladowcy" required>

                            <label>Email</label>
                            <input type="email" name="emailWykladowcy" required>

                            <label>Hasło</label>
                            <input type="password" name="hasloWykladowcy" required>

                            <label>Uwagi</label>
                            <textarea name="uwagiWykladowcy"></textarea>

                            <button type="submit" class="submit-btn">Dodaj wykładowcę</button>
                        </form>
                    </div>
                </div>
                <!-- Okno modalne dodaj Wykładowcę-->
            </div>

            <p>Ilość: <?php echo $teacherCount; ?></p>
            <table>
                <thead>
                    <tr>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Uwagi</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($teacherData = mysqli_fetch_assoc($teacherInfo)): ?>
                        <tr>
                            <td><?php echo ($teacherData['imie']); ?></td>
                            <td><?php echo ($teacherData['nazwisko']); ?></td>
                            <td><?php echo ($teacherData['email']); ?></td>
                            <td class="<?php echo $teacherData['aktywny'] == 'T' ? 'active' : 'inactive'; ?>"> <!-- Ustawiamy klasę dla styli CSS -->
                                <?php echo $teacherData['aktywny'] == 'T' ? 'Aktywny' : 'Nieaktywny'; ?> <!-- Wyświetlamy do tabeli -->
                            </td>
                            <td><?php echo ($teacherData['uwagi']); ?></td>

                            <!-- Przyciski "Modyfikuj" -->
                            <td>
                                <a href="edit_teacher.php?teacher_id=<?php echo $teacherData['ID']; ?>" class="btn-edit">
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
    <script src="../../assets/js/modalWindows.js"></script>  
</body>
</html>