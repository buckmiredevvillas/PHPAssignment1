<?php
require "database.php";

require_once"image_util.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = intval($_POST["id"]);
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $notes = $_POST["notes"];

    // Get current guest record
    $stmt = $conn->prepare("SELECT * FROM guests WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $current_guest = $result->fetch_assoc();

    $old_image_name = $current_guest["image"];
    $image_name = $old_image_name;
    $base_dir = "images/";

    // If a new image was selected
    if (
        isset($_FILES["image"]) &&
        $_FILES["image"]["error"] == UPLOAD_ERR_OK
    ) {

        $original_filename = basename($_FILES["image"]["name"]);
        $upload_path = $base_dir . $original_filename;

        move_uploaded_file(
            $_FILES["image"]["tmp_name"],
            $upload_path
        );

        // Create _100 and _400 images
        process_image($base_dir, $original_filename);

        // Save the _100 filename in database
        $dot_pos = strrpos($original_filename, ".");

        $image_name =
            substr($original_filename, 0, $dot_pos)
            . "_100"
            . substr($original_filename, $dot_pos);

        // Delete old image files if they use Assignment 3 naming
        if (!empty($old_image_name) && strpos($old_image_name, "_100") !== false) {

            $old_base = substr(
                $old_image_name,
                0,
                strrpos($old_image_name, "_100")
            );

            $old_ext = substr(
                $old_image_name,
                strrpos($old_image_name, ".")
            );

            $old_files = [
                $old_base . $old_ext,
                $old_base . "_100" . $old_ext,
                $old_base . "_400" . $old_ext
            ];

            foreach ($old_files as $file) {

                $path = $base_dir . $file;

                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }
    }

    // Update guest
    $stmt = $conn->prepare(
        "UPDATE guests
         SET first_name = ?,
             last_name = ?,
             email = ?,
             phone = ?,
             notes = ?,
             image = ?
         WHERE id = ?"
    );

    $stmt->bind_param(
        "ssssssi",
        $first_name,
        $last_name,
        $email,
        $phone,
        $notes,
        $image_name,
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
    <?php include("header.php"); ?>

<h2>Edit Guest</h2>

<form method="POST" enctype="multipart/form-data">

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
    <?php if (!empty($guest["image"])) { ?>

    <label>Current Image</label>

    <img
        id="imagePreview"
        src="images/<?php echo htmlspecialchars($guest["image"]); ?>"
        alt="<?php echo htmlspecialchars($guest["first_name"] . " " . $guest["last_name"]); ?>"
        height="100"
    >

<?php } ?>

<label>Update Image</label>

<input
    type="file"
    name="image"
    id="image"
    accept="image/*"
>

    <button type="submit">Update Guest</button>

</form>

<p><a href="index.php">Back to Guest List</a></p>
<script>
const fileInput = document.getElementById("image");
const imagePreview = document.getElementById("imagePreview");

fileInput.addEventListener("change", function () {

    const file = this.files[0];

    if (file && imagePreview) {
        const imageURL = URL.createObjectURL(file);
        imagePreview.src = imageURL;
    }
});
</script>
</body>
<?php include("footer.php"); ?>
</html>