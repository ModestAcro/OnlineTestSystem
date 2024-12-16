/*=========================================
<!-- Okno modalne dla dodania Wykładowcy-->
==========================================*/

document.addEventListener('DOMContentLoaded', function () {
    const addTeacherModal = document.getElementById('addTeacherModal');
    const addTeacherModalCloseBtn = document.getElementById('closeAddModalBtn');

    // Funkcja otwierająca modal
    window.addTeacher = function () {
        addTeacherModal.style.display = 'block';
    };

    // Funkcja zamykająca modal
    addTeacherModalCloseBtn.addEventListener('click', function () {
        addTeacherModal.style.display = 'none';
    });

});

/*==========================================================
<!-- Okno modalne dla zatwierdzenia usunięcia nauczyciela-->
===========================================================*/

document.addEventListener("DOMContentLoaded", function() {
    const deleteBtn = document.getElementById("delete-btn");
    const modal = document.getElementById("deleteTeacherModal");
    const closeModal = document.getElementById("closeModal");

    // Otwórz modal po kliknięciu na przycisk "Usuń"
    deleteBtn.addEventListener("click", function(event) {
        event.preventDefault(); // Zatrzymujemy domyślne działanie formularza
        modal.style.display = "block"; // Wyświetlamy modal
    });

    // Zamknij modal po kliknięciu na "X"
    closeModal.addEventListener("click", function() {
        modal.style.display = "none";
    });

});

