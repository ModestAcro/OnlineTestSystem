<?php 
    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $student_id = $_SESSION['user_id'];

    function getTestCount($conn, $student_id) {
        $query = "SELECT COUNT(DISTINCT t.ID) AS liczba_testow
                  FROM tTesty t
                  JOIN tGrupy g ON t.id_grupy = g.ID
                  JOIN tGrupyStudenci gs ON g.ID = gs.id_grupy
                  JOIN tStudenci s ON gs.id_studenta = s.ID
                  WHERE s.ID = $student_id";
    
        $result = mysqli_query($conn, $query);
    
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['liczba_testow'] ?? 0; // Zwróć liczbę testów lub 0, jeśli brak wyników
        }
    
        return 0; // W razie błędu zwracamy 0
    }

    $testCount = getTestCount($conn, $student_id);



    function getTestInfo($conn, $student_id){
        $query = "SELECT t.*, 
                            t.nazwa AS nazwa_testu,
                            p.nazwa AS nazwa_przedmiotu, 
                            k.nazwa AS nazwa_kierunku, 
                            w.imie AS imie_wykladowcy, 
                            w.nazwisko AS nazwisko_wykladowcy, 
                            COUNT(tp.ID) AS liczba_pytan
                    FROM tTesty t
                    JOIN tPrzedmioty p ON t.id_przedmiotu = p.ID
                    JOIN tTestPytania tp ON tp.id_testu = t.ID
                    JOIN tKierunki k ON t.id_kierunku = k.ID
                    JOIN tWykladowcy w ON t.id_wykladowcy = w.ID
                    JOIN tGrupy g ON g.ID = t.id_grupy
                    JOIN tGrupyStudenci gs ON g.ID = gs.id_grupy
                    JOIN tStudenci s ON gs.id_studenta = s.ID
                    WHERE s.ID = $student_id
                    GROUP BY t.ID";

        $result = mysqli_query($conn, $query);

        return $result;
            
    }

    $testInfo = getTestInfo($conn, $student_id);

    
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Testy</title>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="left-header">
                <a class="nav-btn" href="student_dashboard.php">Strona główna</a>
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
            </div>

            <p>Ilość: <?php echo $testCount; ?></p>
            <table>
                <thead>
                    <tr>
                        <th>Przedmiot</th>
                        <th>Test</th>
                        <th>Liczba pytań</th>
                        <th>Data ważności</th>
                        <th>Czas trwania (min.)</th>
                        <th>Ilość prób</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($testData = mysqli_fetch_assoc($testInfo)): ?>
                        <tr>
                            <td><?php echo $testData['nazwa_przedmiotu']; ?></td>

                            <td><?php echo $testData['nazwa_testu']; ?></td>

                            <td><?php echo $testData['liczba_pytan']; ?></td>

                            <td>
                                <?php
                                    $start = $testData['data_rozpoczecia'] ? date('Y-m-d', strtotime($testData['data_rozpoczecia'])) : 'Brak';
                                    $end = $testData['data_zakonczenia'] ? date('Y-m-d', strtotime($testData['data_zakonczenia'])) : 'Brak';
                                    echo "$start / $end";
                                ?>
                            </td>

                            <td><?php echo $testData['czas_trwania']; ?></td>
                            <td>
                                <?php 
                                    echo $testData['ilosc_prob'] == -1 ? 'Nieograniczona' : $testData['ilosc_prob'];
                                ?>
                            </td>

                             
                        </tr>
                    <?php endwhile; ?> 
                </tbody>
            </table>
        </div>
    </main>    




    <script src="../../assets/js/modal_windows.js"></script>  
</body>
</html>