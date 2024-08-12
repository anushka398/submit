<!DOCTYPE html>
<html>
<head>
    <title>Submit Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h2 {
            color: #333;
        }
        form {
            background-color: #e2c9f8;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 50%;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #380565;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #380565;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f5eeb2;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        .action-buttons form {
            display: inline;
        }
        .delete-button {
            background-color: #d1012c;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-button:hover {
            background-color: #d1012c;
        }
    </style>
    <script>
        function confirmDelete(index) {
            if (confirm("Are you sure you want to delete this entry?")) {
                document.getElementById('deleteForm' + index).submit();
            }
        }
    </script>
</head>
<body>

<?php
// Start session to store form data
session_start();

// Initialize session data array if not already initialized
if (!isset($_SESSION['data'])) {
    $_SESSION['data'] = [];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['update']) && !isset($_POST['edit'])) {
    $id = htmlspecialchars($_POST['id']);
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $email = htmlspecialchars($_POST['email']);
    $phone_number = htmlspecialchars($_POST['phone_number']);

    // Save the data in session
    $_SESSION['data'][] = ['id' => $id, 'first_name' => $first_name, 'last_name' => $last_name, 'email' => $email, 'phone_number' => $phone_number];
}

// Handle delete request
if (isset($_POST['delete'])) {
    $index = $_POST['index'];
    unset($_SESSION['data'][$index]);
    $_SESSION['data'] = array_values($_SESSION['data']);
}

// Handle edit request
$edit_data = null;
if (isset($_POST['edit'])) {
    $index = $_POST['index'];
    $edit_data = $_SESSION['data'][$index];
}

// Handle update request
if (isset($_POST['update'])) {
    $index = $_POST['index'];
    $_SESSION['data'][$index] = [
        'id' => htmlspecialchars($_POST['id']),
        'first_name' => htmlspecialchars($_POST['first_name']),
        'last_name' => htmlspecialchars($_POST['last_name']),
        'email' => htmlspecialchars($_POST['email']),
        'phone_number' => htmlspecialchars($_POST['phone_number'])
    ];
}
?>

<h2>Submit Form</h2>
<form action="" method="post">
    <label for="id">ID:</label>
    <input type="text" id="id" name="id" value="<?php echo $edit_data ? $edit_data['id'] : ''; ?>" required>
    <label for="first_name">First Name:</label>
    <input type="text" id="first_name" name="first_name" value="<?php echo $edit_data ? $edit_data['first_name'] : ''; ?>" required>
    <label for="last_name">Last Name:</label>
    <input type="text" id="last_name" name="last_name" value="<?php echo $edit_data ? $edit_data['last_name'] : ''; ?>" required>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" value="<?php echo $edit_data ? $edit_data['email'] : ''; ?>" required>
    <label for="phone_number">Telephone Number:</label>
    <input type="text" id="phone_number" name="phone_number" value="<?php echo $edit_data ? $edit_data['phone_number'] : ''; ?>" required>
    <?php if ($edit_data): ?>
        <input type="hidden" name="index" value="<?php echo $index; ?>">
        <input type="submit" name="update" value="Update">
    <?php else: ?>
        <input type="submit" value="Submit">
    <?php endif; ?>
</form>

<?php if (!empty($_SESSION['data'])): ?>
    <h2>Submitted Form Data</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Telephone Number</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($_SESSION['data'] as $index => $entry): ?>
            <tr>
                <td><?php echo $entry['id']; ?></td>
                <td><?php echo $entry['first_name']; ?></td>
                <td><?php echo $entry['last_name']; ?></td>
                <td><?php echo $entry['email']; ?></td>
                <td><?php echo $entry['phone_number']; ?></td>
                <td class="action-buttons">
                    <form id="deleteForm<?php echo $index; ?>" action="" method="post" style="display:inline;">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <input type="hidden" name="delete" value="true">
                        <button type="button" class="delete-button" onclick="confirmDelete(<?php echo $index; ?>)">Delete</button>
                    </form>
                    <form action="" method="post" style="display:inline;">
                        <input type="hidden" name="index" value="<?php echo $index; ?>">
                        <input type="submit" name="edit" value="Edit">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>



