<?php
include("connection.php");

$id = $_GET['id'];
$image = $_GET['image'];
$sql = "DELETE FROM  `users` WHERE `id`  =  $id ";

if (mysqli_query($conn, $sql)) {

    if ($image != '') {
        unlink("../image/users/" . $image);
    }
    $response = [
        'status' => 'ok',
        'success' => true,
        'message' => 'Record deleted succesfully!'
    ];
    print_r(json_encode($response));
} else {
    $response = [
        'status' => 'ok',
        'success' => false,
        'message' => 'Record deleted failed!'
    ];
    print_r(json_encode($response));
}
?>