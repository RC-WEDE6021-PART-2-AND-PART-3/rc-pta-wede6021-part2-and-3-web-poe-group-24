<?php
session_start();

// PROTECT PAGE
if(!isset($_SESSION['admin'])){
    header("Location: adminLogin.php");
    exit;
}

include "DBConn.php";

/* ADD USER */
if(isset($_POST['add'])){
$name=$_POST['name'];
$email=$_POST['email'];
$pass=password_hash($_POST['password'], PASSWORD_DEFAULT);

$conn->query("INSERT INTO tblUser(name,email,password,is_verified)
VALUES('$name','$email','$pass',1)");
}

/* VERIFY USER */
if(isset($_GET['verify'])){
$id = (int)$_GET['verify'];
$conn->query("UPDATE tblUser SET is_verified=1 WHERE user_id=$id");
}

/* DELETE USER */
if(isset($_GET['delete'])){
$id = (int)$_GET['delete'];
$conn->query("DELETE FROM tblUser WHERE user_id=$id");
}

/* GET USERS */
$res=$conn->query("SELECT * FROM tblUser");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Panel</title>
<style>
body{
    background:#111;
    color:white;
    font-family:Arial;
    padding:20px;
}
h2{text-align:center;}

.card{
    background:#222;
    padding:10px;
    margin:10px;
    border-radius:5px;
}

a{
    margin-left:10px;
    text-decoration:none;
}

.verify{color:lightgreen;}
.edit{color:orange;}
.delete{color:red;}

input{
    padding:8px;
    margin:5px;
}
button{
    padding:8px 15px;
}
</style>
</head>

<body>

<h2>Admin Panel</h2>

<!-- ADD USER -->
<h3>Add New User</h3>
<form method="POST">
<input name="name" placeholder="Name" required>
<input name="email" placeholder="Email" required>
<input name="password" placeholder="Password" required>
<button name="add">Add</button>
</form>

<hr>

<!-- USER LIST -->
<h3>All Users</h3>

<?php
while($row=$res->fetch_assoc()){
echo "<div class='card'>";

echo "<b>".$row['name']."</b> - ".$row['email'];

if(!$row['is_verified']){
echo " <a class='verify' href='?verify=".$row['user_id']."'>Verify</a>";
}

echo " <a class='edit' href='editUser.php?id=".$row['user_id']."'>Edit</a>";
echo " <a class='delete' href='?delete=".$row['user_id']."'>Delete</a>";

echo "</div>";
}
?>
<h2>Seller Requests</h2>

<?php

$result =
mysqli_query(
$conn,
"SELECT * FROM tblSellerRequest"
);

while($row=mysqli_fetch_assoc($result))
{
?>

<div>

<p>
Seller:
<?php echo $row['seller_name']; ?>
</p>

<p>
Brand:
<?php echo $row['brand']; ?>
</p>

<p>
Status:
<?php echo $row['status']; ?>
</p>

<a href="approveSeller.php?id=<?php
echo $row['request_id']; ?>">
Approve
</a>

</div>

<hr>

<?php
}
?>
</body>
</html>