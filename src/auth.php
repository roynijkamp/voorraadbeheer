<?php
session_start();
require 'config.inc.php';
header('Content-Type: application/json');

// Database verbinding (pas dit aan naar jouw instellingen)

$action = $_POST['action'] ?? $_GET['action'] ?? '';

if ($action == 'login') {
    $user = $_POST['username'];
    $pass = $_POST['password'];
    $remember = $_POST['remember'] === 'true';

    // In een echte app gebruik je password_verify()
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (password_verify($pass, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];

            if ($remember) {
                $token = bin2hex(random_bytes(16));
                setcookie('auth_token', $token, time() + (86400 * 30), "/"); // 30 dagen
                $pdo->query("UPDATE users SET session_token = '$token' WHERE id = " . $row['id']);
            }
            echo json_encode(['success' => true]);
            exit;
        }
    }
    echo json_encode(['success' => false, 'error' => 'Ongeldige inloggegevens']);
}

if ($action == 'check') {
    if (isset($_SESSION['user_id'])) {
        echo json_encode(['authenticated' => true]);
    } elseif (isset($_COOKIE['auth_token'])) {
        $token = $_COOKIE['auth_token'];
        $stmt = $pdo->prepare("SELECT id FROM users WHERE session_token = ?");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        if ($row = $stmt->get_result()->fetch_assoc()) {
            $_SESSION['user_id'] = $row['id'];
            echo json_encode(['authenticated' => true]);
        } else {
            echo json_encode(['authenticated' => false]);
        }
    } else {
        echo json_encode(['authenticated' => false]);
    }
}

if ($action == 'logout') {
    session_destroy();
    setcookie('auth_token', '', time() - 3600, "/");
    echo json_encode(['success' => true]);
}
?>