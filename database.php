<?php

require_once 'config.php';

function db_connect() {
  try {
    $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_DATABASE . ';port=' . DB_PORT, DB_USER, DB_PASS);
    // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $conn;
  } catch (PODOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
  }
}

?>