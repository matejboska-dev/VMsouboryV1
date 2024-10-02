<?php
session_start();
require 'config.php';

if (isset($_GET['username'])) {
    $username = $_GET['username'];
    $stmt = $conn->prepare("SELECT qr_expiry FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($expiry);
    $stmt->fetch();

    if ($expiry > date('Y-m-d H:i:s')) {
        $_SESSION['username'] = $username;
        echo "<form method='POST' action='login.php'>
            Heslo: <input type='password' name='password' required><br>
            <input type='submit' value='Přihlásit'>
        </form>";
    } else {
        echo "QR kód vypršel.";
    }
}
?>
