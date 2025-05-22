<?php
    require_once 'database.php'; // Ensure this returns a PDO instance

    $error = '';
    $success = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($username === '' || $password === '') {
            $error = "Username and password are required.";
        } elseif (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) 
              || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password)) {
            $error = "Password must be at least 8 characters long and include uppercase, lowercase, and a number.";
        } else {
        
            try {
            
                $conn = db_connect();

                // Check if username exists
                $stmt = $conn->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
                $stmt->bindParam(':username', $username);
                $stmt->execute();
                $exists = $stmt->fetchColumn();
    
                if ($exists) {
                    $error = "Username already exists. Please choose another.";
                } else {
                    // Hash password and insert new user
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
                    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $hashedPassword);
                    $stmt->execute();
    
                    $success = "Account created successfully. You can now <a href='login.php'>log in</a>.";
                }
            } catch (PDOException $e) {
                $error = "Database error: " . $e->getMessage();
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
        <small>Password must be at least 8 characters with upper/lowercase and a number.</small>
        <br>        
        <br>
        <button type="submit">Create Account</button>
    </form>
</body>
</html>
