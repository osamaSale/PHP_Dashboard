<?php
include('./connection.php');

$id = $_POST["uid"];
$sql = "delete from users where id='{$id}'";
if ($conn->query($sql)) {
    echo "true";
} else {
    echo "false";
}
?>