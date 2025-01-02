<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style_login.css">
    <title>Signin & Sign up</title>
</head>

<body>
    <div class="wrapper">
        <div class="form-container sign-up">
            <form action="./php/verification.php" method="POST">
                <h2>sign up</h2>
                <div class="form-group">
                    <input type="text" name="name" required>
                    <i class="fas fa-user"></i>
                    <label for="">username</label>
                </div>
                <div class="form-group">
                    <input type="email" name="email" required>
                    <i class="fas fa-at"></i>
                    <label for="">email</label>
                </div>
                <div class="form-group">
                    <input type="password" name="password" required>
                    <i class="fas fa-lock"></i>
                    <label for="">password</label>
                </div>
                <div class="form-group">
                    <input type="password" name="confirm_password" required>
                    <i class="fas fa-lock"></i>
                    <label for="">confirm password</label>
                </div>
                <button type="submit" name="sign_up" class="btn">sign up</button>
                <div class="link">
                    <p>You already have an account?<a href="#" class="signin-link"> sign in</a></p>
                </div>
                <div class="link">
                    <?php
                    if (isset($_GET['msg'])) {
                        $msg = $_GET['msg'];
                        echo '<script>';
                        if ($msg == 'invalid_email') {
                            echo 'alert("Invalid email format. Please try again.");';
                        } elseif ($msg == 'password_mismatch') {
                            echo 'alert("Passwords do not match. Please try again.");';
                        } elseif ($msg == 'email_exists') {
                            echo 'alert("This email is already registered. Please use a different email.");';
                        } elseif ($msg == 'registration_failed') {
                            echo 'alert("Registration failed. Please try again later.");';
                        }
                        echo '</script>';
                    }
                    ?>
                </div>
            </form>
        </div>
        <div class="form-container sign-in">
            <form action="./php/verification.php" method="post">
                <h2>login</h2>
                <div class="form-group">
                    <input type="email" name="email" required>
                    <i class="fas fa-user"></i>
                    <label for="">email</label>
                </div>
                <div class="form-group">
                    <input type="password" name="password" required>
                    <i class="fas fa-lock"></i>
                    <label for="">password</label>
                </div>
                <div class="forgot-pass">
                    <!-- <a href="#">forgot password?</a> -->
                </div>
                <button type="submit" name="login" class="btn">login</button>
                <div class="link">
                    <p>Don't have an account?<a href="#" class="signup-link"> sign up</a></p>
                </div>
                <?php
                if (isset($_GET['error'])) {
                    $error = $_GET['error'];
                    echo "<script>";
                    if ($error == 'incorrect_password') {
                        echo "alert('Incorrect password. Please try again.');";
                    } elseif ($error == 'user_not_found') {
                        echo "alert('User not found. Please check your email.');";
                    } elseif ($error == 'invalid_email') {
                        echo "alert('The email address is invalid. Please enter a valid email.');";
                    }
                    echo "</script>";
                }
                ?>
            </form>
        </div>
    </div>
    <script src="https://kit.fontawesome.com/9e5ba2e3f5.js" crossorigin="anonymous"></script>
    <script src="./assets/js/main_login.js"></script>
</body>

</html>