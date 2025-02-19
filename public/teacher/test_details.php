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

    <main class="main">
        <div class="container">
            <h1>Wyniki testu</h1>
            
            <?php while ($row = mysqli_fetch_assoc($results)): ?>
                <?php
                // Jeśli zmienia się student, generuj nową tabelę
                if ($previous_student !== $row['numer_albumu']):
                    if ($previous_student !== null): ?>
                        </tbody>
                    </table>
                    <hr> <!-- Oddziela tabele różnych studentów -->
                    <?php endif; ?>

                 
                    <table>
                        <thead>
                            <tr>
                                <th>Imię</th>
                                <th>Nazwisko</th>
                                <th>Nr Albumu</th>
                                <th>Punkty</th>
                                <th>Max Punkty</th>
                                <th>Ocena</th>
                                <th>Wynik (%)</th>
                                <th>Data rozpoczęcia</th>
                                <th>Data zakończenia</th>
                                <th>Numer próby</th>
                            </tr>
                        </thead>
                        <tbody>
                <?php endif; ?>

                <tr>
                    <td><?php echo checkNull($row['imie_studenta']); ?></td>
                    <td><?php echo checkNull($row['nazwisko_studenta']); ?></td>
                    <td><?php echo checkNull($row['numer_albumu']); ?></td>
                    <td><?php echo checkNull($row['zdobyto_punktow']); ?></td>
                    <td><?php echo checkNull($row['max_punktow']); ?></td>
                    <td><?php echo checkNull($row['ocena_studenta']); ?></td>
                    <td><?php echo checkNull($row['wynik_procentowy'], true); ?></td>
                    <td><?php echo checkNull($row['data_rozpoczecia']); ?></td>
                    <td><?php echo checkNull($row['data_zakonczenia']); ?></td>
                    <td><?php echo checkNull($row['numer_proby']); ?></td>
                </tr>

                <?php 
                // Zapisz numer albumu dla następnego porównania
                $previous_student = $row['numer_albumu']; 
                endwhile; ?>
            </tbody>
        </table>
        <a href="export_excel.php?test_id=<?php echo $test_id; ?>" class="submit-btn" style="display: block; width: 220px;">Pobierz wyniki (Excel)<img src="../../assets/images/icons/download.svg" class="edit-icon" style="margin-left: 10px;"></a>

    </main>

    <script src="../../assets/js/modal_windows.js"></script>
</body>
</html>
