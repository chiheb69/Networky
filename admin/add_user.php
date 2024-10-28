<?php
session_start();

// Load users and client IDs from JSON files
$users_file = '../backend/users.json';
$ids_file = '../backend/client_ids.json';

// Load users or initialize to an empty array if file is empty
$users = json_decode(file_get_contents($users_file), true);
$users = is_array($users) ? $users : [];  // Ensure $users is an array

$client_ids = json_decode(file_get_contents($ids_file), true);
$client_ids = is_array($client_ids) ? $client_ids : [];  // Ensure $client_ids is an array

// Function to count how many users each client has added
function count_added_users($client_id, $client_ids) {
    return isset($client_ids[$client_id]) ? count($client_ids[$client_id]) : 0;
}

// Handle delete user request
if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    // Remove user from users.json
    $users = array_filter($users, function($user) use ($delete_id) {
        return $user['id_number'] !== $delete_id;
    });

    // Save the updated users array back to users.json
    file_put_contents($users_file, json_encode(array_values($users)));

    // Remove user added IDs from client_ids.json
    foreach ($client_ids as $client_id => $ids) {
        $client_ids[$client_id] = array_filter($ids, function($id) use ($delete_id) {
            return $id !== $delete_id;
        });
    }
    file_put_contents($ids_file, json_encode($client_ids));
}

// Handle adding new users
$error_message = ""; // Variable to store error messages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'], $_POST['id_number'], $_POST['class'])) {
    $new_id_number = $_POST['id_number'];

    // Check if the ID already exists
    $id_exists = array_filter($users, function($user) use ($new_id_number) {
        return $user['id_number'] === $new_id_number;
    });

    if (!empty($id_exists)) {
        $error_message = "User with ID '$new_id_number' already exists. Please use a different ID.";
    } else {
        $new_user = [
            'name' => $_POST['name'],
            'id_number' => $new_id_number,
            'class' => $_POST['class']
        ];

        // Add the new user to the users array
        $users[] = $new_user;

        // Save the updated users array back to users.json
        file_put_contents($users_file, json_encode($users));
    }
}

// Sort users by number of users added from high to low
usort($users, function($a, $b) use ($client_ids) {
    $a_count = count_added_users($a['id_number'], $client_ids);
    $b_count = count_added_users($b['id_number'], $client_ids);
    return $b_count <=> $a_count; // Sort in descending order
});
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Admin Dashboard</title>
</head>
<body class="bg-light">
    <div class="container">
        <h2>Admin Dashboard</h2>

        <!-- Error message display -->
        <?php if ($error_message): ?>
            <div class="alert alert-danger" role="alert">
                <?= htmlspecialchars($error_message) ?>
            </div>
        <?php endif; ?>

        <!-- Form to add new users -->
        <form action="" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>
            <div class="mb-3">
                <label for="id_number" class="form-label">ID Number</label>
                <input type="text" name="id_number" class="form-control" id="id_number" required>
            </div>
            <div class="mb-3">
                <label for="class" class="form-label">Class</label>
                <input type="text" name="class" class="form-control" id="class" required>
            </div>
            <button type="submit" class="btn btn-primary">Add User</button>
        </form>

        <!-- Table to display all users -->
        <h3 class="mt-4">All Users</h3>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID Number</th>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Number of Users Added</th> <!-- New column to show the count of added users -->
                    <th>Action</th> <!-- Column for actions (delete) -->
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id_number']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['class']) ?></td>
                        <td><?= count_added_users($user['id_number'], $client_ids) ?></td> <!-- Show the number of users added -->
                        <td>
                            <form action="" method="POST" class="d-inline">
                                <input type="hidden" name="delete_id" value="<?= htmlspecialchars($user['id_number']) ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this user?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Logout button -->
        <form action="logout.php" method="POST" class="mt-3">
            <button type="submit" class="btn btn-danger">Logout</button>
        </form>
    </div>
</body>
</html>
