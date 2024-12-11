<?php
// Database credentials
$host = 'localhost';
$dbname = 'todo_app';
$username = 'root'; // Update if needed
$password = '';     // Update if needed

// Connect to the database
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['new_task'])) {
        // Add a new task
        $task = htmlspecialchars($_POST['task']);
        if (!empty($task)) {
            $stmt = $pdo->prepare("INSERT INTO tasks (task) VALUES (:task)");
            $stmt->execute(['task' => $task]);
        }
    } elseif (isset($_POST['delete_task'])) {
        // Delete a task
        $id = intval($_POST['id']);
        $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}

// Fetch tasks
$stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List Manager</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        form {
            margin-bottom: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        input[type="text"] {
            padding: 10px;
            width: 300px;
            margin-right: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border-collapse: collapse;
            background: #fff;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .delete-btn {
            color: #ff0000;
            border: none;
            background: none;
            cursor: pointer;
        }
        .delete-btn:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<h1>To-Do List Manager</h1>

<!-- Form to Add New Task -->
<form method="POST">
    <input type="text" name="task" placeholder="Enter a new task" required>
    <button type="submit" name="new_task">Add Task</button>
</form>

<!-- Tasks Table -->
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Task</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($tasks)): ?>
            <tr>
                <td colspan="3" style="text-align: center;">No tasks found.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($tasks as $task): ?>
                <tr>
                    <td><?= htmlspecialchars($task['id']); ?></td>
                    <td><?= htmlspecialchars($task['task']); ?></td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="id" value="<?= $task['id']; ?>">
                            <button type="submit" name="delete_task" class="delete-btn">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
