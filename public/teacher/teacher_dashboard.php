<?php

    session_start();

    require_once('../../config/connect.php');

    // Funkcja zwraca liczbę pytan konkretnego nauczyciela              
    function getTableCountByTeacherId($conn, $table, $id) {
    $query = "SELECT COUNT(*) AS liczba FROM $table WHERE id_wykladowcy = '$id'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Błąd zapytania: " . mysqli_error($conn));
    }

    return mysqli_fetch_assoc($result)['liczba'] ?? 0;

    }

    $teacher_id = $_SESSION['user_id'] ?? null;

    $questionCount = getTableCountByTeacherId($conn, 'tPytania', $teacher_id);
    $testCount = getTableCountByTeacherId($conn, 'tTesty', $teacher_id);
    $groupCount = getTableCountByTeacherId($conn, 'tGrupy', $teacher_id);


    function getCompletedTestsCount($conn, $teacher_id){
        $query = "SELECT COUNT(*) AS liczba_zakonczonych_testow
                    FROM tTesty 
                    WHERE data_zakonczenia IS NOT NULL 
                    AND data_zakonczenia <= NOW()
                    AND id_wykladowcy = $teacher_id";
        
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($result)['liczba_zakonczonych_testow'];
    }

    $completedTestCount = getCompletedTestsCount($conn, $teacher_id);

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

    <title>Panel wykładowcy</title>
   
</head>
<body>
   
    <?php include '../../includes/header.php'; ?>

    <main class="main">
    <div class="container py-4">
        <div class="row g-3">
            <!-- Testy -->
            <div class="col-md-6 col-lg-3">
                <a href="tests.php" class="text-decoration-none text-dark">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <img src="../../assets/images/icons/online-test.svg" alt="Test Icon" class="img-fluid mb-2" style="width: 50px;">
                            <h5 class="card-title">Testy</h5>
                            <p class="card-text"><?php echo $testCount; ?></p>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Pytania -->
            <div class="col-md-6 col-lg-3">
                <a href="questions.php" class="text-decoration-none text-dark">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <img src="../../assets/images/icons/question.svg" alt="Question Icon" class="img-fluid mb-2" style="width: 50px;">
                            <h5 class="card-title">Pytania</h5>
                            <p class="card-text"><?php echo $questionCount; ?></p>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Grupy studentów -->
            <div class="col-md-6 col-lg-3">
                <a href="student_groups.php" class="text-decoration-none text-dark">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <img src="../../assets/images/icons/group.svg" alt="Group Icon" class="img-fluid mb-2" style="width: 50px;">
                            <h5 class="card-title">Grupy studentów</h5>
                            <p class="card-text"><?php echo $groupCount; ?></p>
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Wyniki testów -->
            <div class="col-md-6 col-lg-3">
                <a href="completed_tests.php" class="text-decoration-none text-dark">
                    <div class="card text-center shadow-sm">
                        <div class="card-body">
                            <img src="../../assets/images/icons/checked.svg" alt="Results Icon" class="img-fluid mb-2" style="width: 50px;">
                            <h5 class="card-title">Wyniki testów</h5>
                            <p class="card-text"><?php echo $completedTestCount; ?></p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</main>

    <!-- Plik JavaScript --> 
    <script src="../../assets/js/modal_windows.js"></script> 
</body>
</html>