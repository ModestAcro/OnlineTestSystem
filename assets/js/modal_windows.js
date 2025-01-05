document.addEventListener('DOMContentLoaded', function () {
    const modalWindow = document.getElementById('openModal');
    const closeBtn = document.getElementById('closeModal');


    // Funkcja otwierająca modal
    window.addEntity = function () {
        modalWindow.style.display = 'block';
    };

    // Funkcja zamykająca modal
    closeBtn.addEventListener('click', function () {
        modalWindow.style.display = 'none';
    });
});


document.addEventListener('DOMContentLoaded', function() {
    const logoutModalWindow = document.getElementById('openLogoutModal');
    const closeLogoutModal = document.getElementById('closeLogoutModal');

    // Funkcja otwierająca modal wylogowania
    window.LogoutModal = function () {
        logoutModalWindow.style.display = 'block';
    };

    closeLogoutModal.addEventListener('click', function() {
        logoutModalWindow.style.display = 'none';
    });
});


document.addEventListener("DOMContentLoaded", function() {
    const deleteBtn = document.getElementById("delete-btn");
    const modal = document.getElementById("deleteCharacterModal");
    const closeModal = document.getElementById("deleteCharacterModalClose");

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



