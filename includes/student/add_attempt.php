<?php
session_start();
require_once('../../config/connect.php');
date_default_timezone_set('Europe/Vilnius');


$test_id = $_POST['id_testu']; // ID testu pobrane z URL
$student_id = $_SESSION['user_id'] ?? null;


// Pobranie maksymalnej liczby prób dla danego testu
$sql_max_proby = "SELECT ilosc_prob FROM tTesty WHERE ID = '$test_id'";
$result_max_proby = mysqli_query($conn, $sql_max_proby);
$row_max_proby = mysqli_fetch_assoc($result_max_proby);
$max_proby = $row_max_proby['ilosc_prob'];

// Pobranie liczby prób wykonanych przez studenta dla tego testu
$sql_wykonane_proby = "SELECT COUNT(*) AS wykonane FROM tProbyTestu WHERE id_testu = '$test_id' AND id_studenta = '$student_id'";
$result_wykonane_proby = mysqli_query($conn, $sql_wykonane_proby);
$row_wykonane_proby = mysqli_fetch_assoc($result_wykonane_proby);
$wykonane_proby = $row_wykonane_proby['wykonane'];

// Sprawdzenie, czy student osiągnął limit prób
if ($wykonane_proby >= $max_proby) {
    die("Osiągnięto maksymalną liczbę prób dla tego testu.");
}

// Tworzymy nową próbę testu
$sql = "INSERT INTO tProbyTestu (id_testu, id_studenta, status) VALUES ('$test_id', '$student_id', 'w trakcie')";

if (mysqli_query($conn, $sql)) {
    // Pobranie ID ostatnio dodanej próby
    $id_proby = mysqli_insert_id($conn);

    // Pobranie daty rozpoczęcia próby
    $sql_data_prob = "SELECT data_prob FROM tProbyTestu WHERE ID = '$id_proby'";
    $result_data_prob = mysqli_query($conn, $sql_data_prob);
    $row_data_prob = mysqli_fetch_assoc($result_data_prob);

    // Ustawienie daty rozpoczęcia w sesji
    $_SESSION['data_prob'] = $row_data_prob['data_prob'];

    // Przekierowanie do testu z nową próbą
    header("Location: ../../public/student/rozpocznij_test.php?id_proby=" . $id_proby);
    exit;
} else {
    echo "Błąd: " . mysqli_error($conn);
}

?>
