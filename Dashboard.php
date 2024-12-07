<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Portfolio_manage";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handling Delete
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM contact_messages WHERE id = $delete_id";
    if ($conn->query($delete_sql) === TRUE) {
        echo "Record deleted successfully!";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Handling Edit
if (isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    $edit_name = $_POST['name'];
    $edit_email = $_POST['email'];
    $edit_message = $_POST['message'];

    $update_sql = "UPDATE contact_messages SET name='$edit_name', email='$edit_email', message='$edit_message' WHERE id=$edit_id";
    if ($conn->query($update_sql) === TRUE) {
        echo "Record updated successfully!";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

// Fetching all contact messages
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Manage Contact Messages</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1E2A47; /* Dark blue background */
            color: white;
            margin: 20px;
        }
        h2 {
            color: #4CAF50; /* Accent color */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #28334A; /* Darker background for table */
        }
        table, th, td {
            border: 1px solid #4CAF50; /* Greenish border */
        }
        th, td {
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #3E4A64; /* Slightly lighter blue */
        }
        td {
            background-color: #28334A;
        }
        .form-container {
            margin-bottom: 20px;
            background-color: #2A3B57; /* Dark blue form background */
            padding: 20px;
            border-radius: 8px;
        }
        .form-container input, .form-container textarea {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 5px;
            border: 1px solid #4CAF50;
            background-color: #1E2A47;
            color: white;
        }
        .form-container button {
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container button:hover {
            background-color: #45A049;
        }
        a {
            color: #4CAF50;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            color: #45A049;
        }
        .actions {
            text-align: center;
        }
    </style>
</head>
<body>

    <h2>Contact Messages Dashboard</h2>

    <!-- Form for adding/editing a message -->
    <div class="form-container">
        <h3>Edit or Add Message</h3>
        <form method="POST" action="">
            <input type="hidden" name="edit_id" id="edit_id" value="">
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" required>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required>

            <label for="message">Message:</label>
            <textarea name="message" id="message" required></textarea>

            <button type="submit">Save</button>
        </form>
    </div>

    <!-- Table of contact messages -->
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Date Sent</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" . $row['email'] . "</td>";
                    echo "<td>" . $row['message'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td class='actions'>";
                    // Edit link
                    echo "<a href='#' onclick='editMessage(" . $row['id'] . ", \"" . addslashes($row['name']) . "\", \"" . addslashes($row['email']) . "\", \"" . addslashes($row['message']) . "\")'>Edit</a> | ";
                    // Delete link
                    echo "<a href='?delete_id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to delete this message?\")'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No messages found.</td></tr>";
            }
            ?>
        </tbody>
    </table>

    <script>
        function editMessage(id, name, email, message) {
            document.getElementById("edit_id").value = id;
            document.getElementById("name").value = name;
            document.getElementById("email").value = email;
            document.getElementById("message").value = message;
        }
    </script>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
