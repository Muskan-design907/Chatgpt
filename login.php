<?php
session_start();
require_once 'db.php';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
 
    $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
 
    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        header('Location: chat.php');
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Login</title>
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
<h2>Login</h2>
<?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
<form method="POST">
    <input type="text" name="username" placeholder="Username">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Log In</button>
</form>
<p>Don't have an account? <a href="signup.php">Sign up</a></p>
</div>
</body>
</html>
 
