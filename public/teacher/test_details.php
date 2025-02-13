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
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Szczegóły testu</title>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="left-header">
                <a class="nav-btn" href="completed_tests.php">Wyniki testów</a>
            </div>
            <div class="right-header">
                <span class="name"><?php echo $_SESSION['user_name'] . ' ' . $_SESSION['user_surname']; ?></span>
                <?php include('../../includes/logout_modal.php'); ?>
            </div>
        </div>
    </header>

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
    </main>

    <script src="../../assets/js/modal_windows.js"></script>
</body>
</html>
