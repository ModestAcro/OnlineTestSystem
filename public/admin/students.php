<?php

    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $studentInfo = getTableInfo($conn, 'tStudenci');
    $studentCount = getTableCount($conn, 'tStudenci');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Studeńci</title>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="left-header">
                <a class="nav-btn" href="admin_dashboard.php">Strona główna</a>
                <a class="nav-btn" href="teachers.php">Wykładowcy</a>
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
                <h1>Lista Studentów</h1>

                <!-- Przycisk "Dodaj Studenta" -->
                <button class="add-btn" onclick="addEntity()">
                    <img src="../../assets/images/icons/plus.svg" class="add-icon">
                </button>
                <!-- Przycisk "Dodaj Studenta" -->

                <!-- Okno modalne dodaj Studenta-->
                <div id="openModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" id="closeModal">&times;</span>
                        <h1 class="modal-header">Dodaj Studenta</h1>
                        <form action="../../includes/admin/add_student.php" method="POST">

                            <label>Nr. albumu</label>
                            <input type="text" name="nrAlbumuStudenta" required>

                            <label>Imię</label>
                            <input type="text" name="imieStudenta" required>

                            <label>Nazwisko</label>
                            <input type="text" name="nazwiskoStudenta" required>

                            <label>Email</label>
                            <input type="email" name="emailStudenta" required>

                            <label>Hasło</label>
                            <input type="password" name="hasloStudenta" required>

                            <label>Uwagi</label>
                            <textarea name="uwagiStudenta"></textarea>

                            <button type="submit" class="submit-btn">Dodaj studenta</button>
                        </form>
                    </div>
                </div>
                <!-- Okno modalne dodaj Studenta-->
            </div>

            <p>Ilość: <?php echo $studentCount; ?></p>
            <table>
                <thead>
                    <tr>
                        <th>Nr. albumu</th>
                        <th>Imię</th>
                        <th>Nazwisko</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Uwagi</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($studentData = mysqli_fetch_assoc($studentInfo)): ?>
                        <tr>
                            <td><?php echo $studentData['nr_albumu']; ?></td>
                            <td><?php echo $studentData['imie']; ?></td>
                            <td><?php echo $studentData['nazwisko']; ?></td>
                            <td><?php echo $studentData['email']; ?></td>
                            <td class="<?php echo $studentData['aktywny'] == 'T' ? 'active' : 'inactive'; ?>">
                                <?php echo $studentData['aktywny'] == 'T' ? 'Aktywny' : 'Nieaktywny'; ?>
                            </td>
                            <td><?php echo $studentData['uwagi']; ?></td>

                            <!-- Przyciski "Modyfikuj" -->
                            <td>
                                <a href="edit_student.php?student_id=<?php echo $studentData['ID']; ?>" class="btn-edit">
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