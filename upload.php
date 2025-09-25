<?php
try {
    $db = new SQLite3('hazards.sqlite');

    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $type = $_POST['type'];

    // Handle photo upload
    $photoPath = null;
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK){
        $uploadDir = 'uploads/';
        if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

        $filename = time() . '_' . basename($_FILES['photo']['name']);
        $photoPath = $uploadDir . $filename;
        move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
    }

    // Begin transaction
    $db->exec('BEGIN');

    $stmt = $db->prepare("INSERT INTO hazards (lat,lng,type,photo,votes,verified) VALUES (:lat,:lng,:type,:photo,0,0)");
    $stmt->bindValue(':lat', $lat);
    $stmt->bindValue(':lng', $lng);
    $stmt->bindValue(':type', $type);
    $stmt->bindValue(':photo', $photoPath);

    if(!$stmt->execute()){
        $db->exec('ROLLBACK');
        echo "Error: could not report hazard.";
    } else {
        $db->exec('COMMIT');
        echo "Hazard reported successfully!";
    }

    $db->close(); // Always close connection
} catch(Exception $e){
    echo "Error: " . $e->getMessage();
}
?>
