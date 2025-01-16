<?php
// Zaczynamy sesję
session_start();

// Usuwamy wszystkie zmienne sesyjne
session_unset();

// Zniszczamy sesję
session_destroy();

// Przekierowanie użytkownika na stronę logowania
header("Location: ../public/index.php");
exit();
?>
