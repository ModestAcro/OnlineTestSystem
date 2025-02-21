<?php

    session_start();    

    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $studentId = $_GET['student_id'];
    $student = getRecordById($conn, 'tStudenci', $studentId);

    $courseInfo = getTableInfo($conn, 'tKierunki');

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

    <title>Edytuj Studenta</title>
</head>
<body>

    <?php include '../../includes/header.php'; ?>
    
    <main class="main my-5">
        <div class="container card shadow p-4">
            <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Edytuj Studenta</h1>
            <form action="../../includes/admin/update_student.php" method="POST">
                <input type="hidden" name="idStudenta" value="<?php echo $student['ID']; ?>">

                <h5 class="card-title fs-4 mt-2">Nr. albumu</h5>
                <div class="mb-3">
                    <input type="text" name="nrAlbumuStudenta" class="form-control" value="<?php echo $student['nr_albumu']; ?>" required>
                </div>

                <div class="mb-3">
                    <h5 class="card-title fs-4 mt-2">Kierunek</h5>
                    <select name="kierunekStudenta" class="form-select" required>
                        <option disabled selected>Wybierz kierunek</option>
                        <?php while ($course = mysqli_fetch_assoc($courseInfo)): ?>
                            <option value="<?php echo $course['ID']; ?>"
                                <?php echo $course['ID'] == $student['id_kierunku'] ? 'selected' : ''; ?>>
                                <?php echo $course['nazwa']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <h5 class="card-title fs-4 mt-2">Rok</h5>
                <div class="mb-3">
                    <input type="number" name="rokStudenta" class="form-control" value="<?php echo $student['rok']; ?>" required>
                </div>

                <h5 class="card-title fs-4 mt-2">Rocznik</h5>
                <div class="mb-3">
                    <input type="number" name="rocznikStudenta" class="form-control"  value="<?php echo $student['rocznik']; ?>" required>
                </div>

                <h5 class="card-title fs-4 mt-2">Imię</h5>
                <div class="mb-3">
                    <input type="text" name="imieStudenta" class="form-control"  value="<?php echo $student['imie']; ?>"  required>
                </div>

                <h5 class="card-title fs-4 mt-2">Nazwisko</h5>
                <div class="mb-3">
                    <input type="text" name="nazwiskoStudenta" class="form-control"  value="<?php echo $student['nazwisko']; ?>"  required>
                </div>

                <h5 class="card-title fs-4 mt-2">Email</h5>
                <div class="mb-3">
                    <input type="email" name="emailStudenta" class="form-control"  value="<?php echo $student['email']; ?>"  required>
                </div>

                <h5 class="card-title fs-4 mt-2">Hasło</h5>
                <div class="mb-3">
                    <input type="password" name="hasloStudenta" class="form-control"  value="<?php echo $student['haslo']; ?>"  required>
                </div>

                <h5 class="card-title fs-4 mt-2">Uwagi</h5>
                <div class="mb-3">
                    <input type="text" name="uwagiStudenta" class="form-control"  value="<?php echo $student['uwagi']; ?>"  required>
                </div>

                <button type="submit" name="action" value="update"  class="btn btn-outline-danger">Zapisz Zmiany</button>
                <a href="#" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Usuń</a>
            </form>

            <!-- Modal potwierdzenia usunięcia grupy -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="card-title fs-4 mt-2" id="logoutModalLabel">Potwierdzenie usunięcia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Czy na pewno chcesz usunąć tego studenta?
                    </div>
                    <div class="modal-footer">
                        <form action="../../includes/admin/update_student.php" method="POST">
                            <input type="hidden" name="idStudenta" value="<?php echo $student['ID']; ?>">
                            <input type="hidden" name="action" value="delete">
                            <button type="submit" class="btn btn-outline-danger">Usuń</button>
                        </form>
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Anuluj</button>
                    </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

</body>
</html>
