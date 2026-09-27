<?php
require "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $notes = $_POST["notes"];

    $sql = "INSERT INTO guests (first_name, last_name, email, phone, notes)
            VALUES ('$first_name', '$last_name', '$email', '$phone', '$notes')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit;
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add Guest</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 40px;
        }

        h1 {
            color: #2c6e49;
        }

        form {
            background-color: white;
            padding: 20px;
            max-width: 500px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input,
        textarea {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }

        button {
            margin-top: 15px;
            padding: 10px 15px;
            background-color: #2c6e49;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

<h1>Add New Guest</h1>

<form method="POST">

    <label>First Name</label>
    <input type="text" name="first_name" required>

    <label>Last Name</label>
    <input type="text" name="last_name" required>

    <label>Email</label>
    <input type="email" name="email">

    <label>Phone</label>
    <input type="text" name="phone">

    <label>Notes</label>
    <textarea name="notes"></textarea>

    <button type="submit">Add Guest</button>

</form>

<p><a href="index.php">Back to Guest List</a></p>

</body>
</html>