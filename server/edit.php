<?php
include ("connection.php" );
include ("function.php" );


$image = '';
if ($_FILES["image"]["name"] != '') {
  $image = upload_image();
} else {
  $image = $_POST["hidden_user_image"];
}
$name= $_POST['name'];
$email= $_POST['email'];
$password= $_POST['password'];
$phone= $_POST['phone'];
$authorization= $_POST['authorization'];
$id= $_POST['id' ];
$sql= "UPDATE `users`  SET  `name` = '". $name."'  , `email` =  '". $email."' , 
`password`  =  '".$password ."' , `image`  =  '".$image ."', `phone`  ='".$phone ."' , `authorization`  =  '".$authorization ."' WHERE `id` = $id " ;

if(mysqli_query($conn , $sql)){
    $response = [
        'status'=>'ok',
        'success'=>true,
        'message'=>'Record updated succesfully!'
    ];
    print_r(json_encode($response));
}else{
    $response = [
        'status'=>'ok',
        'success'=>false,
        'message'=>'Record updated failed!'
    ];
    print_r(json_encode($response));
} 
?>