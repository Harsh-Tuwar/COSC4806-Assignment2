<?php
  session_start();

  require_once 'user.php';
  
  $user = new User();
  
  $users = $user->get_all_users();
  
  // echo '<pre>';
  // print_r($users)

  // Redirect to login if not authenticated
  if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
      header("Location: login.php");
      // echo $_SESSION['authenticated'] ?? "false";
      // ?> <br /> <?php 
      // // echo $_SESSION['username'] ?? " n/a";
      // ?> <br /> <?php 
      // echo "\nYou are not logged in. Please <a href='login.php'>login</a> first.";
      exit();
  }

  $username = $_SESSION['username'];
  $date = date("l, F j, Y");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome, <?= htmlspecialchars($username) ?>!</h1>
    <p>Today's date is <?= $date ?>.</p>
    <pre>
        <?php
            print_r($users);
        ?>
    </pre>
    <a href="logout.php">Logout</a>
</body>
</html>