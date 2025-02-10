<?php
    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');


    $id_proby = $_GET['id_proby'] ?? null;

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



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Rozpocznij test</title>
</head>
<body>
    <main class="main">
        <div class="container">
            <form action="../../includes/student/submit_test.php" method="POST">
            <input type="hidden" name="id_proby" value="<?= htmlspecialchars($id_proby) ?>">
                <div class="tests-box">
                    <?php foreach ($testQuestions as $pytanie_id => $pytanie): ?>
                    <div class="test_card">
                        <div class="test_title">
                            <div>
                                <h1><?= htmlspecialchars($pytanie['tresc_pytania']) ?></h1>
                            </div>
                            <div>   
                                <label><?= htmlspecialchars($pytanie['typ_pytania']) ?></label>
                            </div>
                        </div>
                        <div class="test-questions">
                            <?php foreach ($pytanie['odpowiedzi'] as $odpowiedz): ?>
                                <label class="answer-option">
                                    <input type="checkbox" name="odpowiedzi[<?= $pytanie_id ?>][]" value="<?= $odpowiedz['id'] ?>">
                                    <?= htmlspecialchars($odpowiedz['tresc_odpowiedzi']) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <button type="submit" class="start-btn">Zakończ test</button>
            </form>
        </div>
    </main>
    
</body>
</html>