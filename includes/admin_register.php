<?php
// Połączenie z bazą danych
require_once('../config/connect.php');

// Dane administratora
$imie = $_POST['imie'];
$nazwisko = $_POST['nazwisko'];
$email = $_POST['email'];
$haslo = $_POST['haslo'];

// Haszowanie hasła
$haslo_hash = password_hash($haslo, PASSWORD_BCRYPT);

// Przygotowanie zapytania SQL
$query = "INSERT INTO tAdministratorzy (imie, nazwisko, email, haslo) 
          VALUES ('$imie', '$nazwisko', '$email', '$haslo_hash')";

// Wykonanie zapytania
if (mysqli_query($conn, $query)) {
    echo "Administrator został dodany do bazy danych.";
} else {
    echo "Błąd: " . mysqli_error($conn);
}
?>
