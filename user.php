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

    // Check if username already exists
    $checkQuery = 'SELECT COUNT(*) FROM users WHERE username = :username';
    $checkStmt = $conn->prepare($checkQuery);
    $checkStmt->bindParam(':username', $username);
    $checkStmt->execute();

    if ($checkStmt->fetchColumn() > 0) {
        return ['success' => false, 'message' => 'Username already taken.'];
    }

    // Validate password strength
    if (!$this->is_valid_password($password)) {
        return ['success' => false, 'message' => 'Password does not meet security requirements.'];
    }

    // Hash the password securely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $query = 'INSERT INTO users (username, password) VALUES (:username, :password)';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashedPassword);

    if ($stmt->execute()) {
        return ['success' => true, 'message' => 'Account created successfully.'];
    } else {
        return ['success' => false, 'message' => 'Error creating account.'];
    }
  }

  public function login($username, $password) {
    $conn = db_connect();

    // Fetch the user's hashed password
    $query = 'SELECT * FROM users WHERE username = :username';
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Login successful
        return ['success' => true, 'message' => 'Login successful.', 'user' => $user];
    } else {
        // Login failed
        return ['success' => false, 'message' => 'Invalid username or password.'];
    }
  }

// Helper function for password validation
  private function is_valid_password($password) {
    $minLength = 8;
    return (
        strlen($password) >= $minLength &&
        preg_match('/[A-Z]/', $password) &&    // At least one uppercase
        preg_match('/[a-z]/', $password) &&    // At least one lowercase
        preg_match('/[0-9]/', $password) &&    // At least one digit
        preg_match('/[\W]/', $password)        // At least one special char
    );
  }

}
?>
