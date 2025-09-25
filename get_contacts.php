<?php
session_start();
$db = new SQLite3('hazards.sqlite');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

$user_id = $_SESSION['user_id'];
$result = $db->query("SELECT phone FROM emergency_contacts WHERE user_id = $user_id");

$contacts = [];
while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
    $contacts[] = $row['phone'];
}

echo json_encode($contacts);
?>
