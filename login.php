<?php
require_once 'classes/User.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = User::login($username, $password);
    if ($user) {
        $_SESSION['id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: index.php");
        exit();
    } else {
        $error_message = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login - Electronix Store</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="icon" href="./images/electronics.png" type="image/x-icon">
    <link rel="stylesheet" href="CSS/styles.css">
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .login-container h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #86a194;
            margin-bottom: 20px;
            text-align: center;
        }

        .login-container .form-group label {
            font-weight: 600;
            color: #333;
        }

        .login-container .form-control {
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
        }

        .login-container .btn-primary {
            background-color: #86a194;
            border-color: #86a194;
            width: 100%;
            padding: 12px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 5px;
            transition: background-color 0.3s ease-in-out;
        }

        .login-container .btn-primary:hover {
            background-color: #6f867c;
            border-color: #6f867c;
        }

        .login-container p {
            margin-top: 20px;
            text-align: center;
            color: #555;
        }

        .login-container a {
            color: #86a194;
            text-decoration: none;
            font-weight: 600;
        }

        .login-container a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #dc3545;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="login-container">
        <h1>Login</h1>
        <?php if (isset($error_message)): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" name="username" id="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" required>
            </div>
            <input type="submit" class="btn btn-primary" value="Login">
        </form>
        <p>Don't have an account? <a href="register.php">Register now</a></p>
    </div>

    <?php include("footer.php") ?>

</body>

</html>