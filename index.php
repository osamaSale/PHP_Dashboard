<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark text-white">
        <?php include("./navbar.php") ?>
    </nav>
    <div class="container box">
        <br>
        <div class="table-responsive">
            <div align="right">
                <a href="#addEmployeeModal" class="btn btn-success" data-toggle="modal">
                    <i class="material-icons">&#xE147;</i><span>Add New User</span></a><br /><br>
                <input type="text" name="search" id="search" placeholder="Search" class="form-control" />
            </div>
            <br /><br />
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th width="10%">ID</th>
                        <th width="10%">Name</th>
                        <th width="10%">Email</th>
                        <th width="10%">Image</th>
                        <th width="10%">Phone</th>
                        <th width="10%">Authorization</th>
                        <th width="10%">View</th>
                        <th width="10%">Delete</th>
                        <th width="10%">Edit</th>
                    </tr>
                </thead>
                <tbody id="employee_data">
                </tbody>
            </table>
            <p class="loading">Loading Data</p>
        </div>
    </div>
    <?php include("./Form/add.php") ?>
    <?php include("./Form/delete.php") ?>
    <?php include("./Form/view.php") ?>
    <?php include("./Form/edit.php") ?>
    <script>
        let result = {};
        $(document).ready(function () {
            employeeList();
            let result = {};
        });
        function employeeList() {
            $.ajax({
                type: 'get',
                url: "./server/viewData.php",
                success: function (data) {
                    var response = JSON.parse(data);
                    var tr = '';
                    for (var i = 0; i < response.length; i++) {
                        var id = response[i].id;
                        var name = response[i].name;
                        var email = response[i].email;
                        var image = response[i].image;
                        var phone = response[i].phone;
                        var authorization = response[i].authorization;
                        tr += '<tr>';
                        tr += '<td>' + id + '</td>';
                        tr += '<td>' + name + '</td>';
                        tr += '<td>' + email + '</td>';
                        tr += '<td><img src="../image/users/' + image + '" class="img-thumbnail" width="50" height="25" /> </td>';
                        tr += '<td>' + phone + '</td>';
                        tr += '<td>' + authorization + '</td>';
                        tr += '<td><a href="#viewEmployeeModal" class="btn btn-success btn-sm" data-toggle="modal" onclick=viewEmployee("' + id + '")>View</a></td>';
                        tr += '<td><a href="#" class="btn btn-danger  btn-sm" data-toggle="modal" onclick=deleteEmployee("' + id + '","' + image + '")>Delete</a></td>'
                        tr += '<td><a href="#editEmployeeModal" class="btn btn-primary  btn-sm" data-toggle="modal" onclick=viewEmployee("' + id + '")>Edit</a></td>'
                        tr += '</tr>';
                    }
                    $('.loading').hide();
                    $('#employee_data').html(tr);
                }
            });
        }
        function addEmployee() {
            var name = $('.add_epmployee #name_input').val();
            var email = $('.add_epmployee #email_input').val();
            var password = $('.add_epmployee #password_input').val();
            var phone = $('.add_epmployee #phone_input').val();
            var authorization = $('.add_epmployee #authorization_input').val();
            var image = $('.add_epmployee #image_input').prop('files')[0];
            let form_data = new FormData();
            form_data.append("name", name);
            form_data.append("email", email);
            form_data.append("password", password);
            form_data.append("image", image);
            form_data.append("phone", phone);
            form_data.append("authorization", authorization);
            $.ajax({
                url: "./server/insert.php",
                type: 'post',
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {

                    if (data === "Email ID already exists") {
                        alert(data)
                    } else {
                        var response = JSON.parse(data);
                        employeeList();
                        alert(response.message);
                        $('#addEmployeeModal').modal('hide');
                    }
                }

            })
        }

        // Edit User
        function editEmployee() {
            var id = $('.edit_employee #id').val();
            var name = $('.edit_employee #name').val();
            var email = $('.edit_employee #email').val();
            var password = $('.edit_employee #password').val();
            var phone = $('.edit_employee #phone').val();
            var authorization = $('.edit_employee #authorization').val();
            let image = $('.edit_employee #image').prop('files')[0];
            let form_data = new FormData();
            form_data.append("id", id);
            form_data.append("name", name);
            form_data.append("email", email);
            form_data.append("password", password);
            form_data.append("image", image);
            form_data.append("phone", phone);
            form_data.append("authorization", authorization);
            $.ajax({
                url: "./server/edit.php",
                type: 'post',
                data: form_data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (data) {
                    var response = JSON.parse(data);
                    $('#editEmployeeModal').modal('hide');
                    employeeList();
                    alert(response.message);
                }

            })

        }

        // View User
        function viewEmployee(id = 2) {
            $.ajax({
                type: 'get',
                data: {
                    id: id
                },
                url: "./server/view.php",
                success: function (data) {
                    var response = JSON.parse(data);
                    $('.edit_employee #id').val(response.id);
                    $('.edit_employee #name').val(response.name);
                    $('.edit_employee #email').val(response.email);
                    $('.edit_employee #password').val(response.password);
                    $('.edit_employee #phone').val(response.phone);
                    $('.edit_employee #authorization').val(response.authorization);
                    $('.edit_employee #user_uploaded_image').html('<img src="../image/users/' + response.image + '" class="img-thumbnail" width="50" height="35" /> ');




                    $('.view_employee #name_input1').val(response.name);
                    $('.view_employee #email_input1').val(response.email);
                    $('.view_employee #password_input1').val(response.password);
                    $('.view_employee #phone_input1').val(response.phone);
                    $('.view_employee #authorization_input1').val(response.authorization);
                    $('.view_employee #image_input1').val(response.image);
                    $('.view_employee #user_uploaded_image').html('<img src="../image/users/' + response.image + '" class="img-thumbnail" width="50" height="35" /> ');


                }
            })
        }

        // Delete User

        function deleteEmployee(id, image) {
            if (confirm("Are you sure you want to delete this?")) {
                $.ajax({
                    url: "./server/delete.php",
                    method: "GET",
                    data: { id: id, image, image },
                    success: function (data) {
                        var response = JSON.parse(data);
                        employeeList();
                        // alert(response.message);
                    }
                });
            }
            else {
                return false;
            }
        };


        $('#search').keyup(function () {
            var query = $(this).val();
            if (query != '') {
                $.ajax({
                    url: "./server/search.php",
                    method: "POST",
                    data: { query: query },
                    success: function (data) {
                        $('#employee_data').fadeIn();
                        $('#employee_data').html(data);
                    }
                });
            } else {
                return employeeList();
            }
        });

    </script>
</body>

</html>