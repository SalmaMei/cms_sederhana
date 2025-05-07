<?php
require_once 'config/database.php';

// Data admin baru
$username = 'admin';
$password = 'admin123';
$email = 'admin@example.com';

// Hash password menggunakan password_hash
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

try {
    // Cek apakah username sudah ada
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->execute([$username]);
    
    if ($stmt->rowCount() > 0) {
        // Update password jika user sudah ada
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt->execute([$hashed_password, $username]);
        echo "Password admin berhasil diupdate!<br>";
    } else {
        // Buat user baru jika belum ada
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute([$username, $hashed_password, $email]);
        echo "Admin berhasil dibuat!<br>";
    }
    
    echo "Username: admin<br>";
    echo "Password: admin123<br>";
    echo "<a href='index.php'>Klik disini untuk login</a>";
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 