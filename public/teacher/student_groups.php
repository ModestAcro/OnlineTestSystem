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
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Grupy studentów</title>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="left-header">
                <a class="nav-btn" href="teacher_dashboard.php">Strona główna</a>
            </div>
            <div class="right-header">
                <span class="name"><?php echo $_SESSION['user_name'] . ' ' . $_SESSION['user_surname']; ?></span>

                <!-- Formularz wylogowania -->
                <?php
                    include('../../includes/logout_modal.php');
                ?>
                <!-- Formularz wylogowania -->

            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <div class="title">
                <h1>Grupy studentów</h1>

                <!-- Przycisk "Dodaj Grupę" -->
                <button class="add-btn" onclick="addEntity()">
                    <img src="../../assets/images/icons/plus.svg" alt="Plus icon" class="add-icon">
                </button>
                <!-- Przycisk "Dodaj Grupę" -->

                <!-- Okno modalne dodaj gupę -->
                <div id="openModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" id="closeModal">&times;</span>
                        <h1 class="modal-header">Dodaj grupę</h1>
                        <form action="../../includes/teacher/save_group.php" method="POST">

                            <label>Rok</label>
                            <input type="text" pattern="\d{4}" id="rok" name="rok" required>

                            <!-- Lista kierunków do przypisania -->
                            <label>Kierunek</label>
                            <select name="uczelnia" required>
                                <option disabled selected>Wybierz kierunek</option>
                                <?php while ($kierunek = mysqli_fetch_assoc($kierunekInfo)): ?>
                                    <option value="<?php echo $kierunek['ID']; ?>">
                                        <?php echo $kierunek['nazwa']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <!-- Lista kierunków do przypisania -->


                            <!-- Lista przedmiotów do przypisania -->
                            <label>Przedmiot</label>
                            <select name="przedmiot" required>
                                <option disabled selected>Wybierz przedmiot</option>
                                <?php while ($subject = mysqli_fetch_assoc($subjectInfo)): ?>
                                    <option value="<?php echo $subject['ID']; ?>">
                                        <?php echo $subject['nazwa']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <!-- Lista przedmiotów do przypisania -->


                            <!-- Lista studentów do przypisania -->
                            <label>Wybierz studentów</label>
                            <select id="studenci" name="studenci[]" multiple>
                                <?php 
                                // Przechodzimy przez listę studentów
                                while ($student = mysqli_fetch_assoc($studentInfo)): ?>
                                    <option value="<?php echo $student['nr_albumu']; ?>">
                                        <?php echo $student['nr_albumu'] . ' - ' . $student['imie'] . ' ' . $student['nazwisko']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <!-- Lista studentów do przypisania -->

                            <label>Nazwa</label>
                            <input type="text" name="nazwa" required>

                            <button type="submit" class="submit-btn">Dodaj grupę</button>
                        </form>
                    </div>
                </div>
                <!-- Okno modalne dodaj grupę-->
            </div>

            <p>Ilość: <?php echo $studentGroupCount; ?></p>
            <table>
                <thead>
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
                                <a href="edit_group.php?group_id=<?php echo $groupData['ID']; ?>" class="btn-edit">
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

    
    <script src="../../assets/js/modal_windows.js"></script>  
    <script src="../../assets/js/multi_select.js"></script>  

    <!-- multi_select.js --> 
    <script>
        new MultiSelectTag('studenci')  // id
    </script>
    <!-- multi_select.js --> 

</body>
</html>