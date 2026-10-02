<?php
require 'koneksi_db.php'; // include the database connection file

// Example CRUD operations using the PDO connection ($pdo)
// Create
// $sql = "INSERT INTO users (name, email, address) VALUES (?, ?, ?)";
// $stmt = $pdo->prepare($sql);
// $stmt->execute(['Jone Doe', 'jane@example.com', '123 Main St']);

// Read
$sql = "SELECT * FROM users";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll();

// // Update
// $sql = "UPDATE users SET name = ? WHERE id = ?";
// $stmt = $pdo->prepare($sql);
// $stmt->execute(['Jane Doe 222', 2]);

// // Delete
// $sql = "DELETE FROM users WHERE id = ?";
// $stmt = $pdo->prepare($sql);
// $stmt->execute([3]);
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD Example</title>
</head>
<body>
    <h1>Users</h1>
    <ul>
        <?php foreach ($users as $user): ?>
            <li>
                ID: <?php echo htmlspecialchars($user['id']); ?><br>
                Name: <?php echo htmlspecialchars($user['name']); ?><br>
                Email: <?php echo htmlspecialchars($user['email']); ?><br>
                Address: <?php echo htmlspecialchars($user['address']); ?><br>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>