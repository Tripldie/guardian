<?php
session_start();
$db = new SQLite3('hazards.sqlite');

if(isset($_POST['register'])){
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (:username, :password)');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $stmt->bindValue(':password', $password, SQLITE3_TEXT);

    if($stmt->execute()){
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'user';
        header('Location: index.php');
        exit;
    } else {
        $error = "Username already exists.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>SafeCity AI - Register</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<div class="login-container">
    <h2>Register</h2>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button type="submit" name="register">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>
