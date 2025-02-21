<?php

    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $teacherInfo = getTableInfo($conn, 'tWykladowcy'); 
    $coursesInfo = getTableInfo($conn, 'tKierunki'); 

    $teacherCount = getTableCount($conn, 'tWykladowcy');

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

    <title>Wykładowcy</title>
</head>
<body>
   
    <?php include '../../includes/header.php'; ?>
    
    <main class="main my-5">
        <div class="container card shadow p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Lista wykładowców</h1>
                <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                    <i class="bi bi-plus-circle"></i> Utwórz wykładowcę
                </button>
            </div>
            <p>Ilość: <?php echo $teacherCount; ?></p>

            <div class="table-responsive mt-5">
                <table class="table">
                    <thead class="table-active">
                        <tr>
                            <th>Stopień</th>
                            <th>Imię</th>
                            <th>Nazwisko</th>
                            <th>Email</th>
                            <th>Kierunki</th>
                            <th>Status</th>
                            <th>Uwagi</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($teacherData = mysqli_fetch_assoc($teacherInfo)): ?>
                            <tr>
                            <td><?php echo ($teacherData['stopien']); ?></td>
                                <td><?php echo ($teacherData['imie']); ?></td>
                                <td><?php echo ($teacherData['nazwisko']); ?></td>
                                <td><?php echo ($teacherData['email']); ?></td>

                                <td>
                                    <?php
                                        // Pobierz przypisane kierunki do wykładowcy
                                        $teacherId = $teacherData['ID'];
                                        $coursesName = getCoursesNames($conn, $teacherId);   //funkcja "getCoursesNames" 36 linijka

                                        // Wyświetl przypisane kierunki
                                        while ($courses = mysqli_fetch_assoc($coursesName)) {
                                            echo $courses['nazwa'] . '<br>';
                                        }
                                    ?>
                                </td>


                                <td class="<?php echo $teacherData['aktywny'] == 'T' ? 'active' : 'inactive'; ?>"> <!-- Ustawiamy klasę dla styli CSS -->
                                    <?php echo $teacherData['aktywny'] == 'T' ? 'Aktywny' : 'Nieaktywny'; ?> <!-- Wyświetlamy do tabeli -->
                                </td>
                                <td><?php echo ($teacherData['uwagi']); ?></td>

                                <td>
                                    <a href="edit_teacher.php?teacher_id=<?php echo $teacherData['ID']; ?>" class="btn">
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
        <div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTestModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="card-title fs-4 mt-2" id="addTestModalLabel">Utwórz wykładowcę</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../../includes/admin/save_teacher.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Imię</label>
                                <input type="text" name="imieWykladowcy" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nazwisko</label>
                                <input type="text" name="nazwiskoWykladowcy" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stopień</label>
                                <input type="text" name="stopienWykladowcy" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="emailWykladowcy" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hasło</label>
                                <input type="password" name="hasloWykladowcy" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kierunki</label>
                                <select id="kierunki" name="kierunki[]" class="form-select" required>
                                    <?php while ($courses = mysqli_fetch_assoc($coursesInfo)): ?>
                                        <option value="<?php echo $courses['ID']; ?>">
                                            <?php echo $courses['nazwa']; ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Uwagi</label>
                                <input type="text" name="uwagiWykladowcy" class="form-control" required>
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
        new MultiSelectTag('kierunki')  // id
    </script>
    <!-- multi_select.js --> 
</body>
</html>