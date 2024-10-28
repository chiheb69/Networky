<?php
session_start();
$file = '../backend/users.json';  // Path to your users.json file
$users = json_decode(file_get_contents($file), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $id_number = $_POST['id_number'];

    // Check if the user exists in the users.json file
    $found_user = null;
    foreach ($users as $user) {
        if ($user['name'] === $name && $user['id_number'] === $id_number) {
            $found_user = $user;
            break;
        }
    }

    if ($found_user) {
        // Set the user info in the session
        $_SESSION['user_logged_in'] = $found_user['name'];
        $_SESSION['user_id'] = $found_user['id_number'];  // Save user_id in session

        // Redirect to the dashboard after successful login
        header('Location: dashboard.php');
        exit();
    } else {
        $error = "Invalid login credentials! Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Networky</title>
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
            margin: 0 70px;
        }
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .login-box {
                padding: 20px;
            }
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
        <img src="client/logo2.png" alt="Logo Placeholder" class="img-fluid">
    </div>

    <!-- Login Form -->
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="login-box">
                    <h3 class="text-center mb-4">Welcome To the Game</h3>
                    <form action="" method="POST">
                        <!-- Name field -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <!-- ID Number field -->
                        <div class="mb-3">
                            <label for="id_number" class="form-label">ID Number</label>
                            <input type="text" class="form-control" id="id_number" name="id_number" required>
                        </div>
                        <button type="submit" class="btn btn-custom btn-block w-100">Login</button>
                    </form>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger mt-3"><?= $error ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer with two white logos -->
    <footer class="footer">
        <div class="logo-container">
            <img src="client/logo1.png" alt="Logo 1" class="img-fluid">
            <img src="client/logo2.png" alt="Logo 2" class="img-fluid">
        </div>
    </footer>

</body>
</html>
