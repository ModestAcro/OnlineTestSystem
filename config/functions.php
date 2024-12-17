<?php
    function getEntityCount($conn, $table) {
        $query = "SELECT COUNT(*) AS entityCount FROM $table";
        $result = mysqli_query($conn, $query);
        return mysqli_fetch_assoc($result)['entityCount'];
    }

    function getEntityInfo($conn, $table) {
        $query = "SELECT * FROM $table";
        return mysqli_query($conn, $query);
    }
?>