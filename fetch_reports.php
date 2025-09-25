<?php
$db = new SQLite3('hazards.sqlite');

$results = $db->query("SELECT id, lat, lng, type, photo, votes FROM hazards ORDER BY created_at DESC");

$data = [];
while($row = $results->fetchArray(SQLITE3_ASSOC)){
    // Consider verified if votes >= 1 for testing
    $row['verified'] = ($row['votes'] >= 1) ? 1 : 0; 
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
