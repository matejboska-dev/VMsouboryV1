<?php
session_start();
require 'config.php';
require 'phpqrcode/qrlib.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $expiry = date('Y-m-d H:i:s', strtotime('+5 minutes'));
    $qr_link = 'http://your_vm_ip/verify.php?username=' . urlencode($username);

    // Uložení QR kódu do souboru
    $qr_file = 'qrcodes/' . $username . '.png';
    QRcode::png($qr_link, $qr_file);

    // Uložit uživatele do databáze
    $stmt = $conn->prepare("INSERT INTO users (username, password, qr_code, qr_expiry) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $qr_file, $expiry);
    if ($stmt->execute()) {
        echo "Zaregistrováno! Naskenujte QR kód do 5 minut.<br>";
        echo "<img src='$qr_file' alt='QR kód'>";
    } else {
        echo "Chyba při registraci: " . $stmt->error;
    }
}
?>
<form method="POST" action="register.php">
    Uživatelské jméno: <input type="text" name="username" required><br>
    Heslo: <input type="password" name="password" required><br>
    <input type="submit" value="Zaregistrovat">
</form>
