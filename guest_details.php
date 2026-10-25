
<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require "database.php";

$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

$stmt = $conn->prepare(
    "SELECT * FROM guests WHERE id = ? AND user_id = ?"
);

$stmt->bind_param("ii", $id, $_SESSION["user_id"]);
$stmt->execute();

$result = $stmt->get_result();
$guest = $result->fetch_assoc();

if (!$guest) {
    die("Guest not found.");
}

// Convert _100 image name to _400 image name
$image_name = $guest["image"];
$dot_position = strrpos($image_name, ".");
$base_name = substr($image_name, 0, $dot_position);
$extension = substr($image_name, $dot_position);

if (substr($base_name, -4) == "_100") {
    $base_name = substr($base_name, 0, -4);
}

$image_400 = $base_name . "_400" . $extension;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Guest Details</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 40px;
    }

    h1, h2 {
        color: #2c6e49;
    }

    img {
        border-radius: 8px;
        margin-bottom: 15px;
    }

    p {
        font-size: 16px;
        margin: 8px 0;
    }

    a {
        color: #2c6e49;
        font-weight: bold;
    }

    footer {
        margin-top: 30px;
    }
</style>
</head>

<body>

<?php include("header.php"); ?>

<h2>Guest Details</h2>

<img
    src="images/<?php echo htmlspecialchars($image_400); ?>"
    alt="<?php echo htmlspecialchars($guest["first_name"] . " " . $guest["last_name"]); ?>"
    width="400"
>

<p><strong>First Name:</strong>
    <?php echo htmlspecialchars($guest["first_name"]); ?>
</p>

<p><strong>Last Name:</strong>
    <?php echo htmlspecialchars($guest["last_name"]); ?>
</p>

<p><strong>Email:</strong>
    <?php echo htmlspecialchars($guest["email"]); ?>
</p>

<p><strong>Phone:</strong>
    <?php echo htmlspecialchars($guest["phone"]); ?>
</p>

<p><strong>Notes:</strong>
    <?php echo htmlspecialchars($guest["notes"]); ?>
</p>

<p>
    <a href="index.php">Back to Guest List</a>
</p>

<?php include("footer.php"); ?>

</body>
</html>