<?php
session_start();
require_once('../config/connect.php');

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Proste zapytanie SQL z aliasem 'role'
    $query = "SELECT ID, email, haslo, imie, nazwisko, 'administrator' AS role FROM tAdministratorzy WHERE email = '$email'
              UNION
              SELECT ID, email, haslo, imie, nazwisko, 'student' AS role FROM tStudenci WHERE email = '$email'
              UNION
              SELECT ID, email, haslo, imie, nazwisko, 'teacher' AS role FROM tWykladowcy WHERE email = '$email'";

    // Wykonanie zapytania
    $result = mysqli_query($conn, $query);
    
    // Sprawdzenie, czy wynik zapytania zwrócił jakikolwiek rekord
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Sprawdzenie, czy wprowadzone hasło pasuje do zahaszowanego hasła w bazie danych
        if (password_verify($password, $user['haslo'])) {
            // Jeśli hasło jest poprawne, zapisujemy dane do sesji
            $_SESSION['user_id'] = $user['ID']; 
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['imie'];
            $_SESSION['user_surname'] = $user['nazwisko'];
            $_SESSION['user_role'] = $user['role']; // Rola użytkownika 

            // Przekierowanie do odpowiedniej strony
            if ($_SESSION['user_role'] === 'administrator') {
                header("Location: ../public/admin/admin_dashboard.php");
            } elseif ($_SESSION['user_role'] === 'student') {
                header("Location: ../public/student/student_dashboard.php");
            } elseif ($_SESSION['user_role'] === 'teacher') {
                header("Location: ../public/teacher/teacher_dashboard.php");
            }
            exit();
        } else {
            // Błąd logowania, jeśli hasło lub email jest nieprawidłowe
            $_SESSION['login_error'] = "Nieprawidłowy e-mail lub hasło!";
            header("Location: ../public/index.php"); // Przekierowanie na stronę logowania
            exit();
        }
    } else {
        // Błąd logowania, jeśli użytkownik nie istnieje w żadnej tabeli
        $_SESSION['login_error'] = "Taki użytkownik nie istnieje!";
        header("Location: ../public/index.php"); // Przekierowanie na stronę logowania
        exit();
    }
}
?>
