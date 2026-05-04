<?php
include "DBConn.php";
session_start();

$message = "";

if (isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $message = "Please fill in all fields";
    } else {

        //  Check if user already exists
        $check = mysqli_query($conn, "SELECT * FROM tblUser WHERE email='$email'");

        if (mysqli_num_rows($check) > 0) {
            $message = "Email already exists";
        } else {

            $name = "New User"; // or add input field

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$insert = mysqli_query($conn,
    "INSERT INTO tblUser (name, email, password, is_verified)
     VALUES ('$name', '$email', '$hashedPassword', 0)"
);

            if ($insert) {
                $message = "Registration successful! You can now login.";
                header("Location: login.php");
                exit();
            } else {
                $message = "Error registering user: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>

<h2>Register</h2>

<form method="POST">
    <input type="text" name="name" placeholder="Name" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>

    <button type="submit" name="register">Register</button>
</form>

<p style="color:red;">
    <?php echo $message; ?>
</p>

</body>
</html>