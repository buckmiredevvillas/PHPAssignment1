<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require "database.php";

$sql = "SELECT * FROM guests ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kias Villa Guest Manager</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 40px;
        }

        h1 {
            color: #2c6e49;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #2c6e49;
            color: white;
        }

        .button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #2c6e49;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>


<body>

<?php include("header.php"); ?>
<p>
    Welcome, <?php echo htmlspecialchars($_SESSION["first_name"]); ?> |
    <a href="logout.php">Logout</a>
</p>


<a class="button" href="add_guest.php">Add New Guest</a>

<table>
    <tr>
        <th>Photo</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Notes</th>
        <th>Actions</th>
    </tr>

    <?php while ($guest = $result->fetch_assoc()) { ?>
        <tr>
            <td>
                <?php if (!empty($guest["image"])) { ?>
                    <img src="images/<?php echo htmlspecialchars($guest["image"]); ?>"
                         width="80"
                         alt="Guest Photo">
                <?php } ?>
</td>


            <td>
                <?php echo $guest["first_name"] . " " . $guest["last_name"]; ?>
            </td>

            <td><?php echo $guest["email"]; ?></td>

            <td><?php echo $guest["phone"]; ?></td>

            <td><?php echo $guest["notes"]; ?></td>

            <td>
                 <a href="guest_details.php?id=<?php echo $guest["id"]; ?>">View Details</a> |
<a href="edit_guest.php?id=<?php echo $guest["id"]; ?>">Edit</a> |
<a href="delete_guest.php?id=<?php echo $guest["id"]; ?>">Delete</a>
            </td>
        </tr>
    <?php } ?>

</table>
<?php include("footer.php"); ?>

</body>
</html>