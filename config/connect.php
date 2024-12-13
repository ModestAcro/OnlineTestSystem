<?php
// Dane do połączenia z bazą danych
$servername = "localhost";  // Nazwa serwera
$username = "root";         // Nazwa użytkownika bazy danych
$password = "root";             // Hasło użytkownika bazy danych
$dbname = "online_test_system";      // Nazwa bazy danych, z którą chcesz się połączyć

// Tworzymy połączenie
$conn = new mysqli($servername, $username, $password, $dbname);

/* Sprawdzamy połączenie
if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
} else {
    echo "Połączono z bazą danych!";
}*/

