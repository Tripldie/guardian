<?php
session_start();
$db = new SQLite3('hazards.sqlite');

if (!isset($_SESSION['user_id'])) {
    echo "Not logged in";
    exit;
}

$user_id = $_SESSION['user_id'];
$phone = $_POST['phone'];

$stmt = $db->prepare("INSERT INTO emergency_contacts (user_id, phone) VALUES (:user_id, :phone)");
$stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
$stmt->bindValue(':phone', $phone, SQLITE3_TEXT);

if ($stmt->execute()) {
    echo "Contact saved successfully!";
} else {
    echo "Error saving contact.";
}
?>
