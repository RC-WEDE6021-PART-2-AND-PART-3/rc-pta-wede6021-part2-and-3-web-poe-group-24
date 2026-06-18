<?php

include("DBConn.php");

$id = $_GET['id'];

mysqli_query(
$conn,
"UPDATE tblSellerRequest
SET status='Approved'
WHERE request_id='$id'"
);

header("Location: admin.php");
exit();
?>