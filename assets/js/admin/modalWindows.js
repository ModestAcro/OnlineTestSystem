document.addEventListener('DOMContentLoaded', function () {
    const modalWindow = document.getElementById('addModal');
    const closeBtn = document.getElementById('addModalClose');

    // Funkcja otwierająca modal
    window.addCharacter = function () {
        modalWindow.style.display = 'block';
    };

    // Funkcja zamykająca modal
    closeBtn.addEventListener('click', function () {
        modalWindow.style.display = 'none';
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



