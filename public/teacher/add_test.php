<?php

    session_start();
    require_once('../../config/connect.php');
    require_once('../../config/functions.php');

    $userId = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/main.css">
    <title>Dodaj test</title>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="left-header">
                <a class="nav-btn" href="teacher_dashboard.php">Strona główna</a>
            </div>
            <div class="right-header">
                <span class="name"><?php echo $_SESSION['user_name'] . ' ' . $_SESSION['user_surname']; ?></span>

                <!-- Formularz wylogowania -->
                <?php
                    include('../../includes/logout_modal.php');
                ?>
                <!-- Formularz wylogowania -->

            </div>
        </div>
    </header>

    <main class="main">
        <div class="container">
            <div class="title">
                <h1>Ustawienia ogólne</h1>
            </div>

            <!-- Nazwa -->
            <h4 class="h4-margin">Nazwa</h4>

            <div class="radio">
                <div class="radio-section">
                    <input type="text"  placeholder="Wpisz nazwę testu">
                </div>
            </div>

            <!-- Dostępność -->
            <h4 class="h4-margin">Dostępność</h4>

            <div class="radio">
                <div class="radio-section">

                    <div class="radio-input">
                        <input type="radio" name="limit">
                        <label>Limit czasowy</label>
                    </div>

                     <!-- Wybór daty i godziny dla limitu czasowego -->
                    <div class="time-limited-options">

                        <div class="time-limit-input">
                            <label>Od:</label>
                            <input type="datetime-local" id="start-time" name="start-time">
                        </div>

                        <div class="time-limit-input">
                            <label>Do:</label>
                            <input type="datetime-local" id="end-time" name="end-time">
                        </div>

                    </div>
                    <!-- Wybór daty i godziny dla limitu czasowego -->
                </div>

                <div class="radio-section">
                    <div class="radio-input">
                        <input type="radio" name="limit">
                        <label>Bez limitu</label>
                    </div>
                </div>
            </div>

            <!-- Czas trawania -->
            <h4 class="h4-margin">Czas trwania testu</h4>

            <div class="radio">
                <div class="radio-section">
                <input type="number" placeholder="W minutach">
                </div>
            </div>

             <!-- Ilość pobr -->
             <h4 class="h4-margin">Ilość prób</h4>

            <div class="radio">
                <div class="radio-section">
                    <div class="radio-input">
                        <input type="radio" name="attempts">
                        <label>Nieograniczona liczba</label>
                    </div>
                </div>

                <div class="radio-section">
                    <div class="radio-input">
                        <input type="radio" name="attempts">
                        <label>Jedno podejście</label>
                    </div>
                </div>

                <div class="radio-section">
                    <div class="radio-input">
                        <input type="radio" name="attempts">
                        <label>Wiele podejść</label>
                    </div>

                    <div class="time-limited-options">
                        <div class="time-limit-input">
                            <label>Liczba</label>
                            <input type="number">
                        </div>
                    </div>

                </div>
            </div>

            <div class="title">
                <h1 style="margin: 20px 0px 20px 0px">Pytania</h1>
            </div>

            <div class="radio">
                <div class="radio-section">
                    <div class="radio-input">
                        <input type="checkbox">
                        <label>Losuj opcje pytań</label>
                    </div>
                </div>

                <div class="radio-section">
                    <div class="radio-input">
                        <input type="checkbox">
                        <label>Losuj pytania</label>
                    </div>

                    <div class="time-limited-options">
                        <div class="time-limit-input">
                            <label>Liczba</label>
                            <input type="number">
                        </div>
                    </div>

                </div>
            </div>

             <!-- Przycisk "Dodaj Grupę" -->
             <button style="margin-top: 30px;"class="add-btn" onclick="addEntity()">
                    <img src="../../assets/images/icons/plus.svg" alt="Plus icon" class="add-icon">
                </button>
            <!-- Przycisk "Dodaj Grupę" -->

            <!-- Question card -->

            <div class="radio" style="margin-top: 30px;">
                <div class="question-card">
                    <div class="question-left">

                        <div class="question-top">
                            <div class="number">
                                <label>1/3</label>
                                <label>Multiple choice</label>
                            </div>
                            <div class="points">
                                <label>10</label>
                            </div>
                        </div>

                        <div class="question-top">
                            <div class="question">
                                <label>2 + 2 = ?</label>
                            </div>
                            <div class="answer">
                                <label>Odpowiedź: 4</label>
                            </div>
                        </div>

                    </div>

                    <div class="question-right">
                        <div class="question-action" id="edit-question-btn">
                            <button>Edytuj</button>
                        </div>
                        <div class="question-action"  id="delete-question-btn">
                            <button>Usuń</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Question card -->

        </div>
    </main>    


    <script src="../../assets/js/modal_windows.js"></script>  

</body>
</html>
