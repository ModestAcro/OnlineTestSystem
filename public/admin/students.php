<?php

    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');


    $studentCount = getTableCount($conn, 'tStudenci');
    $courseInfo = getTableInfo($conn, 'tKierunki');

    $studentInfo = getStudentsWithCourses($conn);

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

    <title>Studeńci</title>
</head>
<body>

    <?php include '../../includes/header.php'; ?>
    
    <main class="main my-5">
        <div class="container card shadow p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Lista studentów</h1>
                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                    <i class="bi bi-plus-circle"></i> 
                    <span class="d-none d-sm-inline">Utwórz studenta</span>
                </button>
            </div>
            <p>Ilość: <?php echo $studentCount; ?></p>

            <div class="table-responsive mt-5">
                <table class="table">
                    <thead class="table-active">
                        <tr>
                            <th>Nr. albumu</th>
                            <th>Kierunek</th>
                            <th>Rok</th>
                            <th>Rocznik</th>
                            <th>Imię</th>
                            <th>Nazwisko</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Uwagi</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($studentData = mysqli_fetch_assoc($studentInfo)): ?>
                            <tr>
                                <td><?php echo $studentData['nr_albumu']; ?></td>
                                <td><?php echo $studentData['kierunek_nazwa']; ?></td>
                                <td><?php echo $studentData['rok']; ?></td>
                                <td><?php echo $studentData['rocznik']; ?></td>
                                <td><?php echo $studentData['imie']; ?></td>
                                <td><?php echo $studentData['nazwisko']; ?></td>
                                <td><?php echo $studentData['email']; ?></td>
                                <td class="<?php echo $studentData['aktywny'] == 'T' ? 'active' : 'inactive'; ?>">
                                    <?php echo $studentData['aktywny'] == 'T' ? 'Aktywny' : 'Nieaktywny'; ?>
                                </td>
            
                                <td><?php echo $studentData['uwagi']; ?></td>

                                <td>
                                    <a href="edit_student.php?student_id=<?php echo $studentData['ID']; ?>" class="btn">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Modal Dodaj Wykładowcę -->
        <div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addTestModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="card-title fs-4 mt-2" id="addTestModalLabel">Utwórz studenta</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../../includes/admin/save_student.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Nr. albumu</label>
                                <input type="text" name="nrAlbumuStudenta" class="form-control" required>
                            </div>


                            <div class="mb-3">
                                <label class="form-label">Kierunek</label>
                                <select name="kierunekStudenta" class="form-select" required>
                                    <?php while ($course = mysqli_fetch_assoc($courseInfo)): ?>
                                        <option value="<?php echo $course['ID']; ?>">
                                            <?php echo $course['nazwa']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Rok</label>
                                <input type="number" name="rokStudenta" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Rocznik</label>
                                <input type="number" name="rocznikStudenta" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Imię</label>
                                <input type="text" name="imieStudenta" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nazwisko</label>
                                <input type="text" name="nazwiskoStudenta" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="emailStudenta" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hasło</label>
                                <input type="password" name="hasloStudenta" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Uwagi</label>
                                <input type="text" name="uwagiStudenta" class="form-control" required>
                            </div>

                            <button type="submit" class="btn btn-outline-danger w-100">Dodaj</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>   
    </main>    
    
    <!-- Pliki JavaScript --> 
    <script src="../../assets/js/modal_windows.js"></script>   
    <script src="../../assets/js/multi_select.js"></script>  

    <!-- multi_select.js --> 
    <script>
        new MultiSelectTag('kierunek')  // id
    </script>
    <!-- multi_select.js --> 
</body>
</html>