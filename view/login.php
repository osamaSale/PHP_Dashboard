<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container" style="margin-top: 100px;">

        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div id="error" class="alert alert-danger" role="alert"></div>
                <div class="modal-header">
                    <h5 class="modal-title">Login</h5>
                </div>
                <div class="modal-body">
                    <div class='form-group'>
                        <label>Email</label>
                        <input type='email' name='email' id='email' class='form-control'>
                    </div>
                    <div class='form-group'>
                        <label>Password</label>
                        <input type='password' name='password' id='password' class='form-control'>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" name="save" class="btn btn-primary" value="login" id="login" style="width: 100%;">
                    </div>
                    </br>
                    <div class="form-group">
                        <p class="text-center">
                            Already have an account ? Register <a href="./register.php"> Here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            var element = document.getElementById("error");
            element.style.display = "none";
            $('#login').on('click', function() {
                let email = $('#email').val();
                let password = $('#password').val();
                let form_data = new FormData();
                form_data.append("email", email);
                form_data.append("password", password);
                if (email === "") {
                    $('div#error').html("Enter Your Email").show();
                } else
                if (password === "") {
                    $('div#error').html("Enter Your Password").show();
                } else {
                    $.ajax({
                        url: "http://localhost:8000/api/users/login.php",
                        type: "post",
                        data: form_data,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(data) {
                            console.log(data)
                            // if invalid Email
                            if (data.status === 202) {
                                $('div#error').html(data.massage).show();
                            }

                            // if invalid Password 
                            if (data.status === 203) {
                                $('div#error').html(data.massage).show();
                            }

                            // if successfully Login user
                            if (data.status === 200) {
                                localStorage.setItem("id", data.data.id)
                                localStorage.setItem("image", data.data.image)
                                localStorage.setItem("name", data.data.name)
                                localStorage.setItem("email", data.data.email)
                                localStorage.setItem("authorization", data.data.authorization)
                                location.href = "./home.php"
                            }
                            // if successfully Login admin
                            if (data.status === 201) {
                                localStorage.setItem("id", data.data.id)
                                localStorage.setItem("image", data.data.image)
                                localStorage.setItem("name", data.data.name)
                                localStorage.setItem("email", data.data.email)
                                localStorage.setItem("authorization", data.data.authorization)
                                location.href = "./index.php"
                            }

                        }
                    });
                }
            })
        })
    </script>
</body>

</html>