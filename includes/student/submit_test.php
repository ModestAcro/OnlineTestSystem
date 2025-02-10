<?php
session_start();
require_once('../../config/connect.php');
require_once('../../config/functions.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_proby = $_POST['id_proby'] ?? null;
    $odpowiedzi = $_POST['odpowiedzi'] ?? [];

    if (!$id_proby) {
        die("Brak ID próby.");
    }

    // Pobranie `id_testu` oraz `id_studenta` na podstawie `id_proby`
    $sql = "SELECT id_testu, id_studenta FROM tProbyTestu WHERE ID = '$id_proby'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        die("Nie znaleziono próby testu.");
    }

    $id_testu = $row['id_testu'];
    $id_studenta = $row['id_studenta'];

    foreach ($odpowiedzi as $pytanie_id => $odpowiedz_ids) {

        // Pobranie liczby poprawnych odpowiedzi dla pytania
        $sql = "SELECT COUNT(*) AS poprawne_odpowiedzi
                FROM tOdpowiedzi o
                WHERE o.id_pytania = '$pytanie_id' AND o.correct = 1";
        $result = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($result);
        $poprawne_odpowiedzi = $data['poprawne_odpowiedzi'];

        // Walidacja liczby zaznaczonych odpowiedzi w przypadku pytania, które ma więcej niż jedną poprawną odpowiedź
        if (count($odpowiedz_ids) > $poprawne_odpowiedzi) {
            die("Błąd: Zaznaczone odpowiedzi przekraczają liczbę poprawnych odpowiedzi.");
        }

        // Sprawdzanie poprawności zaznaczonych odpowiedzi
        $punkty = 0;
        $incorrect_answer = false; // Flaga do sprawdzania błędnych odpowiedzi

        foreach ($odpowiedz_ids as $odpowiedz_id) {
            // Pobranie treści pytania, poprawności odpowiedzi i punktów
            $sql = "SELECT p.tresc AS tresc_pytania, o.punkty AS punkty_odpowiedzi, o.correct AS czy_poprawna
                    FROM tPytania p
                    JOIN tOdpowiedzi o ON p.ID = o.id_pytania
                    WHERE p.ID = '$pytanie_id' AND o.ID = '$odpowiedz_id'";
            $result = mysqli_query($conn, $sql);
            $data = mysqli_fetch_assoc($result);

            if (!$data) {
                die("Błąd: Nie znaleziono pytania lub odpowiedzi.");
            }

            $tresc_pytania = $data['tresc_pytania'];
            $punkty_pytania = $data['punkty_odpowiedzi'];
            $czy_poprawna = $data['czy_poprawna'];

            // Oblicz punkty tylko za poprawne odpowiedzi
            if ($czy_poprawna) {
                $punkty += $punkty_pytania;
            } else {
                $incorrect_answer = true; // Jeśli odpowiedź jest błędna, ustaw flagę
            }
        }

        // Jeśli są błędne odpowiedzi, przypisz 0 punktów
        if ($incorrect_answer) {
            $punkty = 0;
        }

        // Zapisanie odpowiedzi studenta do bazy
        foreach ($odpowiedz_ids as $odpowiedz_id) {
            $sql = "INSERT INTO tOdpowiedziStudenta (id_proby, id_testu, id_studenta, id_pytania, id_odpowiedzi, tresc, correct, points)
                    VALUES ('$id_proby', '$id_testu', '$id_studenta', '$pytanie_id', '$odpowiedz_id', '$tresc_pytania', '$czy_poprawna', '$punkty')";

            if (!mysqli_query($conn, $sql)) {
                die("Błąd zapisu odpowiedzi: " . mysqli_error($conn));
            }
        }
    }

    // Obliczenie sumy zdobytych punktów
    $sql = "SELECT SUM(points) AS suma_punktow FROM tOdpowiedziStudenta WHERE id_proby = '$id_proby'";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    $suma_punktow = $data['suma_punktow'] ?? 0;

    // Pobranie maksymalnej możliwej liczby punktów w teście
    $sql = "SELECT SUM(o.punkty) AS max_punkty 
            FROM tPytania p
            JOIN tOdpowiedzi o ON p.ID = o.id_pytania
            JOIN tTestPytania tp ON tp.id_pytania = p.ID
            JOIN tTesty t ON t.ID = tp.id_testu
            WHERE t.ID = $id_testu AND o.correct = 1";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    $max_punkty = $data['max_punkty'] ?? 0;

    // Aktualizacja wyniku testu oraz statusu na "zakończony"
    $sql = "UPDATE tProbyTestu 
            SET wynik = '$suma_punktow', status = 'zakończony' 
            WHERE ID = '$id_proby'";

    if (!mysqli_query($conn, $sql)) {
        die("Błąd aktualizacji wyniku testu: " . mysqli_error($conn));
    }

    echo "Test zakończony! Zdobyłeś $suma_punktow/$max_punkty punktów.";
}
?>
