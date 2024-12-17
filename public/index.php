<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/main.css">
    <title>Login</title>
</head>
<body id="login-body">
    <div class="login-box">
        <h1 class="login-header">Login</h1>
        <form action="../includes/login.php" method="POST">
            <label>Email</label>
            <input class="input-box" type="text" name="email" required>
            
            <label>Password</label>
            <input class="input-box" type="password" name="password" required>
            
            <button type="submit" name="login" class="login-submit-btn">Login</button>
        </form>
        <?php
            if (isset($_SESSION['login_error'])) {
                echo '<p style="color: red;" class="error-message">' . $_SESSION['login_error'] . '</p>';
                unset($_SESSION['login_error']); // Usunięcie zmiennej po wyświetleniu
            }
        ?>
    </div>
</body>
</html>