<?php 
    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $student_id = $_SESSION['user_id'];

    function getTestCount($conn, $student_id) {
        // Prepare the SQL query to count the number of tests for the given student
        $query = "SELECT COUNT(DISTINCT id_testu) AS liczba_testow
                  FROM tProbyTestu
                  WHERE id_studenta = $student_id";
    
        // Execute the query
        $result = mysqli_query($conn, $query);
    
        // Check if the query was successful
        if ($result) {
            // Fetch the result as an associative array
            $row = mysqli_fetch_assoc($result);
    
            // Return the count of tests, defaulting to 0 if no tests are found
            return $row['liczba_testow'] ?? 0;
        }
    
        // Return 0 if the query failed
        return 0;
    }

    $testCount = getTestCount($conn, $student_id);


    // Funkcja ktora filtruje najleprzy test po wynik_procentowy + data_prob
    function getTestInfo($conn, $student_id){
        $query = "SELECT t.*, 
                        t.nazwa AS nazwa_testu,
                        p.nazwa AS nazwa_przedmiotu, 
                        k.nazwa AS nazwa_kierunku, 
                        w.imie AS imie_wykladowcy, 
                        w.nazwisko AS nazwisko_wykladowcy, 
                        COUNT(tp.ID) AS liczba_pytan,
                        pt.status AS status_testu,
                        pt.zdobyto_punktow, 
                        pt.max_punktow, 
                        pt.ocena, 
                        pt.wynik_procentowy, 
                        pt.data_prob, 
                        pt.data_zakonczenia
                    FROM tTesty t
                    JOIN tPrzedmioty p ON t.id_przedmiotu = p.ID
                    JOIN tTestPytania tp ON tp.id_testu = t.ID
                    JOIN tKierunki k ON t.id_kierunku = k.ID
                    JOIN tWykladowcy w ON t.id_wykladowcy = w.ID
                    JOIN tGrupy g ON g.ID = t.id_grupy
                    JOIN tGrupyStudenci gs ON g.ID = gs.id_grupy
                    JOIN tStudenci s ON gs.id_studenta = s.ID
                    JOIN tProbyTestu pt ON pt.id_testu = t.ID AND pt.id_studenta = s.ID
                    WHERE s.ID = $student_id 
                    AND pt.status = 'zakończony'
                    AND pt.wynik_procentowy = (
                        SELECT MAX(wynik_procentowy)
                        FROM tProbyTestu
                        WHERE id_testu = t.ID AND id_studenta = s.ID AND status = 'zakończony'
                    )
                    AND pt.data_prob = (
                        SELECT MAX(data_prob)
                        FROM tProbyTestu
                        WHERE id_testu = t.ID AND id_studenta = s.ID AND status = 'zakończony'
                        AND wynik_procentowy = (
                            SELECT MAX(wynik_procentowy)
                            FROM tProbyTestu
                            WHERE id_testu = t.ID AND id_studenta = s.ID AND status = 'zakończony'
                        )
                    )
                    GROUP BY t.ID, t.nazwa, p.nazwa, k.nazwa, w.imie, w.nazwisko, pt.status, pt.zdobyto_punktow, pt.max_punktow, pt.ocena, pt.wynik_procentowy, pt.data_prob, pt.data_zakonczenia;
                    ";
       
 

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
   
    <!-- Bootstrap 5.3 css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../assets/css/main.css">

    <title>Testy</title>
</head>
<body>

    <?php include '../../includes/header.php'; ?>

    <main class="main">
        <div class="container mt-5">
            <div class="title mb-5">
                <h1 class="fs-2 fs-md-3 fs-lg-5">Rozwiązane testy</h1>
                <p>Ilość: <?php echo $testCount; ?></p>
            </div>

            <div class="row">
                <?php while ($testData = mysqli_fetch_assoc($testInfo)): ?>
                    <?php 
                        $test_id = $testData['ID']; 
                        $start_time = strtotime($testData['data_prob']);
                        $end_time = strtotime($testData['data_zakonczenia']);
                        
                        $startDateTime = new DateTime();
                        $startDateTime->setTimestamp($start_time);
                        
                        $endDateTime = new DateTime();
                        $endDateTime->setTimestamp($end_time);
                        
                        $interval = $startDateTime->diff($endDateTime);

                        $wynikProcentowy = $testData['wynik_procentowy'];
                    ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title fs-4 mt-2"><?php echo $testData['nazwa_testu']; ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo $testData['nazwa_przedmiotu']; ?></h6>
                            </div>
                            <div class="card-body">
                                <p><strong>Czas rozwiązania:</strong> <?php echo $interval->format('%h godzin %i minut %s sekund'); ?></p>
                                <p><strong>Wynik:</strong> <?php echo $wynikProcentowy . "%"; ?></p>
                                <p><strong>Ocena:</strong> <?php echo $testData['ocena']; ?></p>
                                <p><strong>Status:</strong> 
                                    <?php 
                                        if($wynikProcentowy >= 51){
                                            echo "<span class='badge bg-success'>Zaliczony</span>";
                                        } else {
                                            echo "<span class='badge bg-danger'>Niezaliczony</span>";
                                        }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>

</body>
</html>