<?php
include("../../config/database.php");
include("../cloudinary/cloudinary.php");



// Error
function error422($massage)
{
    $data = [
        "status" => 405,
        "massage" => $massage
    ];
    header("HTTP/1.0 405 Internal Server Error");
    echo json_encode($data);
    exit();
}

function upload_image()
{
    if (isset($_FILES["fileImage"])) {
        $extension = explode('.', $_FILES['fileImage']['name']);
        $new_name = rand() . '.' . $extension[1];
        $destination = '../../image/' . $new_name;
        move_uploaded_file($_FILES['fileImage']['tmp_name'], $destination);
        return $new_name;
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
                "status" => 205,
                "massage" => 'No Users Found',
            ];
            header("HTTP/1.0 205 No Users Found");
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


// Create User
function createUser($user)
{
    global $conn;
    global $cloudinary;
    $name = mysqli_real_escape_string($conn, $user['name']);
    $email = mysqli_real_escape_string($conn, $user['email']);
    $password = mysqli_real_escape_string($conn, $user['password']);
    $phone = mysqli_real_escape_string($conn, $user['phone']);
    $authorization = mysqli_real_escape_string($conn, $user['authorization']);
    $options = ["cost" => 15];
    $hash = password_hash($password, PASSWORD_BCRYPT, $options);
    $fileImage = $_FILES['fileImage']["name"];
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
    } elseif (empty(trim($fileImage))) {
        return error422('Enter your fileImage');
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
            if ($_FILES["fileImage"]["name"] != '') {
                $fileImage = upload_image();
                $data =  $cloudinary->uploadApi()->upload("../../image/" . $fileImage);
                $image = $data['url'];
                $cloudinary_id = $data['public_id'];
            }
            $query = "INSERT INTO users (name, email ,password ,image, fileImage ,phone ,authorization ,cloudinary_id) 
            VALUES('$name', '$email' ,'$hash', '$image','$fileImage' ,'$phone' ,'$authorization' , '$cloudinary_id')";
            $result = mysqli_query($conn,  $query);

            if ($result) {
                $data = [
                    "status" => 200,
                    "massage" => 'User create successfully',
                    'data' => $data
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



// edit User
function editUser($user, $UserParams)
{
    global $conn;
    global $cloudinary;
    if (!isset($UserParams['id'])) {
        return error422('user id not found in URL');
    } elseif ($UserParams['id'] == null) {
        return error422('Enter the id user id');
    }

    $id = mysqli_real_escape_string($conn, $UserParams['id']);
    $name = mysqli_real_escape_string($conn, $user['name']);
    $email = mysqli_real_escape_string($conn, $user['email']);
    $phone = mysqli_real_escape_string($conn, $user['phone']);
    $authorization = mysqli_real_escape_string($conn, $user['authorization']);
    $fileImage = $_FILES['fileImage']["name"];


    if (empty(trim($name))) {
        return error422('Enter your name');
    } elseif (empty(trim($email))) {
        return error422('Enter your email');
    }  elseif (empty(trim($phone))) {
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
                if ($_FILES["fileImage"]["name"] != '') {
                    unlink("../../image/" . $res['fileImage']);
                    $del =  $cloudinary->uploadApi()->destroy($res['cloudinary_id']);
                    $fileImage = upload_image();
                    $data =  $cloudinary->uploadApi()->upload("../../image/" . $fileImage);
                    $image = $data['url'];
                    $cloudinary_id = $data['public_id'];
                } else {
                    $fileImage = $res['fileImage'];
                    $image = $res['image'];
                    $cloudinary_id = $res['cloudinary_id'];
                }
                $query1 = "update users SET name='$name',email='$email',image ='$image',fileImage='$fileImage',phone='$phone',authorization='$authorization', cloudinary_id = '$cloudinary_id' where id ='$id'";
                $result1 = mysqli_query($conn,  $query1);
                if ($result1) {

                    $data = [
                        "status" => 200,
                        "massage" => 'User update successfully',
                        'del' => $del
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


// delete User
function deleteUser($UserParams)
{
    global $conn;
    global $cloudinary;
    if ($UserParams['id'] == null) {
        return error422("Enter your user id");
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
                unlink("../../image/" . $res['fileImage']);
                $deleteImage =  $cloudinary->uploadApi()->destroy($res['cloudinary_id']);
                $data = [
                    "status" => 200,
                    "massage" => 'User Delete successfully',
                    "deleteImage" => $deleteImage
                ];
                header("HTTP/1.0 200 User Delete successfully");
                return json_encode($data);
            }
        } else {
            $data = [
                "status" => 405,
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



//  search Name
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

function login($user)
{
    global $conn;
    $email = mysqli_real_escape_string($conn, $user['email']);
    $password = mysqli_real_escape_string($conn, $user['password']);
    $query = "select * from users where email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (empty(trim($email))) {
        return error422('Enter your email');
    } elseif (empty(trim($password))) {
        return error422('Enter your Password');
    }
    $sql = "SELECT * FROM users WHERE email='$email'  ";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {
        $res = mysqli_fetch_assoc($result);
        $hash = $res['password'];
        if (password_verify($password, $hash)) {
            if ($res['authorization'] === 'user') {
                $data = [
                    "status" => 200,
                    "massage" => 'successfully login user',
                    "data" => $res
                ];
                header("HTTP/1.0 200 successfully login user");
                echo json_encode($data);
            } else if ($res['authorization'] === 'admin') {
                $data = [
                    "status" => 201,
                    "massage" => 'successfully login admin',
                    "data" => $res
                ];
                header("HTTP/1.0 201 successfully login admin");
                echo json_encode($data);
            }
        } else {
            $data = [
                "status" => 203,
                "massage" => 'Invalid Password'
            ];
            header("HTTP/1.0 203 Invalid password");
            echo json_encode($data);
        }
    } else {
        $data = [
            "status" => 202,
            "massage" => 'Invalid Email'
        ];
        header("HTTP/1.0 202 Invalid password");
        echo json_encode($data);
    }
}
