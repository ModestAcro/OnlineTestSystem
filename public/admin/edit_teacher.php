<?php
    session_start();

    require_once('../../config/connect.php');
    require_once('../../config/functions.php');
    
    $teacherId = $_GET['teacher_id'];
    $teacher = getRecordById($conn, 'tWykladowcy', $teacherId);

    $CoursesInfo = getTableInfo($conn, 'tKierunki'); 
    $assignetCourses = getCoursesByTeacher($conn, $teacherId);
    
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

    <title>Edytuj Wykładowcę</title>
</head>
<body>

    <?php include '../../includes/header.php'; ?>
    
    <main class="main my-5">
        <div class="container card shadow p-4">
            <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Edytuj Wykładowcę</h1>
            <form action="../../includes/admin/update_teacher.php" method="POST">
                <input type="hidden" name="idWykladowcy" value="<?php echo $teacher['ID']; ?>">

                <h5 class="card-title fs-4 mt-2">Imię</h5>
                <div class="mb-3">
                    <input type="text" name="imieWykladowcy" class="form-control" value="<?php echo $teacher['imie']; ?>" required>
                </div>

                <h5 class="card-title fs-4 mt-2">Nazwisko</h5>
                <div class="mb-3">
                    <input type="text" name="nazwiskoWykladowcy" class="form-control" value="<?php echo $teacher['nazwisko']; ?>" required>
                </div>


                <h5 class="card-title fs-4 mt-2">Stopień</h5>
                <div class="mb-3">
                    <input type="text" name="stopienWykladowcy" class="form-control" value="<?php echo $teacher['stopien']; ?>" required>
                </div>


                <h5 class="card-title fs-4 mt-2">Email</h5>
                <div class="mb-3">
                    <input type="email" name="emailWykladowcy" class="form-control" value="<?php echo $teacher['email']; ?>" required>
                </div>


                <h5 class="card-title fs-4 mt-2">Hasło</h5>
                <div class="mb-3">
                    <input type="password" name="hasloWykladowcy" class="form-control" value="<?php echo $teacher['haslo']; ?>" required>
                </div>

                <div class="mb-3">
                    <h5 class="card-title fs-4 mt-2">Kierunki</h5>
                    <select id="kierunki" name="kierunki[]" class="form-select"  multiple>
                        <?php 
                        while ($courses = mysqli_fetch_assoc($CoursesInfo)): 
                            $assignetCoursesIds = array_column($assignetCourses, 'id_kierunku');
                            
                            $czyZaznaczony = in_array($courses['ID'], $assignetCoursesIds) ? 'selected' : '';
                        ?>
                            <option value="<?php echo $courses['ID']; ?>" <?php echo $czyZaznaczony; ?>>
                                <?php echo $courses['nazwa']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <h5 class="card-title fs-4 mt-2">Uwagi</h5>
                <div class="mb-3">
                    <textarea name="uwagiWykladowcy" class="form-control"><?php echo $teacher['uwagi']; ?></textarea>
                </div>

                <button type="submit" name="action" value="update"  class="btn btn-outline-danger">Zapisz Zmiany</button>
                <a href="#" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">Usuń</a>
            </form>


            <!-- Modal potwierdzenia usunięcia Wykladowcy -->
            <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="card-title fs-4 mt-2" id="logoutModalLabel">Potwierdzenie usunięcia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Czy na pewno chcesz usunąć tego wykładowcę?
                    </div>
                    <div class="modal-footer">
                        <form action="../../includes/admin/update_teacher.php" method="POST">
                            <input type="hidden" name="idWykladowcy" value="<?php echo $teacher['ID']; ?>">
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

    <!-- Pliki JavaScript --> 
    <script src="../../assets/js/multi_select.js"></script>  

    <!-- multi_select.js --> 
    <script>
        new MultiSelectTag('kierunki')  // id
    </script>
    <!-- multi_select.js --> 
</body>
</html>
