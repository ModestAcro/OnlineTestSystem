<?php
// Sprawdzenie, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_role'])) {
    header("Location: login.php");
    exit();
}
?>

<header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold">
                <?php echo $_SESSION['user_name'] . ' ' . $_SESSION['user_surname']; ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon custom-toggler"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- STUDENT -->
                    <?php if ($_SESSION['user_role'] == 'student') : ?>
                        <?php if (basename($_SERVER['PHP_SELF']) == 'student_dashboard.php') : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/student/completed_tests.php">Rozwiązane testy</a>
                            </li>
                        <?php elseif (basename($_SERVER['PHP_SELF']) == "test_details.php") : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/student/completed_tests.php">Rozwiązane testy</a>
                            </li>
                        <?php else : ?>
                            <li class="nav-item">
                                <a class="nav-link active" href="../../public/student/student_dashboard.php">Panel główny</a>
                            </li>
                        <?php endif; ?>
                    <!-- TEACHER -->
                    <?php elseif ($_SESSION['user_role'] == 'teacher') : ?>
                        <?php if (basename($_SERVER['PHP_SELF']) == 'teacher_dashboard.php') : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/teacher/tests.php">Testy</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/teacher/questions.php">Pytania</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/teacher/student_groups.php">Grupy studentów</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/teacher/completed_tests.php">Wyniki testów</a>
                            </li>
                        <?php elseif (basename($_SERVER['PHP_SELF']) == "edit_test.php") : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/teacher/tests.php">Testy</a>
                            </li>
                        <?php elseif (basename($_SERVER['PHP_SELF']) == "add_multichoice.php") : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/teacher/questions.php">Pytania</a>
                            </li>
                        <?php elseif (basename($_SERVER['PHP_SELF']) == "edit_question.php") : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/teacher/questions.php">Pytania</a>
                            </li>
                        <?php elseif (basename($_SERVER['PHP_SELF']) == "add_test.php") : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/teacher/tests.php">Testy</a>
                            </li>
                        <?php elseif (basename($_SERVER['PHP_SELF']) == "edit_group.php") : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/teacher/student_groups.php">Grupy studentów</a>
                            </li>
                        <?php elseif (basename($_SERVER['PHP_SELF']) == "test_details.php") : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/teacher/completed_tests.php">Wyniki testów</a>
                            </li>
                        <?php else : ?>
                            <li class="nav-item">
                                <a class="nav-link active" href="../../public/teacher/teacher_dashboard.php">Panel główny</a>
                            </li>
                        <?php endif; ?>
                    <!-- ADMIN -->
                    <?php elseif ($_SESSION['user_role'] == 'administrator') : ?>
                        <?php if (basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php') : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/admin/teachers.php">Wykładowcy</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/admin/students.php">Studeńci</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/admin/subjects.php">Przedmioty</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/admin/courses.php">Kierunki</a>
                            </li>
                        <?php elseif (basename($_SERVER['PHP_SELF']) == "edit_teacher.php") : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/admin/teachers.php">Wykładowcy</a>
                            </li>
                        <?php elseif (basename($_SERVER['PHP_SELF']) == "edit_student.php") : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/admin/students.php">Studeńci</a>
                            </li>
                        <?php elseif (basename($_SERVER['PHP_SELF']) == "edit_subject.php") : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/admin/subjects.php">Przedmioty</a>
                            </li>
                        <?php elseif (basename($_SERVER['PHP_SELF']) == "edit_course.php") : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../../public/admin/courses.php">Kierunki</a>
                            </li>
                        <?php else : ?>
                            <li class="nav-item">
                                <a class="nav-link active" href="../../public/admin/admin_dashboard.php">Panel główny</a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
                <form class="d-flex">
                    <a class="btn btn-outline-light" href="../../config/logout.php">Wyloguj</a>
                </form>
            </div>
        </div>
    </nav>
</header>
