<?php
  session_start();
  require_once 'database.php';

  if (!isset($_SESSION['failed_attempts'])) {
      $_SESSION['failed_attempts'] = 0;
  }

  if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
      header('Location: index.php');
     exit();
  }

  $error = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username !== '' && $password !== '') {
        try {
            $conn = db_connect();
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['authenticated'] = true;
                $_SESSION['username'] = $user['username'];
                header('Location: index.php');
                exit();
            } else {
                $_SESSION['failed_attempts'] += 1;
                $error = 'Invalid username or password.';
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    } else {
        $error = "Please enter username and password.";
    }
  }
?>

<!-- HTML Form remains the same -->
<form action="login.php" method="post">
  <?php if ($error): ?>
      <p style="color:red;"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>
  <?php 
    echo "Failed Attempts: ";
    echo $_SESSION['failed_attempts'] ?? "0";
    ?> <br /> <?php
  ?>
  <br />
  <div class="container">
    <label for="uname"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="username" required>

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="password" required>

    <button type="submit">Login</button>
  </div>
  <br />
  <a href="create_user.php">Create Account</a>
</form>
