<?php

    //<=============== GLOBALNE FUNKCJE ===============>//

    // Funkcja zwraca liczbę rekordów w tabeli              
    function getTableCount($conn, $table) {
        $query = "SELECT COUNT(*) AS tableCount FROM $table";
        $result = mysqli_query($conn, $query);

        return mysqli_fetch_assoc($result)['tableCount'];
    }

    // Funkcja zwraca wszystkie rekordy z tabeli
    function getTableInfo($conn, $table) {
        $query = "SELECT * FROM $table";

        return mysqli_query($conn, $query);
    }

     // Funkcja wyszukuje rekord w tabeli po ID
     function getRecordById($conn, $table, $id) {
        $query = "
                    SELECT 
                        * 
                    FROM 
                        $table 
                    WHERE ID = $id";

        $result = mysqli_query($conn, $query);
        $record = mysqli_fetch_assoc($result);
        if (!$record) {
            echo "Nie znaleziono rekordu w tabeli $table.";
            exit;
        }
        
        return $record;  
    }

    //<=============== FUNKCJE DLA ADMINISTRATORA ===============>//

    // Funkcja do pobierania nazwy kierunków dla wykładowcy
    function getCoursesNames($conn, $teacherId) {
        $query = "  SELECT 
                        tKierunki.nazwa 
                    FROM 
                        tWykladowcyKierunki 
                    JOIN 
                        tKierunki ON tWykladowcyKierunki.id_kierunku = tKierunki.ID 
                    WHERE 
                        tWykladowcyKierunki.id_wykladowcy = '$teacherId'";

        $result = mysqli_query($conn, $query);
        if (!$result) {
            die("Błąd zapytania: " . mysqli_error($conn));
        }

        return $result;
    }

    // Funkcja pobierająca kierunki które przepisane do Wykładowcy
    function getCoursesByTeacher($conn, $teacher_id) {
        $query = "SELECT 
                    k.ID, 
                    k.nazwa,
                    k.uwagi
                FROM 
                    tWykladowcyKierunki wk
                JOIN 
                    tKierunki k
                    ON wk.id_kierunku = k.ID
                WHERE 
                    wk.id_wykladowcy = $teacher_id";
        
        $result = mysqli_query($conn, $query);
        $courses = [];
        
        while ($row = mysqli_fetch_assoc($result)) {
            $courses[] = [
                'id_kierunku' => $row['ID'],
                'nazwa' => $row['nazwa'],
                'uwagi' => $row['uwagi']
            ];
        }
        
        return $courses;
    }
    
    //
    function getStudentsWithCourses($conn) {
        // Zapytanie SQL, które łączy tabele tStudenci i tKierunki
        $query = "
                SELECT 
                    s.ID, s.nr_albumu, s.id_kierunku, s.rok, s.imie, s.nazwisko, s.email, s.aktywny, s.uwagi, k.nazwa 
                AS 
                    kierunek_nazwa
                FROM 
                    tStudenci s
                JOIN 
                    tKierunki k ON s.id_kierunku = k.ID
        ";
    
        // Wykonanie zapytania
        $studentInfo = mysqli_query($conn, $query);
    
        // Sprawdzanie, czy zapytanie zwróciło jakiekolwiek wyniki
        if (!$studentInfo) {
            die("Błąd zapytania: " . mysqli_error($conn));
        }
    
        return $studentInfo;
    }



    // Funkcja zwraca liczbę grup w tabeli przypisanych do konkretnego nauczyciela
    function getGroupCountByTeacherId($conn, $table, $userId) {
        $query = "  SELECT 
                        COUNT(*) 
                    FROM 
                        $table 
                    WHERE 
                        id_wykladowcy = '$userId'";

        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_row($result);
        return $row[0];
    }

    // Funkcja zliczająca liczbę studentów przypisanych do każdej grupy  
    function getStudentCountByGroup($conn, $userId) {
        $query = "
                SELECT 
                    g.ID AS group_id, 
                    COUNT(gs.id_studenta) AS student_count
                FROM 
                    tGrupy g
                LEFT JOIN 
                    tGrupyStudenci gs 
                ON 
                    g.ID = gs.id_grupy
                WHERE 
                    g.id_wykladowcy = '$userId'
                GROUP BY 
                    g.ID;
        ";

        $result = mysqli_query($conn, $query);
        $data = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $data[$row['group_id']] = $row['student_count'];
        }

        return $data;
    }

    // Funkcja zliczająca sumę przedmiotów przypisanych do konkretnego kierunku 
    function getSubjectCountByCourse($conn, $courseId) {
        // Zapytanie SQL
        $query = "
            SELECT 
                COUNT(*) AS subject_count
            FROM 
                tKierunkiPrzedmioty
            WHERE 
                id_kierunku = $courseId
        ";
        
        // Wykonanie zapytania
        $result = mysqli_query($conn, $query);
        
        // Pobranie wyniku
        $row = mysqli_fetch_assoc($result);
        
        // Zwracanie liczby przedmiotów (jeśli wynik nie istnieje, zwraca 0)
        if (isset($row['subject_count'])) {
            return $row['subject_count'];
        } else {
            return 0;
        }
        
    }
    


    

    // Funkcja pobierająca listę grup studentów do konkretnego wykładowcy           
    function getStudentGroups($conn, $userId) {
        $query = "
            SELECT 
                g.ID,
                g.rok,
                g.nazwa AS nazwa_grupy,
                u.nazwa AS nazwa_kierunku, 
                p.nazwa AS przedmiot, 
                g.id_wykladowcy
            FROM tGrupy g
            JOIN tKierunki u ON g.id_kierunku = u.ID
            JOIN tPrzedmioty p ON g.id_przedmiotu = p.ID
            WHERE g.id_wykladowcy = '$userId'
        ";

        $result = mysqli_query($conn, $query);

        return $result;
    }

    // Funkcja zwraca wszystkie rekordy z tabeli
    function getTableInfoByUserId($conn, $table, $userId) {
        $query = "SELECT * FROM $table WHERE id_wykladowcy = $userId";

        return mysqli_query($conn, $query);
    }

    // Funkcja pobierająca studentów w konkretnej grupie
    function getStudentsByGroupId($conn, $groupId) {
        $query = "SELECT 
                    s.ID, 
                    s.nr_albumu,
                    s.imie,
                    s.nazwisko 
                FROM 
                    tGrupyStudenci gs
                JOIN 
                    tStudenci s 
                    ON gs.id_studenta = s.ID
                WHERE 
                    gs.id_grupy = $groupId";
        
        $result = mysqli_query($conn, $query);
        $students = [];
        
        while ($row = mysqli_fetch_assoc($result)) {
            $students[] = [
                'id_studenta' => $row['ID'],
                'nr_albumu' => $row['nr_albumu'],
                'imie' => $row['imie'],
                'nazwisko' => $row['nazwisko']
            ];
        }
        
        return $students;
    }


    function getRecordNameById($conn, $table, $id) {
        $query = "SELECT nazwa FROM $table WHERE ID = $id";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            return $row['nazwa'];
        } else {
            return null;
        }        
    }

//########################################################################################################

    // <!-- tTesty -->

    // Funkcja zwraca liczbę testów w tabeli przywiazanych do konkretnego nauczyciela
    function getTestCountByTeacherId($conn, $table, $userId) {
        $query = "SELECT COUNT(*) AS entityCountById FROM $table WHERE id_wykladowcy = '$userId'";
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($result)['entityCountById'];
    }


    // Funkcja pobierająca listę testów dla konkretnego wykładowcy
    function getTestsByTeacherId($conn, $userId) {
        $query = "
            SELECT * 
            FROM tTesty 
            WHERE id_wykladowcy = '$userId'
        ";

        $result = mysqli_query($conn, $query);

        if (!$result) {
            die("Błąd zapytania: " . mysqli_error($conn));
        }

        return $result;
    }





    // Funkcja pobierająca przedmioty w konkretnym kierunku
    function getSubjectsByKierunek($conn, $kierunekId) {
        $query = "SELECT 
                    p.ID, 
                    p.nazwa,
                    p.uwagi
                FROM 
                    tKierunkiPrzedmioty ks
                JOIN 
                    tPrzedmioty p
                    ON ks.id_przedmiotu = p.ID
                WHERE 
                    ks.id_kierunku = $kierunekId";
        
        $result = mysqli_query($conn, $query);
        $subjects = [];
        
        while ($row = mysqli_fetch_assoc($result)) {
            $subjects[] = [
                'id_przedmiotu' => $row['ID'],
                'nazwa' => $row['nazwa'],
                'uwagi' => $row['uwagi']
            ];
        }
        
        return $subjects;
    }




    
    
?>