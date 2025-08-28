<?php
session_start();
require_once 'db.php';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
 
    if ($username === '' || $password === '') {
        $error = "Please fill all fields.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $error = "Username already taken.";
        } else {
            $hash = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password_hash) VALUES (?, ?)");
            $stmt->execute([$username, $hash]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['username'] = $username;
            header('Location: chat.php');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Sign Up</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f5f5f5;
}
.auth-container {
    width: 300px;
    margin: 100px auto;
    background: white;
    padding: 20px;
    border-radius: 8px;
}
.auth-container input, .auth-container button {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
}
.auth-container button {
    background: #10a37f;
    color: white;
    border: none;
    border-radius: 4px;
}
.error {color:red;}
</style>
</head>
<body>
<div class="auth-container">
<h2>Sign Up</h2>
<?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
<form method="POST">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Sign Up</button>
</form>
<p>Already have an account? <a href="login.php">Log in</a></p>
</div>
</body>
</html>
 
