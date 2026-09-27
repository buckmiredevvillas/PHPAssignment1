<?php
require "database.php";

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = intval($_POST["id"]);

    $stmt = $conn->prepare("DELETE FROM guests WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: index.php");
    exit;
}

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
    <title>Delete Guest</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 40px;
        }

        h1 {
            color: #2c6e49;
        }

        .box {
            background-color: white;
            padding: 20px;
            max-width: 500px;
        }

        button {
            padding: 10px 15px;
            background-color: #b22222;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>

<body>

<h1>Delete Guest</h1>

<div class="box">

    <p>
        Are you sure you want to delete
        <strong>
            <?php echo htmlspecialchars($guest["first_name"] . " " . $guest["last_name"]); ?>
        </strong>?
    </p>

    <form method="POST">
        <input type="hidden"
               name="id"
               value="<?php echo $guest["id"]; ?>">

        <button type="submit">Yes, Delete Guest</button>
    </form>

    <p>
        <a href="index.php">Cancel and Return to Guest List</a>
    </p>

</div>

</body>
</html>