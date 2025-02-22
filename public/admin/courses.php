<?php

    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $kierunekInfo = getTableInfo($conn, 'tKierunki');
    $przedmiotInfo = getTableInfo($conn, 'tPrzedmioty');

    $kierunekCount = getTableCount($conn, 'tKierunki');

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

    <title>Uczelnie</title>
</head>
<body>
   
    <?php include '../../includes/header.php'; ?>

    <main class="main my-5">
        <div class="container card shadow p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Lista kierunków</h1>
                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                    <i class="bi bi-plus-circle"></i> 
                    <span class="d-none d-sm-inline">Utwórz kierunek</span>
                </button>
            </div>
            <p>Ilość: <?php echo $kierunekCount; ?></p>

            <div class="table-responsive mt-5">
                <table class="table">
                    <thead class="table-active">
                        <tr>
                            <th>Nazwa</th>
                            <th>Liczba przedmiotów</th>
                            <th>Uwagi</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($kierunekData = mysqli_fetch_assoc($kierunekInfo)): ?>
                            <tr>
                                <td><?php echo $kierunekData['nazwa']; ?></td>
                                <td>
                                    <?php 
                                        // Wywołanie funkcji do zliczania liczby przedmiotów przypisanych do kierunku
                                        $courseId = $kierunekData['ID'];
                                        $subjectCount = getSubjectCountByCourse($conn, $courseId);
                                        echo $subjectCount; // Wyświetlanie liczby przedmiotów
                                    ?>
                                </td>
                                <td><?php echo $kierunekData['uwagi']; ?></td>
                    
                                <!-- Przyciski "Modyfikuj" -->
                                <td>
                                <button class="btn" onclick="window.location.href='edit_course.php?kierunek_id=<?php echo $kierunekData['ID']; ?>'">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                </td>
                                <!-- Przyciski "Modyfikuj" -->

                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Dodaj Wykładowcę -->
        <div class="modal fade" id="addCourseModal" tabindex="-1" aria-labelledby="addTestModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="card-title fs-4 mt-2" id="addTestModalLabel">Utwórz kierunek</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../../includes/admin/save_course.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nazwa</label>
                                <input type="text" name="nazwaKierunku" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Przedmioty</label>
                                <select id="przedmioty" name="przedmioty[]" class="form-select" required multiple>
                                    <?php while ($przedmiot = mysqli_fetch_assoc($przedmiotInfo)): ?>
                                        <option value="<?php echo $przedmiot['ID']; ?>">
                                            <?php echo $przedmiot['nazwa']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Uwagi</label>
                                <input type="text" name="uwagiKierunku" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-outline-danger w-100">Dodaj</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>    
    
    <!-- Pliki JavaScript -->  
    <script src="../../assets/js/multi_select.js"></script>  

    <!-- multi_select.js --> 
    <script>
        new MultiSelectTag('przedmioty')  // id
    </script>
    <!-- multi_select.js --> 
</body>
</html>