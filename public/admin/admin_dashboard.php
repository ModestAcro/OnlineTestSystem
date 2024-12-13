<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Admin dashboard</title>

    <style>

header {
    display: flex;
    justify-content: space-between;
    height: 70px;
    padding: 5px 50px;
    background-color: #333;
    color: white;
}

.nav-btn {
    background-color: #757575;
    color: white;
    width: 150px;
    padding: 20px 20px;
    cursor: pointer;
    font-size: 14px;
    border-radius: 5px;
    text-align: center;
}

.nav-btn:hover {
    opacity: 0.8;
}

.name {
    font-size: 18px;
    font-weight: bold;
}

.right-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.left-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 15px;
}

.logout-btn {
    background-color: #f44336;
    color: white;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    font-size: 14px;
    border-radius: 5px;
}

.logout-btn:hover {
    opacity: 0.8;
}

    </style>

</head>
<body>
<header>
    <div class="left-header">
        <a class="nav-btn" href="teachers.php">Wykładowcy</a>
        <a class="nav-btn" href="students.php">Studenci</a>
    </div>
    <div class="right-header">
        <span class="name">Imię Nazwisko</span>

         <!-- Formularz wylogowania -->
        <form action="../../config/logout.php" method="POST">
            <button type="submit" class="logout-btn">Wyloguj</button>
        </form>
    </div>
</header>
</body>
</html>