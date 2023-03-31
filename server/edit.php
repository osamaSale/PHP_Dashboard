<?php
include("./connection.php");

$id = mysqli_real_escape_string($conn, $_POST["id"]);
$name = mysqli_real_escape_string($conn, $_POST["name1"]);
$email = mysqli_real_escape_string($conn, $_POST["email1"]);
$phone = mysqli_real_escape_string($conn, $_POST["phone1"]);
$authorization = mysqli_real_escape_string($conn, $_POST["authorization1"]);


if ($_FILES['image1']["name"] !== null) {
    $image = $_FILES['image1']["name"];
    $tmp_name = $_FILES['image1']['tmp_name'];
    move_uploaded_file($tmp_name, "../image/$image");
    $sql = "update users SET name='{$name}',email='{$email}',phone='{$phone}',image='{$image}',authorization='{$authorization}' where id ='{$id}'";
    if ($conn->query($sql)) {
        echo "Successfully";
    } else {
        echo false;
    }
} else {
    $sql1 = "select * from users where id='$id'";
    $duplicate = mysqli_query($conn, $sql1);
    $result = mysqli_num_rows($duplicate);
    if ($result > 0) {
        $image = $result['image'];
    }
    echo "osmaam";
}

?>