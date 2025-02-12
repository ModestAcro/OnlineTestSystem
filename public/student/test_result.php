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
    <link rel="stylesheet" href="../../assets/css/main.css">
</head>
<body>
    <main class="main">
        <div class="container">
            <h2>Test zakończony!</h2>
            <p>Czas trawania testu: <?= htmlspecialchars($czas_trwania)?></p>

            <p>Zdobyłeś: <?= htmlspecialchars($row['zdobyto_punktow']) ?>/<?= htmlspecialchars($row['max_punktow']) ?> punktów</p>
            <p>Wynik procentowy: <?= round(htmlspecialchars($row['wynik_procentowy'])) ?>%</p>
            <p>Twoja ocena: <?= htmlspecialchars($row['ocena']) ?></p>
        </div>
    </main>
</body>
</html>
