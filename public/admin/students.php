<?php
    session_start();
    require_once('../../config/connect.php');

    // Zapytanie do bazy danych o nauczycieli
    $studentInfoQuery = "SELECT ID, nr_albumu, imie, nazwisko, email, aktywny, uwagi FROM tStudenci";
    $studentInfoResult = mysqli_query($conn, $studentInfoQuery);

    // Liczenie liczby nauczycieli
    $studentCountQuery = "SELECT COUNT(*) AS teacherCount FROM tStudenci";
    $studentCountResult = mysqli_query($conn, $studentCountQuery);
    $studentCount = mysqli_fetch_assoc($studentCountResult)['teacherCount'];
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
            </div>
            <div class="right-header">
                <span class="name"><?php echo $_SESSION['imie'] . ' ' . $_SESSION['nazwisko']; ?></span>

                <!-- Formularz wylogowania -->
                <form action="../../config/logout.php" method="POST">
                    <button type="submit" class="logout-btn">Wyloguj</button>
                </form>
            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <div class="title">
                <h1>Lista Studentów</h1>
                <!-- Przycisk "Dodaj Studenta" -->
                <button class="add-btn" onclick="addCharacter()">
                    <img src="../../assets/images/icons/plus.svg" alt="Plus icon" class="add-icon">
                </button>

                <!-- Okno modalne dodaj Studenta-->
                <div id="addModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" id="addModalClose">&times;</span>
                        <h1>Dodaj Studenta</h1>
                        <form action="../../includes/admin/add_student.php" method="POST">

                            <label for="nr_albumu">Nr. albumu</label>
                            <input type="text" id="imie" name="nrAlbumuStudenta" required>

                            <label for="imie">Imię</label>
                            <input type="text" id="imie" name="imieStudenta" required>

                            <label for="nazwisko">Nazwisko</label>
                            <input type="text" id="nazwisko" name="nazwiskoStudenta" required>

                            <label for="email">Email</label>
                            <input type="email" id="email" name="emailStudenta" required>

                            <label for="haslo">Hasło</label>
                            <input type="password" id="haslo" name="hasloStudenta" required>

                            <label for="uwagi">Uwagi</label>
                            <textarea id="uwagi" name="uwagiStudenta"></textarea>

                            <button type="submit" class="submit-btn">Dodaj studenta</button>
                        </form>
                    </div>
                </div>
                <!-- Okno modalne dodaj Studenta-->
            </div>

            <p class="teacher-count">Ilość: <?php echo $studentCount; ?></p>
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
                    <?php while ($row = mysqli_fetch_assoc($studentInfoResult)): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['nr_albumu']); ?></td>
                            <td><?php echo htmlspecialchars($row['imie']); ?></td>
                            <td><?php echo htmlspecialchars($row['nazwisko']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="<?php echo $row['aktywny'] == 'T' ? 'active' : 'inactive'; ?>">
                                <?php echo $row['aktywny'] == 'T' ? 'Aktywny' : 'Nieaktywny'; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['uwagi']); ?></td>
                            <!-- Przyciski "Modyfikuj" -->
                            <td>
                            <button class="btn-edit" onclick="window.location.href='edit_student.php?id=<?php echo $row['ID']; ?>'">
                                <img src="../../assets/images/icons/edit.svg" alt="Edit Student" class="edit-icon">
                            </button>
                            </td>
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