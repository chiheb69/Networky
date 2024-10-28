<?php
session_start();
if (!isset($_SESSION['user_logged_in']) || !isset($_SESSION['user_id'])) {
    header('Location: index.php'); // This redirects to client/index.php
    exit();
}

// Load users and client IDs from JSON files
$users_file = '../backend/users.json';
$ids_file = '../backend/client_ids.json';

$users = json_decode(file_get_contents($users_file), true);
$client_ids = json_decode(file_get_contents($ids_file), true);

// Get the logged-in user's ID from the session
$logged_in_id = $_SESSION['user_id'];

// Initialize added_ids for the user if not already set in client_ids.json
if (!isset($client_ids[$logged_in_id])) {
    $client_ids[$logged_in_id] = [];
}

// Handle form submission to add an ID
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_id = $_POST['id_number'];

    // Check if the entered ID exists in users.json
    $found_user = null;
    foreach ($users as $user) {
        if ($user['id_number'] === $input_id) {
            $found_user = $user;
            break;
        }
    }

    if (!$found_user) {
        $error = "The ID you entered does not exist!";
    } elseif (in_array($input_id, $client_ids[$logged_in_id])) {
        $error = "This ID has already been added!";
    } else {
        // Add the ID to the user's list in client_ids.json
        $client_ids[$logged_in_id][] = $input_id;
        file_put_contents($ids_file, json_encode($client_ids)); // Save the updated IDs list
        $success = "User with ID {$input_id} added successfully!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            padding: 20px;
            text-align: center;
        }
        .header img {
            max-height: 60px;
        }
        .content {
            flex-grow: 1;
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
            margin-top: auto;
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
        <img src="path/to/logo-placeholder.png" alt="Logo Placeholder" class="img-fluid">
    </div>

    <!-- Main Content -->
    <div class="container content mt-5">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['user_logged_in']) ?></h2>
        <p>Let's start the game</p>

        <!-- Form to input ID -->
        <form action="" method="POST">
            <div class="mb-3">
                <label for="id_number" class="form-label">Enter ID Number</label>
                <input type="text" name="id_number" class="form-control" id="id_number" required>
            </div>
            <button type="submit" class="btn btn-danger">Add</button>
        </form>

        <!-- Display error or success messages -->
        <?php if ($error): ?>
            <div class="alert alert-danger mt-3"><?= $error ?></div>
        <?php elseif ($success): ?>
            <div class="alert alert-success mt-3"><?= $success ?></div>
        <?php endif; ?>

        <!-- Display added IDs in a table -->
        <?php if (!empty($client_ids[$logged_in_id])): ?>
            <h3 class="mt-4">Added Users</h3>
            <table class="table table-bordered mt-3 text-white">
                <thead>
                    <tr>
                        <th>ID Number</th>
                        <th>Name</th>
                        <th>Class</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($client_ids[$logged_in_id] as $added_id): ?>
                        <?php
                        // Find the user details for this ID
                        foreach ($users as $user) {
                            if ($user['id_number'] === $added_id) {
                                echo "<tr><td>{$user['id_number']}</td><td>{$user['name']}</td><td>{$user['class']}</td></tr>";
                            }
                        }
                        ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Logout Button -->
        <form action="logout.php" method="POST" class="mt-3">
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>

    <!-- Footer with two white logos -->


</body>
</html>
