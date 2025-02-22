<?php

    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $subjectInfo = getTableInfo($conn, 'tPrzedmioty');
    $subjectCount = getTableCount($conn, 'tPrzedmioty');

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
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../../assets/css/main.css">

    <title>Przedmioty</title>
</head>
<body>
  
    <?php include '../../includes/header.php'; ?>

    <main class="main my-5">
        <div class="container card shadow p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Lista przedmiotów</h1>
                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#addSucjectModal">
                    <i class="bi bi-plus-circle"></i> 
                    <span class="d-none d-sm-inline">Utwórz przedmiot</span>
                </button>
            </div>
            <p>Ilość: <?php echo $subjectCount; ?></p>

            <!-- SEARCH -->
            <input class="form-control" id="myInput" type="text" placeholder="Szukaj...">
            <!-- SEARCH -->


            <div class="table-responsive mt-4">
                <table class="table">
                    <thead class="table-active">
                        <tr>
                            <th>Nazwa</th>
                            <th>Uwagi</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody id="myTable">
                        <?php while ($subjectData = mysqli_fetch_assoc($subjectInfo)): ?>
                            <tr>
                                <td><?php echo $subjectData['nazwa']; ?></td>
                                <td><?php echo $subjectData['uwagi']; ?></td>
                    
                                <td>
                                    <a href="edit_subject.php?subject_id=<?php echo $subjectData['ID']; ?>" class="btn">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal Dodaj Przedmiot -->
        <div class="modal fade" id="addSucjectModal" tabindex="-1" aria-labelledby="addTestModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="card-title fs-4 mt-2" id="addTestModalLabel">Utwórz przedmiot</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../../includes/admin/save_subject.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nazwa</label>
                                <input type="text" name="nazwa" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Uwagi</label>
                                <input type="text" name="uwagi" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-outline-danger w-100">Dodaj</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>  
    
    <script>
        $(document).ready(function(){
            $("#myInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                // Filtering table rows on larger screens
                $("#myTable tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>

</body>
</html>