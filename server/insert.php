<?php
include("./connection.php");
include("./function.php");
//echo "osama";
$image = '';
if ($_FILES["image"]["name"] != '') {
  $image = upload_image();
} else {
  $image = $_POST["hidden_user_image"];
}
$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];
$phone = $_POST['phone'];
$authorization = $_POST['authorization'];
$options = ["cost" => 15];
$hash = password_hash($password, PASSWORD_BCRYPT, $options);
$sql = "INSERT  INTO `users`(`name` , `email` , `password` ,`image` ,`phone` ,`authorization`)
 VALUE  (' {$name} ' , ' {$email} ' , ' {$hash} ' , '".$image ."'  , ' {$phone} ' , ' {$authorization} ')";

if (mysqli_query($conn, $sql)) {
    $response = [
        'status' => 'ok',
        'success' => true,
        'message' => 'Record created succesfully!'
    ];
    print_r(json_encode($response));
} else {
    $response = [
        'status' => 'ok',
        'success' => false,
        'message' => 'Record created failed!'
    ];
    print_r(json_encode($response));
} 
?>