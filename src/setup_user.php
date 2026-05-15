<?php
// setup_user.php - Pas deze gegevens aan naar jouw database instellingen
require 'config.inc.php';

// De testgebruiker gegevens
$username = 'testgebruiker';
$password = 'Welkom01!'; // Gebruik een sterk wachtwoord
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
try {
$stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->execute([$username, $hashed_password]);
echo "Succes! Gebruiker '$username' is aangemaakt met wachtwoord '$password'.";
} catch (\PDOException $e) {
if ($e->getCode() == 23000) {
echo "Fout: Gebruikersnaam bestaat al.";
} else {
echo "Er is een fout opgetreden: " . $e->getMessage();
}
}
?>