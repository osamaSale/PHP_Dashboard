<?php
error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header('Access-Control-Allow-Headers: Content-Type , Access-Control-Allow-Headers , Authorization,X-Request-with');
include("./function.php");


$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod === "POST") {
    $inputData = json_decode(file_get_contents("php://input"), true);
    if (empty($inputData)) {
        $user = editUser($_POST, $_GET);
    } else {
        $user = editUser($inputData, $_GET);
    }
    echo $user;
} else {
    $data = [
        "status" => 206,
        "massage" => $requestMethod . 'Method Not Allowed'
    ];
    header("HTTP/1.0 206 Method Not Allowed");
    echo json_encode($data);
}
