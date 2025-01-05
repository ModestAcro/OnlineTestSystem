<?php

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

    // Funkcja zwraca pojedynczy rekord na podstawie ID
    function getRecordById($conn, $table, $id) {
        $query = "SELECT * FROM $table WHERE ID = $id";
        $result = mysqli_query($conn, $query);
        $record = mysqli_fetch_assoc($result);
        if (!$record) {
            echo "Nie znaleziono rekordu w tabeli $table.";
            exit;
        }
        
        return $record;  
    }

    // Funkcja zwraca liczbę grup w tabeli przywiazanych do konkretnego nauczyciela
    function getGroupCountByTeacherId($conn, $table, $userId) {
        $query = "SELECT COUNT(*) AS entityCountById FROM $table WHERE id_wykladowcy = '$userId'";
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($result)['entityCountById'];
    }

    // Funkcja zliczająca liczbę studentów przypisanych do kazdej grupy  
    function getStudentCountByGroup($conn, $userId) {
        $query = "
            SELECT g.ID, COUNT(gs.id_studenta) AS student_count
            FROM tGrupy g
            LEFT JOIN tGrupyStudenci gs ON g.ID = gs.id_grupy
            WHERE g.id_wykladowcy = '$userId'
            GROUP BY g.ID
        ";
    
        $result = mysqli_query($conn, $query);
        $data = [];
    
        foreach (mysqli_fetch_all($result, MYSQLI_ASSOC) as $row) {
            $data[$row['ID']] = $row['student_count'];
        }
    
        return $data;
    }

    // Funkcja pobierająca listę grup studentów do konkretnego wykładowcy
    function getStudentGroups($conn, $userId) {
        $query = "
            SELECT g.ID, g.rok, g.nazwa AS nazwa_grupy, u.nazwa AS nazwa_uczelni, p.nazwa AS przedmiot, g.id_wykladowcy
            FROM tGrupy g
            JOIN tUczelnie u ON g.id_uczelni = u.ID
            JOIN tPrzedmioty p ON g.id_przedmiotu = p.ID
            WHERE g.id_wykladowcy = '$userId'
        ";

        $result = mysqli_query($conn, $query);
        
        if (!$result) {
            die("Błąd zapytania: " . mysqli_error($conn));
        }

        return $result;
    }

    // Funkcja pobierająca studentów w konkretnej grupie
    function getStudentsByGroupId($conn, $groupId) {
        $sql = "SELECT s.ID, s.nr_albumu, s.imie, s.nazwisko 
                FROM tGrupyStudenci gs
                JOIN tStudenci s ON gs.id_studenta = s.ID
                WHERE gs.id_grupy = $groupId";
        
        $result = mysqli_query($conn, $sql);
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

    

    function getEntityNameById($conn, $table, $id) {
        $result = mysqli_query($conn, "SELECT nazwa FROM $table WHERE id = $id");
        $row = mysqli_fetch_assoc($result);
        return $row ? $row['nazwa'] : null;
    }


   // Lista grup przypisanych do konkretnego nauczyciela
    function getGroupsByTeacher($conn, $teacher_id) {
        $sql = "
            SELECT 
                tGrupy.ID AS grupa_id, 
                tGrupy.rok AS rok,
                tUczelnie.nazwa AS uczelnia,
                tPrzedmioty.nazwa AS przedmiot,
                tGrupy.nazwa AS grupa_nazwa,
                COUNT(tGrupyStudenci.id_studenta) AS liczba_studentow
            FROM tGrupy
            LEFT JOIN tUczelnie ON tGrupy.id_uczelni = tUczelnie.ID
            LEFT JOIN tPrzedmioty ON tGrupy.id_przedmiotu = tPrzedmioty.ID
            LEFT JOIN tGrupyStudenci ON tGrupy.ID = tGrupyStudenci.id_grupy
            WHERE tGrupy.id_wykladowcy = $teacher_id  -- Warunek dla nauczyciela
            GROUP BY tGrupy.ID, tGrupy.rok, tUczelnie.nazwa, tPrzedmioty.nazwa, tGrupy.nazwa;
        ";

        // Wykonanie zapytania
        $result = $conn->query($sql);

        // Jeśli zapytanie się powiodło, przygotuj dane
        if ($result && $result->num_rows > 0) {
            $groups = [];
            while ($row = $result->fetch_assoc()) {
                $groups[] = $row;
            }
            return $groups;
        }

        // Jeśli nie ma wyników lub zapytanie się nie powiodło, zwróć pustą tablicę
        return [];
    }

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

    
    
?>