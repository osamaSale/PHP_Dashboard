<?php

error_reporting(0);
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include("./function.php");
$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod === "DELETE") {
    $deleteUser = deleteUser($_GET);
    echo $deleteUser;
} else {
    $data = [
        "status" => 203,
        "massage" => $requestMethod . 'Method Not Allowed'
    ];
    header("HTTP/1.0 203 Method Not Allowed");
    echo json_encode($data);
}
