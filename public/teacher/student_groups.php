<?php

    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $user_id = $_SESSION['user_id'];

    // Pobiera liczbę grup związanych z nauczycielem
    $studentGroupCount = getGroupCountByTeacherId($conn, 'tGrupy', $user_id); 

    // Wywołanie funkcji do zliczania studentów w konkretnej grupie
    $studentCountAtGroup = getStudentCountByGroup($conn, $user_id);


    $studentGroupInfo = getStudentGroups($conn, $user_id);

    function getStudentsByTeacher($conn, $user_id) {
        $query = "SELECT tStudenci.*
                  FROM tStudenci
                  JOIN twykladowcykierunki 
                      ON tStudenci.id_kierunku = tWykladowcyKierunki.id_kierunku
                  WHERE twykladowcykierunki.id_wykladowcy = $user_id";
    
        $result = mysqli_query($conn, $query);
    
        return $result;
    }

    $studentInfo = getStudentsByTeacher($conn, $user_id); 

    function getCoursesByTeacher2($conn, $user_id) {
        $query = "SELECT tKierunki.*
                  FROM tKierunki
                  JOIN twykladowcykierunki 
                      ON tKierunki.ID = twykladowcykierunki.id_kierunku
                  WHERE twykladowcykierunki.id_wykladowcy = $user_id";
    
        $result = mysqli_query($conn, $query);
    
        return $result;
    }

    $kierunekInfo = getCoursesByTeacher2($conn, $user_id);
    
    function getSubjectsByTeacher($conn, $user_id) {
        $query = "SELECT DISTINCT tPrzedmioty.*
                  FROM tPrzedmioty
                  JOIN tKierunkiPrzedmioty 
                      ON tPrzedmioty.ID = tKierunkiPrzedmioty.id_przedmiotu
                  JOIN tWykladowcyKierunki 
                      ON tKierunkiPrzedmioty.id_kierunku = twykladowcykierunki.id_kierunku
                  WHERE twykladowcykierunki.id_wykladowcy = $user_id";
    
        $result = mysqli_query($conn, $query);
    
        return $result;
    }
    
    $subjectInfo = getSubjectsByTeacher($conn, $user_id);



    
    


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

    <title>Grupy studentów</title>
</head>
<body>
   
    <?php include '../../includes/header.php'; ?>

    <main class="main my-5">
    <div class="container card shadow p-4">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="fs-2 fs-md-3 fs-lg-5 pt-2">Lista grup studentów</h1>
            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#addStudentGroupModal">
                <i class="bi bi-plus-circle"></i> Utwórz grupę
            </button>
        </div>
        <p>Ilość: <?php echo $studentGroupCount; ?></p>

        <div class="table-responsive mt-5">
            <table class="table">
                <thead class="table-active">
                    <tr>
                        <th>Rok</th>
                        <th>Kierunek</th>
                        <th>Przedmiot</th>
                        <th>Nazwa</th>
                        <th>Liczba studentów</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($groupData = mysqli_fetch_assoc($studentGroupInfo)): ?>
                        <tr>
                            <td><?php echo $groupData['rok']; ?></td>
                            <td><?php echo $groupData['nazwa_kierunku']; ?></td>
                            <td><?php echo $groupData['przedmiot']; ?></td>
                            <td><?php echo $groupData['nazwa_grupy']; ?></td>
                            <td>
                            <?php 
                                // Wyświetlanie liczby studentów przypisanych do danej grupy
                                $groupId = $groupData['ID'];
                                echo $studentCountAtGroup[$groupId] ?? 0;
                            ?>
                            </td>

                            <!-- Przyciski "Modyfikuj" -->
                            <td>
                                <a href="edit_group.php?group_id=<?php echo $groupData['ID']; ?>" class="btn">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                            </td>
                            <!-- Przyciski "Modyfikuj" -->
                             
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Dodaj Grupę -->
    <div class="modal fade" id="addStudentGroupModal" tabindex="-1" aria-labelledby="addTestModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="card-title fs-4 mt-2" id="addTestModalLabel">Dodaj grupę</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../../includes/teacher/save_group.php" method="POST">

                        <div class="mb-3">
                            <label class="form-label">Rok</label>
                            <input type="text" pattern="\d{4}" id="rok" name="rok" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kierunek</label>
                            <select name="uczelnia" class="form-select" required>
                                <option disabled selected>Wybierz kierunek</option>
                                <?php while ($kierunek = mysqli_fetch_assoc($kierunekInfo)): ?>
                                    <option value="<?php echo $kierunek['ID']; ?>">
                                        <?php echo $kierunek['nazwa']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Przedmiot</label>
                            <select name="przedmiot" class="form-select" required>
                                <option disabled selected>Wybierz przedmiot</option>
                                <?php while ($subject = mysqli_fetch_assoc($subjectInfo)): ?>
                                    <option value="<?php echo $subject['ID']; ?>">
                                        <?php echo $subject['nazwa']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Wybierz studentów</label>
                            <select id="studenci" name="studenci[]" multiple>
                                <?php 
                                // Przechodzimy przez listę studentów
                                while ($student = mysqli_fetch_assoc($studentInfo)): ?>
                                    <option value="<?php echo $student['nr_albumu']; ?>">
                                        <?php echo $student['nr_albumu'] . ' - ' . $student['imie'] . ' ' . $student['nazwisko']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nazwa</label>
                            <input type="text" name="nazwa" class="form-control" required>
                        </div>

                        <button type="submit" class="btn btn-outline-danger w-100">Dodaj</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </main>    

    
    <script src="../../assets/js/modal_windows.js"></script>  
    <script src="../../assets/js/multi_select.js"></script>  

    <!-- multi_select.js --> 
    <script>
        new MultiSelectTag('studenci')  // id
    </script>
    <!-- multi_select.js --> 

</body>
</html>