<?php
    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $user_id = $_SESSION['user_id'];

    function getCompletedTestsCount($conn, $teacher_id){
        $query = "SELECT COUNT(*) AS liczba_zakonczonych_testow
                    FROM tTesty 
                    WHERE data_zakonczenia IS NOT NULL 
                    AND data_zakonczenia <= NOW()
                    AND id_wykladowcy = $teacher_id";
        
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($result)['liczba_zakonczonych_testow'];
    }

    $completedTestCount = getCompletedTestsCount($conn, $user_id);




    function getCompletedTestInfo($conn, $user_id){
        $query = "SELECT t.*, g.nazwa as nazwa_grupy, p.nazwa AS nazwa_przedmiotu, k.nazwa AS nazwa_kierunku
                    FROM tTesty t
                    JOIN tGrupy g ON t.id_grupy = g.ID
                    JOIN tPrzedmioty p ON t.id_przedmiotu = p.ID
                    JOIN tKierunki k ON t.id_kierunku = k.ID
                    LEFT JOIN tProbyTestu pt ON t.ID = pt.ID
                    WHERE t.id_wykladowcy = $user_id
                    AND t.data_zakonczenia <= NOW()
                    ORDER BY t.data_zakonczenia DESC";

        $result = mysqli_query($conn, $query);

        return $result;
    }
    
    $testInfo = getCompletedTestInfo($conn, $user_id);



    function getGroupDetails($conn, $test_id){
        $query = "
        SELECT 
            t.id_grupy, 
            g.ID AS id_grupy,
            g.nazwa AS nazwa_grupy,
            g.rok AS rok_grupy,
            COUNT(gs.id_studenta) AS liczba_studentów,
            GROUP_CONCAT(CONCAT(s.imie, ' ', s.nazwisko, ' (', s.nr_albumu, ')') SEPARATOR '; ') AS studenci
        FROM tTesty t
        JOIN tGrupy g ON t.id_grupy = g.ID
        LEFT JOIN tGrupyStudenci gs ON g.ID = gs.id_grupy
        LEFT JOIN tStudenci s ON gs.id_studenta = s.id
        WHERE t.id = $test_id
        GROUP BY t.id_grupy, g.ID, g.nazwa, g.rok;
    ";

    $result = mysqli_query($conn, $query);

    return $result;

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

    <title>Wyniki testów</title>
</head>
<body>

    <?php include '../../includes/header.php'; ?>

    <main class="main">
        <div class="container">
            <div class="title">
                <h1>Lista wykonanych testów</h1>
            </div>

            <p>Ilość: <?php echo $completedTestCount; ?></p>
            <table>
                <thead>
                    <tr>
                        <th style="width: 20%;">Nazwa</th>   <!-- Średnio dużo miejsca -->
                        <th style="width: 10%;">Kierunek</th>   <!-- Średnio dużo miejsca -->
                        <th style="width: 10%;">Przedmiot</th>       <!-- Krótki tekst -->
                        <th style="width: 10%;">Grupa</th> <!-- Dłuższy tekst -->
                        <th style="width: 25%;">Termin</th> <!-- Najwięcej miejsca dla dłuższego tekstu -->
                        <th style="width: 5%;"></th> <!-- Pusta kolumna -->
                    </tr>

                </thead>
                <tbody>
                    <?php while ($testData = mysqli_fetch_assoc($testInfo)): ?>
                        <tr>
                            <td><?php echo $testData['nazwa']; ?></td>
                            <td><?php echo $testData['nazwa_kierunku']; ?></td>
                            <td><?php echo $testData['nazwa_przedmiotu']; ?></td>
                            <td>
                                <?php
                                    $test_id = $testData['ID'];
                                    $groupDetails = getGroupDetails($conn, $test_id);
                                    if ($groupDetails && $group = mysqli_fetch_assoc($groupDetails)) {
                                        $studentList = $group['studenci']; // Lista studentów z funkcji getGroupDetails
                                        echo "<span class='group-name' data-students='$studentList'>{$group['nazwa_grupy']}</span>"; 
                                    } else {
                                        echo 'Brak grupy';
                                    }
                                ?>
                            </td>
                            <td><?php echo $testData['data_rozpoczecia'] . " / " .  $testData['data_zakonczenia']; ?></td>
                           
                            <!-- Przyciski "Modyfikuj" -->
                            <td>
                                <a href="test_details.php?test_id=<?php echo $testData['ID']; ?>" class="btn-edit">
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


    <!-- Plik JavaScript --> 
    <script src="../../assets/js/modal_windows.js"></script> 

</body>
</html>