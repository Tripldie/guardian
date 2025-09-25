<?php
$db = new SQLite3('hazards.sqlite');

if(!isset($_GET['id'])){
    echo "Error: no hazard ID provided.";
    exit;
}

$id = intval($_GET['id']);
$stmt = $db->prepare("UPDATE hazards SET votes = votes + 1 WHERE id = :id");
$stmt->bindValue(':id', $id, SQLITE3_INTEGER);

if($stmt->execute()){
    echo "Hazard verified!";
}else{
    echo "Error: could not verify hazard.";
}
?>
