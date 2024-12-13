<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/main.css">
    <title>Login form</title>
</head>
<body>
    <h1 class="login-heading">Login</h1>
    <form action="../includes/login.php" method="POST" class="login-form">
        <label for="email" class="form-label">Email</label>
        <input type="text" name="email" id="email" class="form-input" required>
        <br>
        <label for="password" class="form-label">Password</label>
        <input type="password" name="password" id="password" class="form-input" required>
        <br>
        <button type="submit" name="login" class="login-btn">Login</button>
    </form>

    <?php
        if (isset($_SESSION['login_error'])) {
            echo '<p style="color: red;">' . $_SESSION['login_error'] . '</p>';
            unset($_SESSION['login_error']); // Usunięcie zmiennej po wyświetleniu
        }
    ?>

</body>
</html>