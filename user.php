<?php

require_once 'database.php';

Class User {
  public function get_all_users() {
    $conn = db_connect();

    $query = 'SELECT * FROM users;';
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $users;
  }

  public function create_user($username, $password) {
    $conn = db_connect();
    $query = 'INSERT INTO users (username, password) VALUES (:username, :password);';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
  
    return $stmt->rowCount();  
  }
}
?>
