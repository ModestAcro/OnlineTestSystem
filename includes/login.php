<?php
session_start();
require_once('../config/connect.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Proste zapytanie SQL z aliasem 'role'
    $query = "SELECT email, haslo, imie, nazwisko, 'administrator' AS role FROM tAdministratorzy WHERE email = '$email' AND haslo = '$password'
              UNION
              SELECT email, haslo, imie, nazwisko, 'student' AS role FROM tStudenci WHERE email = '$email' AND haslo = '$password'
              UNION
              SELECT email, haslo, imie, nazwisko, 'teacher' AS role FROM tWykladowcy WHERE email = '$email' AND haslo = '$password'";

    // Wykonanie zapytania
    $result = mysqli_query($conn, $query);
    
    // Sprawdzenie, czy wynik zapytania zwrócił jakikolwiek rekord
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Jeśli znaleziono użytkownika, zapisujemy dane do sesji
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role']; // Rola użytkownika jest teraz w wyniku zapytania
        $_SESSION['imie'] = $user['imie'];
        $_SESSION['nazwisko'] = $user['nazwisko'];


        // Przekierowanie do odpowiedniej strony
        if ($_SESSION['role'] === 'administrator') {
            header("Location: ../public/admin/admin_dashboard.php");
        } elseif ($_SESSION['role'] === 'student') {
            header("Location: ../public/student/student_dashboard.php");
        } elseif ($_SESSION['role'] === 'teacher') {
            header("Location: ../public/teacher/teacher_dashboard.php");
        }
        exit();  // Dodaj exit, aby zakończyć skrypt i zapobiec dalszemu wykonaniu
    } else {
        // Błąd logowania, jeśli użytkownik nie istnieje w żadnej tabeli
        $_SESSION['login_error'] = "Nieprawidłowy e-mail lub hasło!";
        header("Location: ../public/index.php"); // Przekierowanie na stronę logowania
        exit();
    }
}
?>
