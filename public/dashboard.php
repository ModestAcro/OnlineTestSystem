<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login form</title>
</head>
<body>
    <h1>Login</h1>
    <form action="../includes/login.php" method="POST">
        <label>Email</label>
        <input type="text" name="email" required>
        <br>
        <label>Password</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit" name="login">Login</button>
    </form>


    <?php
        if (isset($_SESSION['login_error'])) {
            echo '<p style="color: red;">' . $_SESSION['login_error'] . '</p>';
            unset($_SESSION['login_error']); // Usunięcie zmiennej po wyświetleniu
        }
    ?>

</body>
</html>