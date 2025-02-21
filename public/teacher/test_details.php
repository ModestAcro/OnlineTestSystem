<?php
session_start();
require_once('../../config/connect.php');
require_once('../../config/functions.php');

$user_id = $_SESSION['user_id'];
$test_id = $_GET['test_id'];



function getTestPointsByStudent($conn, $user_id, $test_id){
    $query = "
    SELECT 
        t.ID AS test_id,
        t.nazwa AS test_nazwa, 
        g.nazwa AS nazwa_grupy, 
        p.nazwa AS nazwa_przedmiotu, 
        k.nazwa AS nazwa_kierunku, 
        s.imie AS imie_studenta, 
        s.nazwisko AS nazwisko_studenta, 
        s.nr_albumu AS numer_albumu,
        pt.zdobyto_punktow AS zdobyto_punktow,
        pt.max_punktow AS max_punktow,
        pt.ocena AS ocena_studenta,
        pt.wynik_procentowy AS wynik_procentowy,
        pt.data_prob AS data_rozpoczecia,
        pt.data_zakonczenia,
        ROW_NUMBER() OVER (PARTITION BY pt.id_testu, pt.id_studenta ORDER BY pt.data_prob) AS numer_proby
    FROM tTesty t
    JOIN tGrupy g ON t.id_grupy = g.ID
    JOIN tGrupyStudenci gs ON t.id_grupy = gs.id_grupy
    JOIN tStudenci s ON gs.id_studenta = s.ID
    JOIN tPrzedmioty p ON t.id_przedmiotu = p.ID
    JOIN tKierunki k ON t.id_kierunku = k.ID
    LEFT JOIN tProbyTestu pt ON t.ID = pt.id_testu
                                AND s.ID = pt.id_studenta
    WHERE t.id_wykladowcy = $user_id
    AND pt.status = 'zakończony'
    AND t.data_zakonczenia <= NOW()
    AND t.ID = $test_id
    ORDER BY s.nr_albumu, t.data_zakonczenia DESC;
    ";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Błąd zapytania: " . mysqli_error($conn));
    }

    return $result;
}

$results = getTestPointsByStudent($conn, $user_id, $test_id);

// Funkcja pomocnicza do sprawdzania NULL
function checkNull($value, $isPercentage = false) {
    if ($value === NULL || $value === '') {
        return 'Brak danych';
    }
    if ($isPercentage) {
        return $value . '%';
    }
    return $value;
}

$previous_student = null;


$colors = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'dark'];
$student_colors = [];

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


    <title>Szczegóły testu</title>
</head>
<body>
    
    <?php include '../../includes/header.php'; ?>

    <main class="container my-5">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Wyniki testu</h1>
        </div>
        
        <div class="row mt-5">
            <?php while ($row = mysqli_fetch_assoc($results)): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card shadow">
                        <div class="card-header bg-danger text-white">
                            <h5 class="mb-0">Student: <?php echo $row['imie_studenta'] . ' ' . $row['nazwisko_studenta']; ?></h5>
                        </div>
                        <div class="card-body">
                            <p><strong>Nr Albumu:</strong> <?php echo checkNull($row['numer_albumu']); ?></p>
                            <p><strong>Punkty:</strong> <?php echo checkNull($row['zdobyto_punktow']); ?> / <?php echo checkNull($row['max_punktow']); ?></p>
                            <p><strong>Ocena:</strong> <?php echo checkNull($row['ocena_studenta']); ?></p>
                            <p><strong>Wynik:</strong> <?php echo checkNull($row['wynik_procentowy'], true); ?></p>
                            <p><strong>Data rozpoczęcia:</strong> <?php echo checkNull($row['data_rozpoczecia']); ?></p>
                            <p><strong>Data zakończenia:</strong> <?php echo checkNull($row['data_zakonczenia']); ?></p>
                            <p><strong>Numer próby:</strong> <?php echo checkNull($row['numer_proby']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        
        <a href="export_excel.php?test_id=<?php echo $test_id; ?>" class="btn btn-outline-danger mt-3">
            Pobierz wyniki (Excel)
        </a>
    </main>
</body>
</html>
