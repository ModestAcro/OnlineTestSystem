<?php

    $characterId = $_SESSION['ID'];

    function getEntityCount($conn, $table) {
        $query = "SELECT COUNT(*) AS entityCount FROM $table";
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($result)['entityCount'];
    }

    function getEntityInfo($conn, $table) {
        $query = "SELECT * FROM $table";
        return mysqli_query($conn, $query);
    }

    function getEntityCountByCharacter($conn, $table, $characterId) {
        $query = "SELECT COUNT(*) AS entityCount FROM $table WHERE id_wykladowcy = '$characterId'";
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($result)['entityCount'];
    }
    
    function getEntityInfoByCharacter($conn, $table, $characterId) {
        $query = "SELECT * FROM $table WHERE id_wykladowcy = '$characterId'";
        return mysqli_query($conn, $query);
    }

    // Funkcja do pobrania grup na podstawie ID wykładowcy
    function getGroupById($conn, $groupId) {
    $query = "SELECT * FROM tGrupy WHERE ID = '$groupId'"; 
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        return false; 
    }
    
        return mysqli_fetch_assoc($result); 
    }

    // Funkcja zliczająca liczbę studentów przypisanych do grupy
    function getStudentCountByGroup($conn, $characterId) {
    $studentCountQuery = "
        SELECT g.ID, COUNT(gs.id_studenta) AS student_count
        FROM tGrupy g
        LEFT JOIN tGrupyStudenci gs ON g.ID = gs.id_grupy
        WHERE g.id_wykladowcy = '$characterId'
        GROUP BY g.ID
    ";
    
    $studentCountResult = mysqli_query($conn, $studentCountQuery);
    $studentCountData = [];

    while ($row = mysqli_fetch_assoc($studentCountResult)) {
        $studentCountData[$row['ID']] = $row['student_count'];
    }
    
        return $studentCountData;
    }


    function getStudentsByGroupId($conn, $groupId) {
        $sql = "
            SELECT s.ID, s.nr_albumu, s.imie, s.nazwisko 
            FROM tGrupyStudenci gs
            JOIN tStudenci s ON gs.id_studenta = s.ID
            WHERE gs.id_grupy = ?
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $groupId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $students = [];
        while ($row = $result->fetch_assoc()) {
            // Przechowujemy dane studenta, a nie tylko ID
            $students[] = [
                'id_studenta' => $row['ID'],
                'nr_albumu' => $row['nr_albumu'],
                'imie' => $row['imie'],
                'nazwisko' => $row['nazwisko']
            ];
        }
        
        return $students;
    }    
    
    
?>