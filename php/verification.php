<?php

require_once("../connexion/config.php");

session_start();

if (isset($_POST["login"])) {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: ../login.php?error=invalid_email");
        exit();
    }

    $password = $_POST["password"];

    $sql = "SELECT email, password, type FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_email'] = $user['email'];

            if ($user['type'] == 'admin') {
                header("Location: ../admin.php");
            } elseif ($user['type'] == 'cleint') {
                header("Location: ../cleint.php");
            } else {
                header("Location: ../login.php?error=unknown_role");
            }
            exit();
        } else {
            header("Location: ../login.php?error=incorrect_password");
            exit();
        }
    } else {
        header("Location: ../login.php?error=user_not_found");
        exit();
    }
} elseif (isset($_POST["sign_up"])) {

    $name = trim($_POST["name"]);
    $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];
    $type = 'cleint';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("location:../login.php?msg=invalid_email");
        exit();
    }

    if ($password !== $confirm_password) {
        header("location:../login.php?msg=password_mismatch");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "SELECT email FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        header("location:../login.php?msg=email_exists");
        exit();
    }

    $sql = "INSERT INTO users (name, email, password, type) VALUES (:name, :email, :password, :type)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
    $stmt->bindParam(':type', $type, PDO::PARAM_STR);

    if ($stmt->execute()) {
        $_SESSION['user_email'] = $email;
        header("Location: cleint.php");
        exit();
    } else {
        header("location:../login.php?msg=registration_failed");
        exit();
    }
}

?>