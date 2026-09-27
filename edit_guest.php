<?php
require "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = intval($_POST["id"]);
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $notes = $_POST["notes"];

    $stmt = $conn->prepare(
        "UPDATE guests
         SET first_name = ?, last_name = ?, email = ?, phone = ?, notes = ?
         WHERE id = ?"
    );

    $stmt->bind_param(
        "sssssi",
        $first_name,
        $last_name,
        $email,
        $phone,
        $notes,
        $id
    );

    $stmt->execute();

    header("Location: index.php");
    exit;
}

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

$stmt = $conn->prepare("SELECT * FROM guests WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$guest = $result->fetch_assoc();

if (!$guest) {
    die("Guest not found.");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Guest</title>

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

<h1>Edit Guest</h1>

<form method="POST">

    <input type="hidden"
           name="id"
           value="<?php echo $guest["id"]; ?>">

    <label>First Name</label>
    <input type="text"
           name="first_name"
           value="<?php echo htmlspecialchars($guest["first_name"]); ?>"
           required>

    <label>Last Name</label>
    <input type="text"
           name="last_name"
           value="<?php echo htmlspecialchars($guest["last_name"]); ?>"
           required>

    <label>Email</label>
    <input type="email"
           name="email"
           value="<?php echo htmlspecialchars($guest["email"]); ?>">

    <label>Phone</label>
    <input type="text"
           name="phone"
           value="<?php echo htmlspecialchars($guest["phone"]); ?>">

    <label>Notes</label>
    <textarea name="notes"><?php echo htmlspecialchars($guest["notes"]); ?></textarea>

    <button type="submit">Update Guest</button>

</form>

<p><a href="index.php">Back to Guest List</a></p>

</body>
</html>