<?php
    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');
    date_default_timezone_set('Europe/Vilnius');

    $id_proby = $_GET['id_proby'] ?? null;
    $student_id = $_SESSION['user_id'] ?? null;

    if (!$id_proby) {
        die("Brak ID próby.");
    }

    // Pobranie `id_testu` na podstawie `id_proby`
    $sql = "SELECT id_testu, id_studenta FROM tProbyTestu WHERE ID = '$id_proby'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    $test_id = $row['id_testu'];
    $student_id = $row['id_studenta'];


    function getQuestionsWithAnswers($conn, $test_id) {
        $query = "SELECT p.ID AS pytanie_id, p.tresc AS tresc_pytania, 
                         o.ID AS odpowiedz_id, o.tresc AS tresc_odpowiedzi, p.typ AS typ_pytania
                  FROM tPytania p
                  JOIN tTestPytania tp ON p.ID = tp.id_pytania
                  JOIN tOdpowiedzi o ON p.ID = o.id_pytania
                  WHERE tp.id_testu = $test_id
                  ORDER BY p.ID";
    
        $result = mysqli_query($conn, $query);
    
        $questions = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $pytanie_id = $row['pytanie_id'];
    
            if (!isset($questions[$pytanie_id])) {
                $questions[$pytanie_id] = [
                    'tresc_pytania' => $row['tresc_pytania'],
                    'typ_pytania' => $row['typ_pytania'],
                    'odpowiedzi' => []
                ];
            }
            $questions[$pytanie_id]['odpowiedzi'][] = [
                'id' => $row['odpowiedz_id'],
                'tresc_odpowiedzi' => $row['tresc_odpowiedzi']
            ];
        }
        return $questions;
    }
    

    $testQuestions = getQuestionsWithAnswers($conn, $test_id);







    // Pobierz czas trwania testu w minutach
    $query = "SELECT czas_trwania FROM tTesty WHERE ID = $test_id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $czas_trwania = $row['czas_trwania'];



    // Pobierz czas rozpoczenia testu 
    $sql = "SELECT data_prob FROM tProbyTestu WHERE ID = '$id_proby' AND id_testu = '$test_id' AND id_studenta = '$student_id'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $data_prob = $row['data_prob'];
    } else {
        die("Nie znaleziono daty próby.");
    }


    
    // Konwertuj data_prob na znacznik czasu UNIX
    $timestamp_data_prob = strtotime($data_prob);

    if ($timestamp_data_prob === false) {
        die("Nieprawidłowy format daty próby.");
    }

    // Oblicz czas zakończenia
    $czas_zakonczenia = $timestamp_data_prob + ($czas_trwania * 60);



    //Sprawdzenie czy uczen nie oszukuje system po przycisku "wstecz"
    $query = "SELECT status FROM tProbyTestu WHERE id_studenta = $student_id AND id_testu = $test_id AND ID = '$id_proby'";
    $result = mysqli_query($conn, $query); 
    $row = mysqli_fetch_assoc($result);

    $status = $row['status'];
    
    if ($status === 'zakończony') {
        // Przekieruj do strony z wynikami lub informacją o zakończonym teście
        header("Location: student_dashboard.php");
        exit();
    }

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

    <title>Rozpocznij test</title>
</head>
<body>
    <main class="main">
        <div class="container mt-5">
            <form action="../../includes/student/submit_test.php" method="POST">
                <input type="hidden" name="id_proby" value="<?= htmlspecialchars($id_proby) ?>">

                <div class="tests-box">
                    <!-- Timer -->
                    <div class="d-flex justify-content-end mb-4">
                        <div class="timer" id="timer"></div>
                    </div>

                    <!-- Test Questions -->
                    <?php foreach ($testQuestions as $pytanie_id => $pytanie): ?>
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="card-title"><?= htmlspecialchars($pytanie['tresc_pytania']) ?></h5>
                                <label class="card-subtitle text-muted"><?= htmlspecialchars($pytanie['typ_pytania']) ?></label>
                            </div>
                            <div class="card-body">
                                <?php foreach ($pytanie['odpowiedzi'] as $odpowiedz): ?>
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="odpowiedzi[<?= $pytanie_id ?>][]" value="<?= $odpowiedz['id'] ?>">
                                        <label class="form-check-label"><?= htmlspecialchars($odpowiedz['tresc_odpowiedzi']) ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Submit Button -->
                <div class=" mt-4 mb-5">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Zakończ test</button>
                </div>
            </form>
        </div>
    </main>

    
    <script>
    // Pobierz czas zakończenia z PHP
    var czasZakonczenia = <?= $czas_zakonczenia ?> * 1000; // w milisekundach

    function odliczanie() {
        var teraz = new Date().getTime();
        var roznica = czasZakonczenia - teraz;

        var minuty = Math.floor((roznica % (1000 * 60 * 60)) / (1000 * 60));
        var sekundy = Math.floor((roznica % (1000 * 60)) / 1000);

        document.getElementById("timer").innerHTML = minuty + "m " + sekundy + "s ";

        if (roznica < 0) {
            clearInterval(x);
            document.getElementById("timer").innerHTML = "KONIEC CZASU";
            // Automatyczne wysłanie formularza po upływie czasu
            document.querySelector('form').submit();
        }
    }

    var x = setInterval(odliczanie, 1000);
</script>

</body>
</html>