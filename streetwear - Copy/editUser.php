<?php
session_start();
include "DBConn.php";

$id=$_GET['id'];

$res=$conn->query("SELECT * FROM tblUser WHERE user_id=$id");
$user=$res->fetch_assoc();

if(isset($_POST['update'])){
$name=$_POST['name'];
$email=$_POST['email'];

$conn->query("UPDATE tblUser SET name='$name', email='$email' WHERE user_id=$id");

header("Location: admin.php");
}
?>

<h2>Edit User</h2>

<form method="POST">
<input name="name" value="<?php echo $user['name']; ?>">
<input name="email" value="<?php echo $user['email']; ?>">
<button name="update">Update</button>
</form>