<?php
    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');
    date_default_timezone_set('Europe/Vilnius');

    $student_id = $_SESSION['user_id'] ?? null;

    $studentInfo = getRecordById($conn, 'tStudenci', $student_id);



    function getTestInfo($conn, $student_id){

        $current_date = date('Y-m-d H:i:s');

        $query = "SELECT t.*, 
                    t.nazwa AS nazwa_testu,
                    p.nazwa AS nazwa_przedmiotu, 
                    k.nazwa AS nazwa_kierunku, 
                    w.imie AS imie_wykladowcy, 
                    w.nazwisko AS nazwisko_wykladowcy, 
                    COUNT(DISTINCT tp.ID) AS liczba_pytan,  -- Liczenie unikalnych pytań
                    COUNT(DISTINCT pt.ID) AS liczba_prob   -- Liczenie unikalnych pró
                FROM tTesty t
                JOIN tPrzedmioty p ON t.id_przedmiotu = p.ID
                JOIN tTestPytania tp ON tp.id_testu = t.ID
                JOIN tKierunki k ON t.id_kierunku = k.ID
                JOIN tWykladowcy w ON t.id_wykladowcy = w.ID
                JOIN tGrupy g ON g.ID = t.id_grupy
                JOIN tGrupyStudenci gs ON g.ID = gs.id_grupy
                JOIN tStudenci s ON gs.id_studenta = s.ID
                LEFT JOIN tProbyTestu pt ON pt.id_testu = t.ID AND pt.id_studenta = s.ID
                WHERE s.ID = $student_id
                AND t.data_rozpoczecia <= '$current_date' 
                AND t.data_zakonczenia >= '$current_date'
                GROUP BY t.ID
                HAVING COUNT(DISTINCT pt.ID) < t.ilosc_prob";

    
        $result = mysqli_query($conn, $query);
        return $result;
    }
    
    $testInfo = getTestInfo($conn, $student_id);

    function getStudentTestCount ($conn, $student_id, $test_id){
        $query = "SELECT COUNT(*) AS liczba_prob_studenta
                    FROM tProbytestu
                    WHERE id_testu = $test_id AND id_studenta = $student_id";

        $result = mysqli_query($conn, $query);
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            return $row['liczba_prob_studenta'];
        } else {
            return 0;
        }
    }



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap 5.3 css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../assets/css/main.css">
   
    <title>Panel studenta</title>
</head>
<body>

   <?php include '../../includes/header.php'; ?>


    <main class="main">
    <div class="container">
        <?php if (mysqli_num_rows($testInfo) > 0): ?>
            <div class="tests-box">
                <h1>Testy do wykonania</h1>
                <?php while ($row = mysqli_fetch_assoc($testInfo)): ?>
                    <div class="test_card">
                        <div class="test_title">
                            <div>
                                <label>
                                    <?php 
                                    // Pobieramy dane z bazy danych
                                    $data_rozpoczecia = $row['data_rozpoczecia'];
                                    $data_zakonczenia = $row['data_zakonczenia'];

                                    // Sprawdzamy, czy jedna z dat jest NULL
                                    if ($data_rozpoczecia == NULL || $data_zakonczenia == NULL) {
                                        echo "Nieograniczona";
                                    } else {
                                        echo $data_rozpoczecia . " / " . $data_zakonczenia;
                                    }
                                    ?>
                                </label>
                                <h1><?= htmlspecialchars($row['nazwa_testu']) ?></h1>
                            </div>
                            <div class="test_title-info">
                                <h4><?= htmlspecialchars($row['nazwa_przedmiotu']) ?></h4>
                                <p><?= htmlspecialchars($row['imie_wykladowcy']) . " " . htmlspecialchars($row['nazwisko_wykladowcy']) ?></p>
                            </div>
                        </div>
                        <div class="test-info">
                            <label>Liczba pytań: <?= $row['liczba_pytan'] ?></label>
                            <label>Czas trwania: <?= $row['czas_trwania'] ?> min</label>


                            <?php 
                                    $liczbaProbStudenta = getStudentTestCount($conn, $student_id, $row['ID']);
                                    $pozostale_proby = $row['ilosc_prob'] - $liczbaProbStudenta;
                                ?>

                                <label>Liczba prób: <?= $pozostale_proby ?></label>

                            <div class="test-buttons">
                                <form action="../../includes/student/add_attempt.php" method="POST">
                                    <input type="hidden" name="id_testu" value="<?= $row['ID'] ?>">
                                    <button type="submit" class="start-btn">Rozpocznij test</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>

        <div class="tests-box">
            <h1>Twoje wyniki i postępy</h1>
        </div>
    </div>
</main>



    <!-- Plik JavaScript --> 
    <script src="../../assets/js/modal_windows.js"></script> 

    <script>
        function myFunction() {
        var x = document.getElementById("myLinks");
            if (x.style.display === "block") {
                x.style.display = "none";
            } else {
                x.style.display = "block";
            }
        }
    </script>
</body>
</html>