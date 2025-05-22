<?php
    require_once 'user.php';
    
    $error = '';
    $success = '';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if ($username === '' || $password === '') {
            $error = "Username and password are required.";
        } else {
            $user = new User();
            $result = $user->create_user($username, $password);
    
            if ($result['success']) {
                $success = $result['message'] . ' <a href="login.php">Login here</a>.';
            } else {
                $error = $result['message'];
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Account</title>
</head>
<body>
    <h2>Create New User</h2>

    <?php if ($error): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green;"><?= $success ?></p>
    <?php endif; ?>

    <form method="post" action="create_user.php">
        <label for="username">Username:</label><br>
        <input type="text" name="username" required><br><br>

        <label for="password">Password:</label><br>
        <input type="password" name="password" required><br>
        <small>
            Password must be at least 8 characters and include uppercase, lowercase, number, and                 special character.
        </small><br><br>

        <button type="submit">Create Account</button>
    </form>
</body>
</html>
