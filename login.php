<?php
session_start();
$db = new SQLite3('hazards.sqlite');

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$contactName = $_POST['contactName'] ?? '';
$contactPhone = $_POST['contactPhone'] ?? '';

// Simple login check (replace with proper hashed passwords)
$stmt = $db->prepare("SELECT * FROM users WHERE username=:username AND password=:password");
$stmt->bindValue(':username', $username);
$stmt->bindValue(':password', $password);
$user = $stmt->execute()->fetchArray(SQLITE3_ASSOC);

if ($user) {
    $_SESSION['user_id'] = $user['id'];

    // Insert emergency contact
    if($contactName && $contactPhone){
        $stmt2 = $db->prepare("INSERT INTO contacts (user_id,name,phone) VALUES (:user_id,:name,:phone)");
        $stmt2->bindValue(':user_id', $user['id']);
        $stmt2->bindValue(':name', $contactName);
        $stmt2->bindValue(':phone', $contactPhone);
        $stmt2->execute();
    }

    echo "success";
} else {
    echo "Invalid username or password";
}
?>
