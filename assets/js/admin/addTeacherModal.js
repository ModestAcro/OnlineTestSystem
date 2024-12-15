document.addEventListener('DOMContentLoaded', function () {
    const modal = document.querySelector('.modal');
    const closeBtn = document.querySelector('.close-btn');

    // Funkcja otwierająca modal
    window.addTeacher = function () {
        modal.style.display = 'block';
    };

    // Funkcja zamykająca modal
    closeBtn.addEventListener('click', function () {
        modal.style.display = 'none';
    });
});


/*=======================================================
<!-- Skrypt dla edytowania i usunięcia wykładowców -->
========================================================*/
