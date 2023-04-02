<?php
include('db.php');
include('function.php');
if (isset($_POST["operation"])) {
  if ($_POST["operation"] == "Add") {
    $image = '';
    if ($_FILES["image"]["name"] != '') {
      $image = upload_image();
    }
    $password = 'my secret password';
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $statement = $connection->prepare("
   INSERT INTO users (name, email,password ,phone,authorization, image) 
   VALUES (:name, :email,:password,:phone , :authorization , :image)
  ");
    $result = $statement->execute(
      array(
        ':name' => $_POST["name"],
        ':email' => $_POST["email"],
       ':password' => $hash,
        ':phone' => $_POST["phone"],
        ':authorization' => $_POST["authorization"],
        ':image' => $image
      )
    );
    if (!empty($result)) {
      echo 'Data Inserted';
    }
  }
  if ($_POST["operation"] == "Edit") {
    $image = '';
    if ($_FILES["image"]["name"] != '') {
      $image = upload_image();
    } else {
      $image = $_POST["hidden_user_image"];
    }
    $statement = $connection->prepare(
      "UPDATE users 
   SET name = :name, email = :email,password = :password , phone = :phone , authorization = :authorization, image = :image  
   WHERE id = :id
   "
    );
    $result = $statement->execute(
      array(
        ':name' => $_POST["name"],
        ':email' => $_POST["email"],
        ':password' => $_POST["password"],
        ':phone' => $_POST["phone"],
        ':authorization' => $_POST["authorization"],
        ':image' => $image,
        ':id' => $_POST["user_id"]
      )

    );
    if (!empty($result)) {
      echo 'Data Updated';
    }
  }
}

?>