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
            <form action="../../includes/student/submit_test.php" method="POST" id="test-form">
                <input type="hidden" name="id_proby" value="<?= htmlspecialchars($id_proby) ?>">

                <div class="tests-box">
                    <div class="d-flex justify-content-between align-items-center mt-4 mb-5">
                        <!-- Licznik pytań -->
                        <div class="text-left">
                            <span id="question-counter"></span>
                        </div>
                        <!-- Timer -->
                        <div class="text-right">
                            <div class="timer" id="timer"></div>
                        </div>
                    </div>
                    <!-- Test Questions -->
                    <div id="questions-container">
                        <?php $i = 0; ?>
                        <?php foreach ($testQuestions as $pytanie_id => $pytanie): ?>
                            <div class="question-card card mb-4" data-question="<?= $i ?>" style="<?= $i === 0 ? '' : 'display: none;' ?>">
                                <div class="card-header">
                                    <pre><h5><?= htmlspecialchars($pytanie['tresc_pytania']) ?></h5></pre>
                                    <label class="card-subtitle text-muted"><?= htmlspecialchars($pytanie['typ_pytania']) ?></label>
                                </div>
                                <div class="card-body">
                                    <?php foreach ($pytanie['odpowiedzi'] as $odpowiedz): ?>
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="odpowiedzi[<?= $pytanie_id ?>][]" value="<?= $odpowiedz['id'] ?>">
                                            <pre><label class="form-check-label"><?= htmlspecialchars($odpowiedz['tresc_odpowiedzi']) ?></label></pre>
                                            <hr>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <!-- Pagination -->
                        <nav>
                            <ul class="pagination mb-0">
                                <li class="page-item">
                                    <button type="button" class="btn btn-outline-danger" id="prev-btn" disabled>Poprzednie</button>
                                </li>
                                <li class="page-item mx-2">
                                    <button type="button" class="btn btn-outline-danger" id="next-btn">Następne</button>
                                </li>
                            </ul>
                        </nav>
                        <!-- Submit Button -->
                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#confirmModal">
                            Zakończ test
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- Modal potwierdzenia zakończenia testu -->
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="card-title fs-4 mt-2" id="logoutModalLabel">Potwierdzenie zakończenia testu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Czy na pewno chcesz zakończyć test? Nie będzie już można wprowadzić zmian.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Anuluj</button>
                        <!-- Wysłanie formularza -->
                        <button type="submit" class="btn btn-outline-danger" form="test-form">Zakończ test</button>
                    </div>
                </div>
            </div>
        </div>
    </main>



    <!-- JavaScript do paginacji -->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
        let currentQuestion = 0;
        const questions = document.querySelectorAll(".question-card");
        const totalQuestions = questions.length;
        
        const prevBtn = document.getElementById("prev-btn");
        const nextBtn = document.getElementById("next-btn");
        const questionCounter = document.getElementById("question-counter"); // Dodane

        function updatePagination() {
            questions.forEach((q, index) => {
                q.style.display = index === currentQuestion ? "block" : "none";
            });

            prevBtn.disabled = currentQuestion === 0;
            nextBtn.disabled = currentQuestion === totalQuestions - 1;

            // Aktualizacja licznika pytań
            questionCounter.textContent = `Pytanie ${currentQuestion + 1} z ${totalQuestions}`;
        }

        prevBtn.addEventListener("click", function (event) {
            event.preventDefault();
            if (currentQuestion > 0) {
                currentQuestion--;
                updatePagination();
            }
        });

        nextBtn.addEventListener("click", function (event) {
            event.preventDefault();
            if (currentQuestion < totalQuestions - 1) {
                currentQuestion++;
                updatePagination();
            }
        });

            updatePagination();
        });
    </script>


    
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