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

    // Funkcja do pobrania grupy na podstawie ID
    function getGroupById($conn, $groupId) {
    $query = "SELECT * FROM tGrupy WHERE ID = '$groupId'"; 
    $result = mysqli_query($conn, $query);
    
    if (!$result) {
        return false; 
    }
    
    return mysqli_fetch_assoc($result); 
    }

    
?>