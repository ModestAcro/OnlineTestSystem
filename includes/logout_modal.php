
<button class="btn btn-outline-danger" onclick="LogoutModal()">Wyloguj</button>

<!-- Okno modalne do potwierdzenia wylogowania -->
<div id="openLogoutModal" class="modal">
    <div class="modal-content">
        <span class="close-btn" id="closeLogoutModal">&times;</span>
        <h1 class="modal-header">Czy na pewno chcesz się wylogować?</h1>
        <form action="../../config/logout.php" method="POST">
            <button type="submit" class="submit-btn">Tak</button>
        </form>
    </div>
</div>
<!-- Okno modalne do potwierdzenia wylogowania -->
