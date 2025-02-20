<?php

    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $user_id = $_SESSION['user_id'];

    // Pobiera liczbę testów związanych z nauczycielem
    $testCount = getTestCountByTeacherId($conn, 'tTesty', $user_id); 

    $tTestInfo = getTestsByTeacherId($conn, $user_id);

    // Funkcja do pobierania kierunków przypisanych do nauczyciela
    function getCoursesByTeacher2($conn, $user_id) {
        // Zapytanie SQL do pobrania kierunków przypisanych do nauczyciela
        $sql = "SELECT k.nazwa, k.ID
                FROM tKierunki k
                JOIN twykladowcykierunki t ON t.id_kierunku = k.ID
                WHERE t.id_wykladowcy = $user_id";  // Wstawienie $teacherId bezpośrednio w zapytaniu
        
        // Wykonanie zapytania
        $result = mysqli_query($conn, $sql);

        return $result;
    }

    $courseByTeacher = getCoursesByTeacher2($conn, $user_id);


    function getSubjectsByTeacher($conn, $user_id) {
        $sql = "SELECT p.nazwa, p.ID
                FROM tPrzedmioty p
                JOIN tKierunkiPrzedmioty kp ON p.ID = kp.id_przedmiotu
                JOIN tKierunki k ON kp.id_kierunku = k.ID
                JOIN tWykladowcyKierunki wk ON wk.id_kierunku = k.ID
                WHERE wk.id_wykladowcy = $user_id";
    
        $result = mysqli_query($conn, $sql);
        
        return $result;
    }

    $subjectsByTeacher = getSubjectsByTeacher($conn, $user_id);


    function getTestDetails($conn, $test_id) {
        $query = "SELECT t.ID, t.id_przedmiotu, p.nazwa AS nazwa_przedmiotu, COUNT(tp.id_pytania) AS liczba_pytan
                  FROM tTesty t
                  JOIN tPrzedmioty p ON t.id_przedmiotu = p.ID
                  LEFT JOIN tTestPytania tp ON tp.id_testu = t.ID
                  WHERE t.ID = $test_id
                  GROUP BY t.ID, t.id_przedmiotu, p.nazwa";
    
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
    
        return $row ? ['nazwa_przedmiotu' => $row['nazwa_przedmiotu'], 'liczba_pytan' => $row['liczba_pytan']] : ['nazwa_przedmiotu' => null, 'liczba_pytan' => 0];
    }


    function getGroupDetails($conn, $test_id){
        $query = "
        SELECT 
            t.id_grupy, 
            g.ID AS id_grupy,
            g.nazwa AS nazwa_grupy,
            g.rok AS rok_grupy,
            COUNT(gs.id_studenta) AS liczba_studentów,
            GROUP_CONCAT(CONCAT(s.imie, ' ', s.nazwisko, ' (', s.nr_albumu, ')') SEPARATOR '; ') AS studenci
        FROM tTesty t
        JOIN tGrupy g ON t.id_grupy = g.ID
        LEFT JOIN tGrupyStudenci gs ON g.ID = gs.id_grupy
        LEFT JOIN tStudenci s ON gs.id_studenta = s.id
        WHERE t.id = $test_id
        GROUP BY t.id_grupy, g.ID, g.nazwa, g.rok;
    ";

    $result = mysqli_query($conn, $query);

    return $result;

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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../assets/css/main.css">

    <title>Lista testów</title>
</head>
<body>
   
    <?php include '../../includes/header.php'; ?>

    <main class="main my-5">
    <div class="container card shadow p-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Lista testów</h1>
            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#addTestModal">
                <i class="bi bi-plus-circle"></i> Utwórz test
            </button>
        </div>
        <p>Ilość: <?php echo $testCount; ?></p>



        <div class="table-responsive mt-5">
            <table class="table">
                <thead class="table-active">
                    <tr>
                        <th>Nazwa</th>
                        <th>Przedmiot</th>
                        <th>Grupa</th>
                        <th>Data utworzenia</th>
                        <th>Czas trwania (min.)</th>
                        <th>Ilość prób</th>
                        <th>Ilość pytań</th>
                        <th>Akcje</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($testData = mysqli_fetch_assoc($tTestInfo)): ?>
                        <?php $test_id = $testData['ID']; ?>
                        <tr>
                            <td><?php echo $testData['nazwa']; ?></td>
                            <td><?php echo getTestDetails($conn, $test_id)['nazwa_przedmiotu'] ?? 'Brak danych'; ?></td>
                            <td>
                                <?php
                                    $groupDetails = getGroupDetails($conn, $test_id);
                                    if ($groupDetails && $group = mysqli_fetch_assoc($groupDetails)) {
                                        echo "<span class='badge bg-secondary'>{$group['nazwa_grupy']}</span>";
                                    } else {
                                        echo 'Brak grupy';
                                    }
                                ?>
                            </td>
                            <td><?php echo date('Y-m-d', strtotime($testData['data_utworzenia'])); ?></td>
                            <td><?php echo $testData['czas_trwania']; ?></td>
                            <td><?php echo $testData['ilosc_prob'] == -1 ? 'Nieograniczona' : $testData['ilosc_prob']; ?></td>
                            <td><?php echo getTestDetails($conn, $test_id)['liczba_pytan']; ?></td>
                            <td>
                                <a href="edit_test.php?test_id=<?php echo $testData['ID']; ?>" class="btn">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Dodaj Test -->
    <div class="modal fade" id="addTestModal" tabindex="-1" aria-labelledby="addTestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="card-title fs-4 mt-2" id="addTestModalLabel">Dodaj Test</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="add_test.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nazwa</label>
                            <input type="text" name="nazwa" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kierunek</label>
                            <select name="kierunek" class="form-select" required>
                                <option disabled selected>Wybierz kierunek</option>
                                <?php while ($courses = mysqli_fetch_assoc($courseByTeacher)): ?>
                                    <option value="<?php echo $courses['ID']; ?>">
                                        <?php echo $courses['nazwa']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Przedmiot</label>
                            <select name="przedmiot" class="form-select" required>
                                <option disabled selected>Wybierz przedmiot</option>
                                <?php while ($subjects = mysqli_fetch_assoc($subjectsByTeacher)): ?>
                                    <option value="<?php echo $subjects['ID']; ?>">
                                        <?php echo $subjects['nazwa']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-outline-danger w-100">Dodaj</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </main>

    
    <script src="../../assets/js/modal_windows.js"></script>  

</body>
</html>