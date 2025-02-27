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


    // Zapytanie o nazwę kierunku
    $courseQuery = "SELECT nazwa FROM tKierunki WHERE ID = $course_id";
    $courseResult = mysqli_query($conn, $courseQuery);

    // Jeśli zapytanie się powiodło, pobierz nazwę kierunku
    if ($courseResult) {
        $courseData = mysqli_fetch_assoc($courseResult);
        $courseName = $courseData['nazwa'];
    } else {
        $courseName = null;  // Jeśli nie znaleziono, ustaw null
    }

    // Zapytanie o nazwę predmiotu
    $subjectQuery = "SELECT nazwa FROM tPrzedmioty WHERE ID = $subject_id";
    $subjectResult = mysqli_query($conn, $subjectQuery);

    // Jeśli zapytanie się powiodło, pobierz nazwę kierunku
    if ($subjectResult) {
        $subjectData = mysqli_fetch_assoc($subjectResult);
        $subjectName = $subjectData['nazwa'];
    } else {
        $subjectName = null;  // Jeśli nie znaleziono, ustaw null
    }
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

    <title>Dodaj test</title>
</head>
<body>

    <?php include '../../includes/header.php'; ?>

   <main class="main my-5">
    <div class="container card shadow p-4">

        <form method="POST" action="../../includes/teacher/save_test.php">
            <!-- Przesyłanie ukrytych danych -->
            <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
            <input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>">

            <h1 class="fs-2 fs-md-3 fs-lg-5 mb-5">Utwórz test</h1>

            <!-- Kierunek testu -->
            <div class="mb-3">
                <h5 class="card-title fs-4 mt-2">Kierunek</h5>
                <input type="text" class="form-control" value="<?php echo $courseName?>" readonly>
            </div>

            <!-- Przedmiot testu -->
            <div class="mb-3">
                <h5 class="card-title fs-4 mt-2">Przedmiot</h5>
                <input type="text" class="form-control" value="<?php echo $subjectName?>" readonly>
            </div>

            <!-- Nazwa testu -->
            <div class="mb-3">
                <h5 class="card-title fs-4 mt-2">Nazwa</h5>
                <input type="text" class="form-control" name="nazwa" value="<?php echo isset($nazwa) ? $nazwa : ''; ?>" required>
            </div>

            <!-- Limit czasowy -->
            <div class="mb-3">
                <h5 class="card-title fs-4 mt-2">Okres dostępności</h5>

                <!-- Przełącznik (switch) do włączania/wyłączania limitu czasowego -->
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="limitSwitch">
                    <label class="form-check-label" for="limitSwitch">Włącz/Wyłącz</label>
                </div>

                <!-- Sekcja z wyborem daty, domyślnie ukryta -->
                <div id="timeLimitSection" class="row g-2 mt-2 d-none">
                    <div class="col-md-6">
                        <label class="form-label">Od:</label>
                        <input type="datetime-local" class="form-control" name="start-time">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Do:</label>
                        <input type="datetime-local" class="form-control" name="end-time">
                    </div>
                </div>
            </div>

            <!-- Skrypt do obsługi przełącznika -->
            <script>
                document.getElementById('limitSwitch').addEventListener('change', function() {
                    const timeLimitSection = document.getElementById('timeLimitSection');
                    if (this.checked) {
                        timeLimitSection.classList.remove('d-none');
                    } else {
                        timeLimitSection.classList.add('d-none');
                    }
                });
            </script>


            <!-- Czas trwania -->
            <div class="mb-3">
                <h5 class="card-title fs-4 mt-2">Czas trwania (w minutach)</h5>
                <input type="number" class="form-control" name="test-time" placeholder="Wpisz liczbę minut">
            </div>

            <!-- Ilość prób -->
            <div class="mb-3">
                <h5 class="card-title fs-4 mt-2">Ilość prób</h5>

                <!-- Opcja: Nieograniczona liczba prób -->
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="attempts" value="unlimited" id="attemptsUnlimited" checked>
                    <label class="form-check-label" for="attemptsUnlimited">Nieograniczona liczba</label>
                </div>

                <!-- Opcja: Jedno podejście -->
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="attempts" value="one" id="attemptsOne">
                    <label class="form-check-label" for="attemptsOne">Jedno podejście</label>
                </div>

                <!-- Opcja: Wiele podejść -->
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="attempts" value="multiple" id="attemptsMultiple">
                    <label class="form-check-label" for="attemptsMultiple">Wiele podejść</label>
                </div>

                <!-- Pole do wpisania liczby prób (domyślnie ukryte) -->
                <div class="mt-2 d-none" id="attemptsNumberSection">
                    <input type="number" class="form-control" id="number-of-attempts" name="number-of-attempts" min="1" placeholder="Wpisz liczbę">
                </div>
            </div>

            <!-- Skrypt do obsługi przełączania -->
            <script>
                document.querySelectorAll('input[name="attempts"]').forEach(function(radio) {
                    radio.addEventListener('change', function() {
                        const attemptsNumberSection = document.getElementById('attemptsNumberSection');

                        if (this.value === 'multiple') {
                            attemptsNumberSection.classList.remove('d-none'); // Pokaż pole do liczby prób
                        } else {
                            attemptsNumberSection.classList.add('d-none'); // Ukryj pole do liczby prób
                        }
                    });
                });
            </script>


            <!-- Wybór grupy -->
            <div class="mb-3">
                <h5 class="card-title fs-4 mt-2">Grupa</h5>
                <select class="form-select" name="grupa" required>
                    <option disabled selected>Wybierz grupę</option>
                    <?php while ($groups = mysqli_fetch_assoc($groupsInfo)): ?>
                        <option value="<?php echo $groups['ID']; ?>">
                            <?php echo $groups['rok'] . ', ' . $groups['nazwa_kierunku'] . ', ' . $groups['nazwa_przedmiotu'] . ', ' . $groups['nazwa']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Wybór pytań -->
            <div class="mb-3">
                <h5 class="card-title fs-4 mt-2">Pytania</h5>
                <select id="pytania" name="pytania[]" class="form-select" multiple>
                    <?php while ($questions = mysqli_fetch_assoc($questionsInfo)): ?>
                        <option value="<?php echo $questions['ID']; ?>">
                            <?php echo $questions['tresc']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <!-- Przycisk zapisu -->
            <div class="d-grid">
                <button type="submit" name="action" value="save" class="btn btn-outline-danger">Zapisz</button>
            </div>
        </form>
    </div>
</main>

    <!-- Plik javascript -->
    <script src="../../assets/js/multi_select.js"></script>


    <!-- multi_select.js --> 
    <script>
        new MultiSelectTag('pytania')  // id
    </script>
    <!-- multi_select.js --> 

</body>
</html>
