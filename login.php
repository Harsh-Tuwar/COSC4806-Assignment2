<?php
  $valid_uname = "harsh";
  $valid_pwd = "harsh123";

  session_start();

  if (!isset($_SESSION['failed_attempts'])) {
      $_SESSION['failed_attempts'] = 0; // init with 0
  }

  if (isset($_SESSION['authenticated']) && isset($_SESSION['authenticated']) == true) {
    header('Location: index.php');
    exit();
  }

  $error = '';

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? "";;
    $password = $_POST['password'] ?? "";

    if ($username != "" && $password != "") {
     if ($username == $valid_uname && $password == $valid_pwd) {
       $_SESSION['authenticated'] = true;
       $_SESSION['username'] = $username;
       // echo "Login successful!";
       header('Location: index.php');
       exit();
     } else {
       $_SESSION['failed_attempts'] = $_SESSION['failed_attempts'] + 1;
       $error = 'Invalid username or password.';
     }
    } else {
      $error="Please enter username and password";
    }
  }
?>

<form action="login.php" method="post">
  <?php if ($error): ?>
      <p style="color:red;"><?= $error ?></p>
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
</form>
