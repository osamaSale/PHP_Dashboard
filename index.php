<?php
include("./server/connection.php");
$sql = "select * from users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>PHP Dashboard</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</head>

<body>
    <nav class="navbar navbar-dark bg-dark text-white">
        <div class="container-fluid">
            <a class="navbar-brand">Dashboard</a>
            <div class="d-flex">
                <a class="btn btn-primary" href="./logout.php">Log out</a>
            </div>
        </div>
    </nav>
    <div class="container-fluid">
        <div class="row  pt-3 pb-3">
            <div class="col">
                <p class='text-left'><a href='#' class='btn btn-success' id='add_record'>Add User</a></p>
                <table class='table table table-dark table-sm'>
                    <thead>
                        <tr uid='{$row["id"]}'>
                            <th scope="col">ID</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Password</th>
                            <th scope="col">Image</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Authorization</th>
                            <th scope="col">View</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Delete</th>
                        </tr>
                    </thead>
                    <tbody id='tbody'>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "
                        <tr uid='{$row["id"]}'>
                          <td>{$row["id"]}</td>
                          <td>{$row["name"]}</td>
                          <td>{$row["email"]}</td>
                          <td style='width: 10px;'>{$row["password"]}</td>
                          <td ><img src='./image/{$row["image"]}' style='height: 30px; width: 50px;' alt='{$row['image']}'></img></td>
                          <td>{$row["phone"]}</td>
                          <td>{$row["authorization"]}</td>
                          <td><button type='button' value='{$row['id']}' class='viewStudentBtn btn btn-info btn-sm'>View</button></td>
                          <td><a href='#' class='btn btn-primary  btn-sm edit'>Edit</a></td>
                          <td><a href='#' class='btn btn-danger  btn-sm delete '>Delete</a></td>
                        </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>


    </div>
    <?php include("./From/insert.php") ?>
    <?php include("./From/edit.php") ?>
    <script>
        $(document).ready(function () {
            var current_row = null;
            $("#add_record").click(function () {
                $("#addUser").modal('show');
            });

            $("#insert").click(function () {
                var name = $('#name').val();
                var email = $('#email').val();
                var password = $('#password').val();
                var phone = $('#phone').val();
                var image = $('#image').prop('files')[0];
                var authorization = $('#authorization').val();
                var form_data = new FormData();
                form_data.append("name", name);
                form_data.append("email", email);
                form_data.append("password", password);
                form_data.append("image", image);
                form_data.append("phone", phone);
                form_data.append("authorization", authorization);
                if (name !== "" && email !== "" && password !== "" && phone !== "") {
                    $.ajax({
                        url: "./server/insert.php",
                        type: "POST",
                        dataType: 'script',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function (data) {
                            if (data === "successfully created") {
                                $('#addUser').modal().hide();
                                alert('Data updated successfully !');
                                location.reload();
                            }
                            if (data === "Email ID already exists") {
                                alert(data)
                            }
                        }
                    });
                } else {
                    alert("cannot be blank")
                }
            });

            $("body").on("click", ".edit", function (event) {
                event.preventDefault();
                current_row = $(this).closest("tr");
                $("#editUser").modal();
                var id = $(this).closest("tr").attr("id_u");
                var id = $(this).closest("tr").find("td:eq(0)").text();
                var name = $(this).closest("tr").find("td:eq(1)").text();
                var email = $(this).closest("tr").find("td:eq(2)").text();
                var password = $(this).closest("tr").find("td:eq(3)").text();
                var image = $(this).closest("tr").find("td:eq(4)").text();
                var phone = $(this).closest("tr").find("td:eq(5)").text();
                var authorization = $(this).closest("tr").find("td:eq(6)").text();
                $("#id").val(id);
                $("#name1").val(name);
                $("#email1").val(email);
                $("#password1").val(password);
                $("#phone1").val(phone);
                $("#image1").val(image);
                $("#authorization1").val(authorization);
            });
            $("#update").click(function () {
                var id = $('#id').val();
                var name = $('#name1').val();
                var email = $('#email1').val();
                var password = $('#password1').val();
                var phone = $('#phone1').val();
                var image = $('#image1').prop('files')[0];
                var authorization = $('#authorization1').val();
                var form_data = new FormData();
                form_data.append("id", id);
                form_data.append("name1", name);
                form_data.append("email1", email);
                form_data.append("image1", image);
                form_data.append("phone1", phone);
                form_data.append("authorization1", authorization);
                console.log(image)
                if (image !== "") {
                    $.ajax({
                        url: "./server/edit.php",
                        type: "POST",
                        dataType: 'script',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function (data) {
                            if (data === "Successfully") {
                                alert("Successfully")
                                $('#editUser').modal().hide();
                                location.reload();
                            } else {
                                alert("cannot")
                                $('#editUser').modal().hide();
                                location.reload();
                            }

                        }
                    });
                } else {
                    alert("cannot be blank")
                }
            });

            // Delete
            $(document).on('click', '.delete', function () {
                let id = $(this).closest("tr").attr("uid");
                console.log(id)
                var cls = $(this);
                if (confirm("Are you sure you want to delete this?")) {
                    $.ajax({
                        url: "./server/delete.php",
                        method: "POST",
                        data: {
                            uid: id,
                        },
                        success: function (res) {
                            console.log(res)
                            if (res) {
                                $(cls).closest("tr").remove();
                            } else {
                                alert("Failed TryAgain");
                                $(cls).text("Try Again");
                            }
                        }
                    });
                } else {
                    return false;
                }
            });
        })
    </script>
</body>

</html>