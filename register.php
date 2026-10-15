<?php
require "database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare(
        "INSERT INTO registrations
        (first_name, last_name, email, password)
        VALUES (?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "ssss",
        $first_name,
        $last_name,
        $email,
        $hashed_password
    );

    if ($stmt->execute()) {
        $message = "Registration successful.";
    } else {
        $message = "Registration failed.";
    }
}
?>

<?php include "header.php"; ?>

<h2>Register</h2>

<?php
if ($message != "") {
    echo "<p>$message</p>";
}
?>

<form method="POST">

    <label>First Name</label>
    <input type="text" name="first_name" required>

    <label>Last Name</label>
    <input type="text" name="last_name" required>

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit">Register</button>

</form>

<p>
    Already registered?
    <a href="login.php">Login</a>
</p>

<?php include "footer.php"; ?>