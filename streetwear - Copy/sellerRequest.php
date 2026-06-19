<?php
include("DBConn.php");

if(isset($_POST['submit']))
{
    $seller = $_POST['seller'];
    $brand = $_POST['brand'];
    $description = $_POST['description'];

    $image = $_FILES['image']['name'];

    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        "uploads/".$image
    );

    $sql = "INSERT INTO tblSellerRequest
    (seller_name,brand,description,image,status)

    VALUES
    ('$seller','$brand','$description','$image','Pending')";

    mysqli_query($conn,$sql);

    echo "Request Submitted";
}
?>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="seller" placeholder="Seller Name" required>

<input type="text" name="brand" placeholder="Brand" required>

<textarea name="description"
placeholder="Description"></textarea>

<input type="file" name="image" required>

<button type="submit" name="submit">
Submit Request
</button>

</form>