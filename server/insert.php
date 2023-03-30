<?php
include("connection.php");


function upload_image()
{
    if (isset($_FILES["image"])) {
        $extension = explode('.', $_FILES['image']['name']);
        $new_name = rand() . '.' . $extension[1];
        $destination = './image/' . $new_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $destination);
        return $new_name;
    }
}
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$phone = $_POST['phone'];
$image = upload_image();
$authorization = $_POST['authorization'];
$options = ["cost" => 15];
$hash = password_hash($password, PASSWORD_BCRYPT, $options);

$sql1 = "select * from users where email='$email'";

$duplicate = mysqli_query($conn, $sql1);


if (mysqli_num_rows($duplicate) > 0) {
    echo "Email ID already exists";
} else {
    $sql = "INSERT INTO users
    (name, email ,password ,image ,phone ,authorization) 
    VALUES('$name', '$email' ,'$hash', '$image' ,'$phone' , '$authorization')";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        echo ("successfully created");
    } else {
        echo ("unknown error occurred");
    }
}


