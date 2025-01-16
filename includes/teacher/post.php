<?php
// Sprawdzamy, czy dane zostały przesłane metodą POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo '<h1>Dane przesłane przez POST:</h1>';
    echo '<pre>';
    print_r($_POST); // Wyświetla wszystkie dane z tablicy $_POST
    echo '</pre>';
} else {
    echo '<h1>Brak danych w żądaniu POST.</h1>';
}
?>
