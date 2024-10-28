<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin_user = "chiheb";
    $admin_pass = "chiheb2011";

    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $admin_user && $password === $admin_pass) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: add_user.php');
        exit();
    } else {
        $error = "Invalid login credentials!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* General Styles */
        body {
            background-color: #252525;
            color: white;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .header {
            background-color: #A81D20;
            padding: 10px;
            text-align: center;
        }
        .header img {
            max-height: 80px;
        }
        .login-box {
            background-color: #333;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: white;
        }
        .btn-custom {
            background-color: #A81D20;
            border: none;
        }
        .btn-custom:hover {
            background-color: #c2181e;
        }
        .footer {
            background-color: #A81D20;
            padding: 10px;
            text-align: center;
            margin-top: auto; /* Ensures footer stays at the bottom */
        }
        .footer .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .footer img {
            max-height: 70px;
            margin: 0 20px;
        }
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .footer .logo-container {
                flex-direction: row;
                justify-content: space-between;
            }
        }
    </style>
</head>
<body>

    <!-- Header with logo placeholder -->
    <div class="header">
        <img src="logo2.png" alt="Logo Placeholder" class="img-fluid">
    </div>

    <!-- Login Form -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="login-box">
                    <h2 class="text-center mb-4">Admin Login</h2>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <!-- Username field -->
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" id="username" required>
                        </div>
                        <!-- Password field -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" id="password" required>
                        </div>
                        <button type="submit" class="btn btn-custom btn-block w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer with two white logos -->
    <footer class="footer">
        <div class="logo-container">
            <img src="logo1.png" alt="Logo 1" class="img-fluid">
            <img src="logo2.png" alt="Logo 2" class="img-fluid">
        </div>
    </footer>

</body>
</html>
