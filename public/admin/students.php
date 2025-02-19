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
    <link rel="stylesheet" href="../../assets/css/main.css">

    <title>Studeńci</title>
</head>
<body>

    <?php include '../../includes/header.php'; ?>
    
    <main class="main">
        <div class="container">
            <div class="title">
                <h1>Lista Studentów</h1>

                <!-- Przycisk "Dodaj Studenta" -->
                <button class="add-btn" onclick="addEntity()">
                    <img src="../../assets/images/icons/plus.svg" class="add-icon">
                </button>
                <!-- Przycisk "Dodaj Studenta" -->

                <!-- Okno modalne dodaj Studenta-->
                <div id="openModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" id="closeModal">&times;</span>
                        <h1 class="modal-header">Dodaj Studenta</h1>
                        <form action="../../includes/admin/save_student.php" method="POST">

                            <label>Nr. albumu</label>
                            <input type="text" name="nrAlbumuStudenta" required>

                            <!-- Lista kierunków do przypisania -->
                            <label>Kierunek</label>
                                <select name="kierunekStudenta" required>
                                    <option value="" disabled selected>Wybierz kierunek</option>
                                        <?php while ($course = mysqli_fetch_assoc($courseInfo)): ?>
                                            <option value="<?php echo $course['ID']; ?>">
                                                <?php echo $course['nazwa']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                </select>
                            <!-- Lista kierunków do przypisania -->

                            <label>Rok</label>
                            <input type="number" name="rokStudenta" required>

                            <label>Rocznik</label>
                            <input type="number" name="rocznikStudenta" required>

                            <label>Imię</label>
                            <input type="text" name="imieStudenta" required>

                            <label>Nazwisko</label>
                            <input type="text" name="nazwiskoStudenta" required>

                            <label>Email</label>
                            <input type="email" name="emailStudenta" required>

                            <label>Hasło</label>
                            <input type="password" name="hasloStudenta" required>

                            <label>Uwagi</label>
                            <textarea name="uwagiStudenta"></textarea>

                            <button type="submit" class="submit-btn">Dodaj studenta</button>
                        </form>
                    </div>
                </div>
                <!-- Okno modalne dodaj Studenta-->
            </div>

            <p>Ilość: <?php echo $studentCount; ?></p>
            <table>
                <thead>
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
                        <th></th>
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

                            <!-- Przyciski "Modyfikuj" -->
                            <td>
                                <a href="edit_student.php?student_id=<?php echo $studentData['ID']; ?>" class="btn-edit">
                                    <img src="../../assets/images/icons/edit.svg" class="edit-icon">
                                </a>
                            </td>
                            <!-- Przyciski "Modyfikuj" -->

                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
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