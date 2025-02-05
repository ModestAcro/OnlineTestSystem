<?php
    session_start();
    

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nazwa = $_POST['nazwa']; // Pobiera wartość z pola tekstowego "nazwa"
        $course_id = $_POST['kierunek']; // Pobiera wartość z selecta "kierunek"
        $subject_id = $_POST['przedmiot']; // Pobiera wartość z selecta "przedmiot"
    }



    require_once('../../config/connect.php');
    require_once('../../config/functions.php');


    $user_id = $_SESSION['user_id'];


    
   // Funkcja zwraca pytania dla konkretnego nauczyciela
    function getQuestionsByTeacher($conn, $user_id) {
        // Zapytanie SQL, które filtruje pytania po id_wykladowcy (nauczyciel)
        $query = "SELECT * FROM tPytania WHERE id_wykladowcy = '$user_id'";

        // Wykonanie zapytania i zwrócenie wyniku
        return mysqli_query($conn, $query);
    }



    // Funkcja do pobierania kierunków przypisanych do nauczyciela
    function getCoursesByTeacher2($conn, $user_id) {
        // Zapytanie SQL do pobrania kierunków przypisanych do nauczyciela
        $sql = "SELECT k.nazwa, k.ID
                FROM tKierunki k
                JOIN twykladowcykierunki t ON t.id_kierunku = k.ID
                WHERE t.id_wykladowcy = $user_id";  // Wstawienie $teacherId bezpośrednio w zapytaniu
        
        // Wykonanie zapytania
        $result = mysqli_query($conn, $sql);

        return $result;
    }

    function getSubjectsByTeacher($conn, $user_id) {
        $sql = "SELECT p.nazwa, p.ID
                FROM tPrzedmioty p
                JOIN tKierunkiPrzedmioty kp ON p.ID = kp.id_przedmiotu
                JOIN tKierunki k ON kp.id_kierunku = k.ID
                JOIN tWykladowcyKierunki wk ON wk.id_kierunku = k.ID
                WHERE wk.id_wykladowcy = $user_id";
    
        $result = mysqli_query($conn, $sql);
        
        return $result;
    }

    function getGroupByCourseAndSubjectAndTeacher($conn, $course_id, $subject_id, $user_id) {
        $query = "SELECT tGrupy.*, tKierunki.nazwa AS nazwa_kierunku, tPrzedmioty.nazwa AS nazwa_przedmiotu
                  FROM tGrupy
                  JOIN tKierunki ON tGrupy.id_kierunku = tKierunki.ID
                  JOIN tPrzedmioty ON tGrupy.id_przedmiotu = tPrzedmioty.ID
                  WHERE tGrupy.id_wykladowcy = $user_id 
                    AND tGrupy.id_kierunku = $course_id 
                    AND tGrupy.id_przedmiotu = $subject_id";
    
        $result = mysqli_query($conn, $query);
    
        return $result;
    }
    


    $subjectsByTeacher = getSubjectsByTeacher($conn, $user_id);

    $courseByTeacher = getCoursesByTeacher2($conn, $user_id);

    $questionsByTeacher = getQuestionsByTeacher($conn, $user_id);

    $groupsInfo = getGroupByCourseAndSubjectAndTeacher($conn, $course_id, $subject_id, $user_id);


    function getQuestionsByTeacherAndSubject($conn, $user_id, $subject_id) {
        $query = "SELECT * 
                  FROM tPytania
                  WHERE id_wykladowcy = $user_id AND id_przedmiotu = $subject_id";
    
        $result = mysqli_query($conn, $query);
    
        return $result;
    }

    $questionsInfo = getQuestionsByTeacherAndSubject($conn, $user_id, $subject_id);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Dodaj test</title>
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
                <h1>Ustawienia ogólne</h1>
            </div>

            <form method="POST" action="../../includes/teacher/save_test.php">

             <!-- Przesyłanie ukrytych danych (user_id, course_id, subject_id) -->
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
            <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>">

            <!-- Nazwa -->
            <h4 class="h4-margin">Nazwa</h4>

            <div class="radio">
                <div class="radio-section">
                    <input type="text" name="nazwa" value="<?php echo isset($nazwa) ? $nazwa : ''; ?>" required>
                </div>
            </div>
    
            <div class="radio">
                <div class="radio-section">
            
                    <div class="radio-input">
                        <input type="radio" name="limit" value="date">
                        <label>Limit czasowy</label>
                    </div>

                    <!-- Wybór daty i godziny dla limitu czasowego -->
                    <div class="time-limited-options">

                        <div class="time-limit-input">
                            <label>Od:</label>
                            <input type="datetime-local" id="start-time" name="start-time">
                        </div>

                        <div class="time-limit-input">
                            <label>Do:</label>
                            <input type="datetime-local" id="end-time" name="end-time">
                        </div>
             

                    </div>
                    <!-- Wybór daty i godziny dla limitu czasowego -->
                </div>

                <div class="radio-section">
                    <div class="radio-input">
                        <input type="radio" name="limit" value="unlimited">
                        <label>Bez limitu</label>
                    </div>
                </div>
            </div>

            <!-- Czas trawania -->
            <h4 class="h4-margin">Czas trwania testu</h4>

            <div class="radio">
                <div class="radio-section">
                <input type="number" placeholder="W minutach" name="test-time">
                </div>
            </div>

             <!-- Ilość pobr -->
             <h4 class="h4-margin">Ilość prób</h4>

            <div class="radio">
                <div class="radio-section">
                    <div class="radio-input">
                        <input type="radio" name="attempts" value="unlimited" id="attempts-unlimited">
                        <label for="attempts-unlimited">Nieograniczona liczba</label>
                    </div>
                </div>

                <div class="radio-section">
                    <div class="radio-input">
                        <input type="radio" name="attempts" value="one" id="attempts-one">
                        <label for="attempts-one">Jedno podejście</label>
                    </div>
                </div>

                <div class="radio-section">
                    <div class="radio-input">
                        <input type="radio" name="attempts" value="multiple" id="attempts-multiple">
                        <label for="attempts-multiple">Wiele podejść</label>
                    </div>

                    <div class="time-limited-options">
                        <div class="time-limit-input">
                            <label for="number-of-attempts">Liczba</label>
                            <input type="number" id="number-of-attempts" name="number-of-attempts">
                        </div>
                    </div>
                </div>
            </div>


            <div class="title">
                <h1 style="margin: 20px 0px 20px 0px">Grupa</h1>
            </div>

            <!-- Lista grup do przypisania -->
                <select name="grupa" required>
                    <option disabled selected>Wybierz grupę</option>
                    <?php while ($groups = mysqli_fetch_assoc($groupsInfo)): ?>
                        <option value="<?php echo $groups['ID']; ?>">
                        <?php echo $groups['rok'] . ', ' . $groups['nazwa_kierunku'] . ', ' . $groups['nazwa_przedmiotu'] . ', ' . $groups['nazwa']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            <!-- Lista grup do przypisania -->



            <!--

            <div class="title">
                <h1 style="margin: 20px 0px 20px 0px">Ustawienia pytań</h1>
            </div>

            <div class="radio">
                <div class="radio-section">
                    <div class="radio-input">
                        <input type="checkbox">
                        <label>Losuj opcje pytań</label>
                    </div>
                </div>

                <div class="radio-section">
                    <div class="radio-input">
                        <input type="checkbox">
                        <label>Losuj pytania</label>
                    </div>

                    <div class="time-limited-options">
                        <div class="time-limit-input">
                            <label>Liczba</label>
                            <input type="number">
                        </div>
                    </div>

                </div>
            </div>

            -->


            <div class="title">
                <h1 style="margin: 20px 0px 20px 0px">Pytania</h1>
            </div>
                <!-- Lista pytań do przypisania -->
                <label>Wybierz pytania</label>
                <select id="pytania" name="pytania[]" multiple>
                    <?php 
                    // Przechodzimy przez listę pytań
                    while ($questions = mysqli_fetch_assoc($questionsInfo)): ?>
                        <option value="<?php echo $questions['ID']; ?>">
                            <?php echo $questions['tresc']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <input type="submit" value="Zapisz">

            </form>




            <!-- Question card 
            <div class="radio" style="margin-top: 20px;">
                <div class="question-card">
                    <div class="question-left">

                        <div class="question-top">
                            <div class="number">
                                <label>1/3</label>
                                <label>Multiple choice</label>
                            </div>
                            <div class="points">
                                <label>10</label>
                            </div>
                        </div>

                        <div class="question-top">
                            <div class="question">
                                <label>2 + 2 = ?</label>
                            </div>
                            <div class="answer">
                                <label>Odpowiedź: 4</label>
                            </div>
                        </div>

                    </div>

                    <div class="question-right">
                        <div class="question-action" id="edit-question-btn">
                            <button>Edytuj</button>
                        </div>
                        <div class="question-action"  id="delete-question-btn">
                            <button>Usuń</button>
                        </div>
                    </div>
                </div>
            </div>
             Question card -->
             

        </div>
    </main>    


    <!-- Plik javascript -->
    <script src="../../assets/js/modal_windows.js"></script> 
    <script src="../../assets/js/multi_select.js"></script>


    <!-- multi_select.js --> 
    <script>
        new MultiSelectTag('pytania')  // id
    </script>
    <!-- multi_select.js --> 

</body>
</html>
