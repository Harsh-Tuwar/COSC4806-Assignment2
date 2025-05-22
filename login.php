<?php
  session_start();
  require_once 'database.php';
  require_once 'user.php';

  if (!isset($_SESSION['failed_attempts'])) {
      $_SESSION['failed_attempts'] = 0;
  }

  if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] === true) {
      header('Location: index.php');
      exit();
  }

  $error = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $username = trim($_POST['username'] ?? '');
      $password = $_POST['password'] ?? '';

      if ($username !== '' && $password !== '') {
          $userObj = new User();
          $result = $userObj->login($username, $password);

          if ($result['success']) {
              $_SESSION['authenticated'] = true;
              $_SESSION['username'] = $result['user']['username'];
              header('Location: index.php');
              exit();
          } else {
              $_SESSION['failed_attempts'] += 1;
              $error = $result['message'];
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
