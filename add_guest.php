<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require "database.php";

require_once"image_util.php";

    

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $notes = $_POST["notes"];

    $image = null;

    $image = null;
$base_dir = "images/";

if (isset($_FILES["image"]) && $_FILES["image"]["error"] == UPLOAD_ERR_OK) {

    $original_filename = basename($_FILES["image"]["name"]);
    $upload_path = $base_dir . $original_filename;

    move_uploaded_file(
        $_FILES["image"]["tmp_name"],
        $upload_path
    );

    process_image($base_dir, $original_filename);

    // save _100 version in database
    $dot_pos = strpos($original_filename, ".");

    $image = substr($original_filename, 0, $dot_pos)
           . "_100"
           . substr($original_filename, $dot_pos);
}
    $user_id = $_SESSION["user_id"];
    $sql = "INSERT INTO guests
            (first_name, last_name, email, phone, notes, image, user_id)
            VALUES
            ('$first_name', '$last_name', '$email', '$phone', '$notes', '$image', $user_id)";

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
    <?php include("header.php"); ?>

<h2>Add New Guest</h2>

<form method="POST" enctype="multipart/form-data">

    <label>First Name</label>
    <input type="text" name="first_name" required>

    <label>Last Name</label>
    <input type="text" name="last_name" required>

    <label>Email</label>
    <input type="email" name="email">

    <label>Phone</label>
    <input type="text" name="phone">

    <label>Notes</label>
    <textarea name="notes" id="notes"></textarea>

    <label>Upload Image</label>
    <input type="file" name="image" accept=".jpg,.jpeg,.png,.gif">

    <button type="submit">Add Guest</button>

</form>

<p><a href="index.php">Back to Guest List</a></p>
<script>
document.getElementById("notes").value = "";
</script>
</body>
 <?php include("footer.php"); ?>
</html>