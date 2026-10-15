<?php
session_start();
require "database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT * FROM registrations WHERE email = ?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user["password"])) {

        $_SESSION["user_id"] = $user["id"];
        $_SESSION["first_name"] = $user["first_name"];

        header("Location: index.php");
        exit;

    } else {
        $message = "Invalid email or password.";
    }
}
?>

<?php include "header.php"; ?>

<h2>Login</h2>

<?php
if ($message != "") {
    echo "<p>$message</p>";
}
?>

<form method="POST">

    <label>Email</label>
    <input type="email" name="email" required>

    <label>Password</label>
    <input type="password" name="password" required>

    <button type="submit">Login</button>

</form>

<p>
    Not registered?
    <a href="register.php">Register</a>
</p>

<?php include "footer.php"; ?>