<html>

<head>
    <title>PHP_Dashboard</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f1f1f1;
        }

        .box {
            width: 1270px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 25px;
        }
    </style>
</head>

<body>
    <div class="container box">
        <h1 align="center">PHP Ajax CRUD </h1>
        <br />
        <div class="table-responsive">
            <br />
            <div align="right">
                <button type="button" id="add_button" data-toggle="modal" data-target="#userModal"
                    class="btn btn-info btn-lg">Add</button>
            </div>
            <br /><br />
            <table id="user_data" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th width="35%">ID</th>
                        <th width="10%">Name</th>
                        <th width="10%">Email</th>
                        <th width="20%">Password</th>
                        <th width="30%">Phone</th>
                        <th width="10%">Authorization</th>
                        <th width="35%">Image</th>
                        <th width="10%">Edit</th>
                        <th width="10%">Delete</th>
                        <th scope="col">View</th>
                    </tr>
                </thead>
            </table>

        </div>
    </div>
</body>

</html>
<?php include('./Form/view.php') ?>
<div id="userModal" class="modal fade">
    <div class="modal-dialog">
        <form method="post" id="user_form" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add User</h4>
                </div>
                <div class="modal-body">
                    <label>Name</label>
                    <input type="text" name="name" id="name" class="form-control" />
                    <br />
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control" />
                    <br />
                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control" />
                    <br />
                    <label>Phone</label>
                    <input type="tel" name="phone" id="phone" class="form-control" />
                    <br />
                    <label>Authorization</label>
                    <select class='form-control' name='authorization' id='authorization'>
                        <option selected>Open this select menu</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                    <br />
                    <label>Select User Image</label>
                    <input type="file" name="image" id="image" />
                    <span id="user_uploaded_image"></span>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="user_id" id="user_id" />
                    <input type="hidden" name="operation" id="operation" />
                    <input type="submit" name="action" id="action" class="btn btn-success" value="Add" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" language="javascript">
    $(document).ready(function () {
        $('#add_button').click(function () {
            $('#user_form')[0].reset();
            $('.modal-title').text("Add User");
            $('#action').val("Add");
            $('#operation').val("Add");
            $('#user_uploaded_image').html('');
        });

        var dataTable = $('#user_data').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                url: "./server/fetch.php",
                type: "POST"
            },
            "columnDefs": [
                {
                    "targets": [0, 3, 4],
                    "orderable": false,
                },
            ],

        });

        $(document).on('submit', '#user_form', function (event) {
            event.preventDefault();
            var name = $('#name').val();
            var email = $('#email').val();
            var password = $('#password').val();
            var phone = $('#phone').val();
            var authorization = $('#authorization').val();
            var extension = $('#image').val().split('.').pop().toLowerCase();
            if (extension != '') {
                if (jQuery.inArray(extension, ['gif', 'png', 'jpg', 'jpeg ,jfif']) == -1) {
                    alert("Invalid Image File");
                    $('#image').val('');
                    return false;
                }
            }
            if (name != '' && email != '') {
                $.ajax({
                    url: "./server/insert.php",
                    method: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        alert(data);
                        $('#user_form')[0].reset();
                        $('#userModal').modal('hide');
                        dataTable.ajax.reload();
                    }
                });
            }
            else {
                alert("Both Fields are Required");
            }
        });

        $(document).on('click', '.update', function () {
            var user_id = $(this).attr("id");
            $.ajax({
                url: "./server/fetch_single.php",
                method: "POST",
                data: { user_id: user_id },
                dataType: "json",
                success: function (data) {
                    $('#userModal').modal('show');
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#password').val(data.password);
                    $('#phone').val(data.phone);
                    $('#authorization').val(data.authorization);
                    $('.modal-title').text("Edit User");
                    $('#user_id').val(user_id);
                    $('#user_uploaded_image').html(data.image);
                    $('#action').val("Edit");
                    $('#operation').val("Edit");
                }
            })
        });

        $(document).on('click', '.delete', function () {

            var user_id = $(this).attr("id");
            if (confirm("Are you sure you want to delete this?")) {
                $.ajax({
                    url: "./server/delete.php",
                    method: "POST",
                    data: { user_id: user_id },
                    success: function (data) {
                        alert(data);
                        dataTable.ajax.reload();
                    }
                });
            }
            else {
                return false;
            }
        });



    });

    $("#view").click(function () {
        $("#viewModal").modal('show');
    })

</script>