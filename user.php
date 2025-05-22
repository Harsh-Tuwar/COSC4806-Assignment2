<?php

require_once 'database.php';

Class User {
  public function get_all_users() {
    $conn = db_connect();

    $query = 'SELECT * FROM users';
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $users;
  }
}
?>
