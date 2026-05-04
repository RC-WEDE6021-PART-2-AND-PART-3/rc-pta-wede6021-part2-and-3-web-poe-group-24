<?php
session_start();
include "DBConn.php";

$error="";

if($_SERVER["REQUEST_METHOD"]=="POST"){
$username=$_POST["username"];
$password=$_POST["password"];

$res=$conn->query("SELECT * FROM tblAdmin WHERE username='$username'");

if($res->num_rows>0){
$admin=$res->fetch_assoc();

if(password_verify($password,$admin['password'])){
$_SESSION['admin']=$username;
header("Location: admin.php");
exit;
}else $error="Wrong password";
}else $error="Admin not found";
}
?>

<h2>Admin Login</h2>

<form method="POST">
<input name="username" placeholder="Username" required>
<input type="password" name="password" placeholder="Password" required>
<button>Login</button>
</form>

<p><?php echo $error; ?></p>