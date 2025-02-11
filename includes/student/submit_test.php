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

    $suma_punktow = 0; // Zmienna na sumę punktów

    foreach ($odpowiedzi as $pytanie_id => $odpowiedz_ids) {

        // Pobieramy liczbę poprawnych odpowiedzi dla danego pytania
        $sql = "SELECT COUNT(*) AS poprawne_odpowiedzi
                FROM tOdpowiedzi o
                WHERE o.id_pytania = '$pytanie_id' AND o.correct = 1";
        $result = mysqli_query($conn, $sql);
        $data = mysqli_fetch_assoc($result);
        $poprawne_odpowiedzi = $data['poprawne_odpowiedzi'];

        // Liczba odpowiedzi zaznaczonych przez użytkownika
        $zaznaczone_odpowiedzi = count($odpowiedz_ids);

        if ($zaznaczone_odpowiedzi > $poprawne_odpowiedzi) {
            $punkty = 0; // Jeśli zaznaczone odpowiedzi są większe niż poprawne, punkty = 0
        } else {
            $punkty = 0; // Resetowanie punktów dla tego pytania
            foreach ($odpowiedz_ids as $odpowiedz_id) {
                $sql = "SELECT o.punkty AS punkty_odpowiedzi, o.correct AS czy_poprawna
                        FROM tOdpowiedzi o
                        WHERE o.ID = '$odpowiedz_id'";
                $result = mysqli_query($conn, $sql);
                $data = mysqli_fetch_assoc($result);

                $czy_poprawna = $data['czy_poprawna'];
                $punkty_pytania = $data['punkty_odpowiedzi'];

                if ($czy_poprawna == 1) {
                    $punkty += $punkty_pytania; // Dodaj punkty za poprawną odpowiedź
                }
            }
        }

        // Zapisz odpowiedzi studenta do bazy danych
        foreach ($odpowiedz_ids as $odpowiedz_id) {
            $sql = "SELECT o.punkty AS punkty_odpowiedzi, o.correct AS czy_poprawna
                    FROM tOdpowiedzi o
                    WHERE o.ID = '$odpowiedz_id'";
            $result = mysqli_query($conn, $sql);
            $data = mysqli_fetch_assoc($result);
            $czy_poprawna = $data['czy_poprawna'];
            $punkty_pytania = $data['punkty_odpowiedzi'];

            $sql = "INSERT INTO tOdpowiedziStudenta (id_proby, id_testu, id_studenta, id_pytania, id_odpowiedzi, correct, points)
                    VALUES ('$id_proby', '$id_testu', '$id_studenta', '$pytanie_id', '$odpowiedz_id', '$czy_poprawna', '$punkty_pytania')";
            if (!mysqli_query($conn, $sql)) {
                die("Błąd zapisu odpowiedzi: " . mysqli_error($conn));
            }
        }

        // Dodaj punkty za to pytanie do sumy punktów
        $suma_punktow += $punkty;
    }

    // Pobierz maksymalną liczbę punktów dla testu
    $sql = "SELECT SUM(o.punkty) AS max_punkty 
            FROM tPytania p
            JOIN tOdpowiedzi o ON p.ID = o.id_pytania
            JOIN tTestPytania tp ON tp.id_pytania = p.ID
            JOIN tTesty t ON t.ID = tp.id_testu
            WHERE t.ID = $id_testu AND o.correct = 1";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    $max_punkty = (int) $data['max_punkty'] ?? 0;


    $procent = ($suma_punktow / $max_punkty) * 100;

    if ($procent >= 90) {
        $ocena = 5; // Bardzo dobry
    } elseif ($procent >= 80) {
        $ocena = 4.5; // Dobry plus
    } elseif ($procent >= 70) {
        $ocena = 4; // Dobry
    } elseif ($procent >= 60) {
        $ocena = 3.5; // Dostateczny plus
    } elseif ($procent >= 51) {
        $ocena = 3; // Dostateczny
    } else {
        $ocena = 2; // Niedostateczny
    }

    // Zaktualizuj wynik testu
    $sql = "UPDATE tProbyTestu 
            SET zdobyto_punktow = '$suma_punktow', status = 'zakończony', ocena = '$ocena', wynik_procentowy = '$procent', data_zakonczenia = NOW()
            WHERE ID = '$id_proby'";

    if (!mysqli_query($conn, $sql)) {
        die("Błąd aktualizacji wyniku testu: " . mysqli_error($conn));
    }

   
    
    $zaokraglony_procent = round($procent);

    // Wyświetl wynik testu
    echo "<h2>Test zakończony! Zdobyłeś $suma_punktow/$max_punkty punktów ($zaokraglony_procent%). Twoja ocena: $ocena.</h2>";


}
?>
