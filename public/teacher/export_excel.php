<?php
session_start();
require_once('../../config/connect.php');
require_once '../../vendor/autoload.php'; 

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$user_id = $_SESSION['user_id'];
$test_id = $_GET['test_id'];

function getTestResults($conn, $user_id, $test_id) {
    $query = "
    SELECT 
        s.imie AS imie_studenta, 
        s.nazwisko AS nazwisko_studenta, 
        s.nr_albumu AS numer_albumu,
        pt.zdobyto_punktow, 
        pt.max_punktow, 
        pt.ocena, 
        pt.wynik_procentowy, 
        pt.data_prob AS data_rozpoczecia,
        pt.data_zakonczenia,
        ROW_NUMBER() OVER (PARTITION BY pt.id_testu, pt.id_studenta ORDER BY pt.data_prob) AS numer_proby
    FROM tTesty t
    JOIN tGrupy g ON t.id_grupy = g.ID
    JOIN tGrupyStudenci gs ON t.id_grupy = gs.id_grupy
    JOIN tStudenci s ON gs.id_studenta = s.ID
    LEFT JOIN tProbyTestu pt ON t.ID = pt.id_testu AND s.ID = pt.id_studenta
    WHERE t.id_wykladowcy = $user_id
    AND pt.status = 'zakończony'
    AND t.ID = $test_id
    ORDER BY s.nr_albumu, t.data_zakonczenia DESC;
    ";

    $result = mysqli_query($conn, $query);
    if (!$result) {
        die("Błąd zapytania: " . mysqli_error($conn));
    }

    return $result;
}

$results = getTestResults($conn, $user_id, $test_id);

// Pobierz wszystkie wyniki do tablicy
$allResults = [];
while ($row = mysqli_fetch_assoc($results)) {
    $allResults[] = $row;
}

// Dla każdego studenta określ najlepszy wynik (najwyższy 'wynik_procentowy')
$bestIndexes = [];
foreach ($allResults as $i => $row) {
    $album = $row['numer_albumu'];
    // Porównujemy wyniki jako liczby zmiennoprzecinkowe
    if (!isset($bestIndexes[$album]) || floatval($row['wynik_procentowy']) > floatval($allResults[$bestIndexes[$album]]['wynik_procentowy'])) {
        $bestIndexes[$album] = $i;
    }
}

// Tworzenie arkusza Excel
$spreadsheet = new Spreadsheet();

// Arkusz 1: Wszystkie próby
$sheet1 = $spreadsheet->createSheet(0);
$sheet1->setTitle('Wszystkie próby');

// Nagłówki
$headers1 = ['Imię', 'Nazwisko', 'Nr Albumu', 'Punkty', 'Wynik (%)', 'Ocena', 'Numer próby'];
$sheet1->fromArray([$headers1], NULL, 'A1');

// Ustawienia stylu nagłówków
$headerStyle = [
    'font' => ['bold' => true, 'size' => 32, 'color' => ['rgb' => 'FFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '4F81BD']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
];
$sheet1->getStyle('A1:G1')->applyFromArray($headerStyle);


// Styl danych (dla pozostałych wierszy)
$dataStyle = [
    'font' => ['size' => 24],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_LEFT,
        'vertical' => Alignment::VERTICAL_CENTER,
    ],
];

// Styl dla najlepszego wyniku 
$bestStyle = [
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => 'C6EFCE'], // jasnozielony
    ],
];

$rowIndex = 2;
foreach ($allResults as $i => $row) {
    $sheet1->fromArray([
        $row['imie_studenta'],
        $row['nazwisko_studenta'],
        $row['numer_albumu'],
        $row['zdobyto_punktow'] . " / " . $row['max_punktow'],
        $row['wynik_procentowy'] . '%',
        $row['ocena'],
        $row['numer_proby']
    ], NULL, "A{$rowIndex}");
    
    // Zastosuj domyślny styl danych dla wiersza
    $sheet1->getStyle("A{$rowIndex}:G{$rowIndex}")->applyFromArray($dataStyle);
    
    // Jeśli bieżący rekord jest najlepszym wynikiem dla studenta, zaznacz go (zielonym tłem)
    if (isset($bestIndexes[$row['numer_albumu']]) && $bestIndexes[$row['numer_albumu']] == $i) {
         $sheet1->getStyle("A{$rowIndex}:G{$rowIndex}")->applyFromArray($bestStyle);
    }
    
    $rowIndex++;
}

// Automatyczne dopasowanie szerokości kolumn
foreach (range('A', 'G') as $columnID) {
    $sheet1->getColumnDimension($columnID)->setAutoSize(true);
}

// Ramki dla całej tabeli
$sheet1->getStyle("A1:G" . ($rowIndex - 1))->applyFromArray([
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
]);

// Arkusz 2: Najlepsze wyniki
$sheet2 = $spreadsheet->createSheet(1);
$sheet2->setTitle('Najlepsze wyniki');

$headers2 = ['Nr Albumu', 'Punkty', 'Wynik (%)', 'Ocena', 'Numer próby'];
// Nagłówki
$sheet2->fromArray([$headers2], NULL, 'A1');

// Zastosowanie stylu nagłówków
$sheet2->getStyle('A1:E1')->applyFromArray($headerStyle);

// Debugowanie: Sprawdzenie, czy bestIndexes jest prawidłowe
// echo "<pre>";
// print_r($bestIndexes);
// echo "</pre>";

$rowIndex = 2;
foreach ($bestIndexes as $album => $bestIndex) {
    $row = $allResults[$bestIndex]; // Pobieramy najlepszy wynik dla danego albumu

    $sheet2->fromArray([
        $row['numer_albumu'],
        $row['zdobyto_punktow'] . " / " . $row['max_punktow'],
        $row['wynik_procentowy'] . '%',
        $row['ocena'],
        $row['numer_proby']
    ], NULL, "A{$rowIndex}");
    
    // Zastosowanie stylu danych
    $sheet2->getStyle("A{$rowIndex}:E{$rowIndex}")->applyFromArray($dataStyle);

    // Dodajemy style dla najlepszego wyniku (zielone tło)
    $sheet2->getStyle("A{$rowIndex}:E{$rowIndex}")->applyFromArray($bestStyle);

    $rowIndex++; // Przechodzimy do kolejnego wiersza
}

// Automatyczne dopasowanie szerokości kolumn
foreach (range('A', 'E') as $columnID) {
    $sheet2->getColumnDimension($columnID)->setAutoSize(true);
}

// Ramki dla całej drugiej tabeli
$sheet2->getStyle("A1:E" . ($rowIndex - 1))->applyFromArray([
    'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
]);

// Pobieranie pliku
$filename = "Wyniki_testu_$test_id.xlsx";
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=$filename");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
?>
