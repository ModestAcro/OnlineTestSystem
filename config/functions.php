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

     // Funkcja wyszukuje rekord w tabeli po ID
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


    // Funkcja zwraca liczbę grup w tabeli przypisanych do konkretnego nauczyciela
    function getGroupCountByTeacherId($conn, $table, $userId) {
        $query = "SELECT COUNT(*) FROM $table WHERE id_wykladowcy = '$userId'";
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
                ON g.ID = gs.id_grupy
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

    // Funkcja pobierająca listę grup studentów do konkretnego wykładowcy           
    function getStudentGroups($conn, $userId) {
        $query = "
            SELECT 
                g.ID,
                g.rok,
                g.nazwa AS nazwa_grupy,
                u.nazwa AS nazwa_uczelni, 
                p.nazwa AS przedmiot, 
                g.id_wykladowcy
            FROM tGrupy g
            JOIN tUczelnie u ON g.id_uczelni = u.ID
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
    
    
?>