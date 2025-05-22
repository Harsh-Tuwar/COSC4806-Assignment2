<?php
  require_once 'user.php';

  $user = new User();

  $users = $user->get_all_users();

  print_r($users)

?>
