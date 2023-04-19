<?php
include("../../config/database.php");


// Error
function error422($massage)
{
    $data = [
        "status" => 422,
        "massage" => $massage
    ];
    header("HTTP/1.0 405 Internal Server Error");
    echo json_encode($data);
    exit();
}

function upload_image()
{
    if (isset($_FILES["image"])) {
        $extension = explode('.', $_FILES['image']['name']);
        $new_name = rand() . '.' . $extension[1];
        $destination = '../../image/users/' . $new_name;
        move_uploaded_file($_FILES['image']['tmp_name'], $destination);
        return $new_name;
    }
}



// Create User
function createUser($user)
{
    global $conn;
    $name = mysqli_real_escape_string($conn, $user['name']);
    $email = mysqli_real_escape_string($conn, $user['email']);
    $password = mysqli_real_escape_string($conn, $user['password']);
    $phone = mysqli_real_escape_string($conn, $user['phone']);
    $authorization = mysqli_real_escape_string($conn, $user['authorization']);
    $options = ["cost" => 15];
    $hash = password_hash($password, PASSWORD_BCRYPT, $options);
    $image = $_FILES['image']["name"];


    if (empty(trim($name))) {
        return error422('Enter your name');
    } elseif (empty(trim($email))) {
        return error422('Enter your email');
    } elseif (empty(trim($password))) {
        return error422('Enter your Password');
    } elseif (empty(trim($phone))) {
        return error422('Enter your Phone');
    } elseif (empty(trim($authorization))) {
        return error422('Enter your User and Admin');
    } elseif (empty(trim($image))) {
        return error422('Enter your Image');
    } else {
        $sql1 = "select * from users where email='$email'";
        $duplicate = mysqli_query($conn, $sql1);
        if (mysqli_num_rows($duplicate) > 0) {
            $data = [
                "status" => 201,
                "massage" => 'Email ID already exists'
            ];
            header("HTTP/1.0 201 Email ID already exists");
            return json_encode($data);
        } else {
            if ($_FILES["image"]["name"] != '') {
                $image = upload_image();
            }
            $query = "INSERT INTO users (name, email ,password ,image ,phone ,authorization) 
            VALUES('$name', '$email' ,'$hash', '$image' ,'$phone' , '$authorization')";
            $result = mysqli_query($conn,  $query);

            if ($result) {
                $data = [
                    "status" => 200,
                    "massage" => 'User create successfully'
                ];
                header("HTTP/1.0 200 User create successfully");
                return json_encode($data);
            } else {
                $data = [
                    "status" => 405,
                    "massage" => 'Internal Server Error'
                ];
                header("HTTP/1.0 405 Internal Server Error");
                return json_encode($data);
            }
        }
    }
}





// List Users All
function getUserList()
{
    global $conn;
    $query = "select * from users";
    $result = mysqli_query($conn,  $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $res = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $data = [
                "status" => 200,
                "massage" => 'Users List Fetched Successfully',
                "data" => $res
            ];
            header("HTTP/1.0 200 Users List Fetched Successfully");
            return json_encode($data);
        } else {
            $data = [
                "status" => 404,
                "massage" => 'No Users Found',
            ];
            header("HTTP/1.0 404 No Users Found");
            return json_encode($data);
        }
    } else {
        $data = [
            "status" => 500,
            "massage" => 'Internal Server Error'
        ];
        header("HTTP/1.0 405 Internal Server Error");
        echo json_encode($data);
    }
}


// single ID


function getIdUser($UserParams)
{
    global $conn;
    if ($UserParams['id'] == null) {
        return error422("Enter your user id");
    }
    $id = mysqli_real_escape_string($conn, $UserParams['id']);
    $query = "select * from users where id = '$id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $res = mysqli_fetch_assoc($result);
            $data = [
                "status" => 200,
                "massage" => 'User Fetched successfully',
                "data" => $res
            ];
            header("HTTP/1.0 200 No User Found");
            return json_encode($data);
        } else {
            $data = [
                "status" => 500,
                "massage" => 'No User Found'
            ];
            header("HTTP/1.0 405 No User Found");
            return json_encode($data);
        }
    } else {
        $data = [
            "status" => 500,
            "massage" => 'Internal Server Error'
        ];
        header("HTTP/1.0 405 Internal Server Error");
        echo json_encode($data);
    }
}


function editUser($user, $UserParams)
{
    global $conn;

    if (!isset($UserParams['id'])) {
        return error422('user id not found in URL');
    } elseif ($UserParams['id'] == null) {
        return error422('Enter the id user id');
    }

    $id = mysqli_real_escape_string($conn, $UserParams['id']);
    $name = mysqli_real_escape_string($conn, $user['name']);
    $email = mysqli_real_escape_string($conn, $user['email']);
    $password = mysqli_real_escape_string($conn, $user['password']);
    $phone = mysqli_real_escape_string($conn, $user['phone']);
    $authorization = mysqli_real_escape_string($conn, $user['authorization']);
    $options = ["cost" => 15];
    $hash = password_hash($password, PASSWORD_BCRYPT, $options);
    $image = $_FILES['image']["name"];


    if (empty(trim($name))) {
        return error422('Enter your name');
    } elseif (empty(trim($email))) {
        return error422('Enter your email');
    } elseif (empty(trim($password))) {
        return error422('Enter your Password');
    } elseif (empty(trim($phone))) {
        return error422('Enter your Phone');
    } elseif (empty(trim($authorization))) {
        return error422('Enter your User and Admin');
    } else {

        $id = mysqli_real_escape_string($conn, $UserParams['id']);
        $query = "select * from users where id = '$id' LIMIT 1";
        $result = mysqli_query($conn, $query);
        if ($result) {
            if (mysqli_num_rows($result) == 1) {
                $res = mysqli_fetch_assoc($result);
                if ($_FILES["image"]["name"] != '') {
                    unlink("../../image/users/" . $res['image']);
                    $image = upload_image();
                } else {
                    $image = $res['image'];
                }
                $query1 = "update users SET name='$name',email='$email',password='$hash',image='$image',phone='$phone',authorization='$authorization' where id ='$id'";
                $result = mysqli_query($conn,  $query1);
                if ($result) {

                    $data = [
                        "status" => 200,
                        "massage" => 'User update successfully'
                    ];
                    header("HTTP/1.0 200 User update successfully");
                    return json_encode($data);
                } else {
                    $data = [
                        "status" => 405,
                        "massage" => 'Internal Server Error'
                    ];
                    header("HTTP/1.0 405 Internal Server Error");
                    return json_encode($data);
                }
            }
        }
    }
}

function deleteUser($UserParams)
{
    global $conn;

    if (!isset($UserParams['id'])) {
        return error422('user id not found in URL');
    } elseif ($UserParams['id'] == null) {
        return error422('Enter the id user id');
    }
    $id = mysqli_real_escape_string($conn, $UserParams['id']);
    $query = "select * from users where id = '$id' LIMIT 1";
    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $res = mysqli_fetch_assoc($result);
            $query1 = "DELETE FROM users WHERE id = '$id' LIMIT 1";
            $result = mysqli_query($conn, $query1);
            if ($result) {
                unlink("../../image/users/" . $res['image']);
                $data = [
                    "status" => 200,
                    "massage" => 'User Delete successfully'
                ];
                header("HTTP/1.0 200 User Delete successfully");
                return json_encode($data);
            } else {

                $data = [
                    "status" => 400,
                    "massage" => 'Internal Server Error'
                ];
                header("HTTP/1.0 400 Internal Server Error");
                return json_encode($data);
            }
        } else {
            $data = [
                "status" => 500,
                "massage" => 'No User Found'
            ];
            header("HTTP/1.0 405 No User Found");
            return json_encode($data);
        }
    } else {
        $data = [
            "status" => 500,
            "massage" => 'Internal Server Error'
        ];
        header("HTTP/1.0 405 Internal Server Error");
        echo json_encode($data);
    }
}




function search($UserParams)
{
    global $conn;
    if ($UserParams['name'] == null) {
        return getUserList();
    }
    $name = mysqli_real_escape_string($conn, $UserParams['name']);
    $query = "SELECT * FROM users WHERE name LIKE '%" . $name . "%'";
    $result = mysqli_query($conn, $query);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $res = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $data = [
                "status" => 200,
                "massage" => 'Users List Fetched Successfully',
                "data" => $res
            ];
            header("HTTP/1.0 200 Users List Fetched Successfully");
            return json_encode($data);
        } else {
            $data = [
                "status" => 201,
                "massage" => 'No Users Found',
            ];
            header("HTTP/1.0 201 No Users Found");
            return json_encode($data);
        }
    } else {
        $data = [
            "status" => 500,
            "massage" => 'Internal Server Error'
        ];
        header("HTTP/1.0 405 Internal Server Error");
        echo json_encode($data);
    }
}
