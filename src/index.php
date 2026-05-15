<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boodschappen PWA</title>
    <link rel="manifest" href="manifest.json">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; background: #f4f4f9; }
        .login-card { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
        #message { color: red; font-size: 0.9rem; text-align: center; }
    </style>
</head>
<body>

<div class="login-card" id="login-section">
    <h2>Inloggen</h2>
    <div id="message"></div>
    <form id="loginForm">
        <input type="text" id="username" placeholder="Gebruikersnaam" required>
        <input type="password" id="password" placeholder="Wachtwoord" required>
        <label><input type="checkbox" id="remember"> Blijf ingelogd</label>
        <button type="submit">Log in</button>
    </form>
</div>

<div id="app-section" style="display:none;">
    <h2>Welkom bij je Voorraad</h2>
    <p>Je bent succesvol ingelogd.</p>
    <button id="logoutBtn">Uitloggen</button>
</div>

<script>
$(document).ready(function() {
    // Check of de gebruiker al een geldige sessie heeft bij het laden
    checkAuth();

    $('#loginForm').on('submit', function(e) {
        e.preventDefault();
        const data = {
            username: $('#username').val(),
            password: $('#password').val(),
            remember: $('#remember').is(':checked')
        };

        $.post('auth.php', { action: 'login', ...data }, function(response) {
            if(response.success) {
                showApp();
            } else {
                $('#message').text(response.error);
            }
        }, 'json');
    });

    $('#logoutBtn').on('click', function() {
        $.post('auth.php', { action: 'logout' }, function() {
            location.reload();
        });
    });
});

function checkAuth() {
    $.get('auth.php', { action: 'check' }, function(response) {
        if(response.authenticated) {
            showApp();
        }
    }, 'json');
}

function showApp() {
    $('#login-section').hide();
    $('#app-section').show();
}
</script>
<script>
if ('serviceWorker' in navigator) {
navigator.serviceWorker.register('service-worker.js');
}
</script>
</body>
</html>