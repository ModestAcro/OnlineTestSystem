<?php

    session_start();

    require_once('../../config/connect.php');
    require_once('../../config/functions.php');


    $subjectId = $_GET['subject_id'];
    $subject = getRecordById($conn, 'tPrzedmioty', $subjectId);

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

    <title>Edytuj przedmiot</title>
</head>
<body>

    <?php include '../../includes/header.php'; ?>
    
    <main class="main my-5">
        <div class="container card shadow p-4">
            <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Edytuj przedmiot</h1>
            <form action="../../includes/admin/update_subject.php" method="POST">
                <input type="hidden" name="idPrzedmiotu" value="<?php echo $subject['ID']; ?>">

                <h5 class="card-title fs-4 mt-2">Nazwa</h5>
                <div class="mb-3">
                    <input type="text" name="nazwaPrzedmiotu" class="form-control" value="<?php echo $subject['nazwa']; ?>" required>
                </div>

                <h5 class="card-title fs-4 mt-2">Uwagi</h5>
                <div class="mb-3">
                    <input type="text" name="uwagiPrzedmiotu" class="form-control"  value="<?php echo $subject['uwagi']; ?>" required>
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
                        Czy na pewno chcesz usunąć ten przedmiot?
                    </div>
                    <div class="modal-footer">
                        <form action="../../includes/admin/update_subject.php" method="POST">
                            <input type="hidden" name="idPrzedmiotu" value="<?php echo $subject['ID']; ?>">
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

    <!-- Plik JavaScript --> 
    <script src="../../assets/js/modal_windows.js"></script>  
</body>
</html>
