<?php
require_once("./connexion/config.php");

// Example registration process for securely hashing the password
$name = 'mohamed Amid';
$email = 'mohamed@gmail.com';
$password = 'mohamed123';
$type = 'cleint';

// Hash the password before saving to the database
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Insert into database (make sure to adjust the table and fields as needed)
$sql = "INSERT INTO users (name, email, password, type) VALUES (:name, :email, :password, :type)";
$stmt = $conn->prepare($sql);

// Bind the parameters
$stmt->bindParam(':name', $name);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':password', $hashedPassword);  // Use hashed password
$stmt->bindParam(':type', $type);

// Execute the query
$stmt->execute();
?>