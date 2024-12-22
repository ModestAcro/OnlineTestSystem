<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja Administratora</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body>
    <div class="register-box">
        <h1>Rejestracja Administratora</h1>
        <form action="../includes/admin_register.php" method="POST">
            <label for="imie">Imię</label>
            <input type="text" name="imie" id="imie" required>

            <label for="nazwisko">Nazwisko</label>
            <input type="text" name="nazwisko" id="nazwisko" required>

            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>

            <label for="haslo">Hasło</label>
            <input type="password" name="haslo" id="haslo" required>

            <button type="submit" name="register" class="register-btn">Zarejestruj</button>
        </form>

        <?php
            if (isset($_GET['error'])) {
                echo '<p style="color: red;">' . htmlspecialchars($_GET['error']) . '</p>';
            }
        ?>
    </div>
</body>
</html>
