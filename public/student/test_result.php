<?php
session_start();
require_once('../../config/connect.php');
require_once('../../config/functions.php');

$id_proby = $_GET['id_proby'] ?? null;

if (!$id_proby) {
    die("Brak ID próby.");
}

// Pobierz dane próby z bazy danych
$sql = "SELECT zdobyto_punktow, max_punktow, wynik_procentowy, ocena, data_prob, data_zakonczenia FROM tProbyTestu WHERE ID = '$id_proby'";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);

$data_prob = new DateTime($row['data_prob']);
$data_zakonczenia = new DateTime($row['data_zakonczenia']);
$roznica = $data_prob->diff($data_zakonczenia);
$czas_trwania = $roznica->format('%h godzin, %i minut, %s sekund');


if (!$row) {
    die("Nie znaleziono próby testu.");
}


?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <title>Wyniki testu</title>
    
    <!-- Bootstrap 5.3 css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../../assets/css/main.css">

</head>
<body>
    <main class="main">
        <div class="container card shadow mt-5 text-center">
            <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2 mt-5 mb-5">Test zakończony!</h1>
            <p class="lead">Czas trwania testu: <?= htmlspecialchars($czas_trwania) ?></p>
            <p class="lead">Zdobyłeś: <?= htmlspecialchars($row['zdobyto_punktow']) ?>/<?= htmlspecialchars($row['max_punktow']) ?> punktów</p>
            <p class="lead">Wynik procentowy: <?= round(htmlspecialchars($row['wynik_procentowy'])) ?>%</p>
            <p class="lead">Twoja ocena: <?= htmlspecialchars($row['ocena']) ?></p>
            <br>
            <a class="btn btn-outline-danger mb-5" href="student_dashboard.php">Dalej</a>
        </div>
    </main>
</body>
</html>
