document.addEventListener('DOMContentLoaded', function () {
    const modal = document.querySelector('.modal');
    const openBtn = document.querySelector('.btn-with-icon');
    const closeBtn = document.querySelector('.close-btn');

    openBtn.addEventListener('click', function () {
        modal.style.display = 'block';
    });

    closeBtn.addEventListener('click', function () {
        modal.style.display = 'none';
    });
});
