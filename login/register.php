<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" integrity="sha512-xh6O/CkQoPOWDdYTDqeRdPCVd1SpvCA9XXcUnZS2FmJNp1coAFzvtCN9BmamE+4aHK8yyUHUSCcJHgXloTyT2A==" crossorigin="anonymous" referrerpolicy="no-referrer">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <style type="text/css">
        .login-form {
            width: 390px;
            margin: 50px auto;
        }

        .login-form form {
            margin-bottom: 15px;
            background: #f7f7f7;
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            padding: 30px;
        }

        .login-form h2 {
            margin: 0 0 15px;
        }

        .form-control,
        .btn {
            min-height: 38px;
            border-radius: 2px;
        }

        .btn {
            font-size: 15px;
            font-weight: bold;
        }
    </style>
</head>

<body style = "background-color: honeydew;">
    <div class="login-form">
        <h1>Register</h1>
        <form action="" method="post" autocomplete="off" , id="registerForm">

            <div class="form-group">
                <label for="username"><i class="fas fa-eye"></i></label>
                <input type="text" name="username" placeholder="Username" id="username" required>
            </div>


            <div class="form-group">
                <label for="email"><i class="fas fa-envelope"></i></label>
                <input type="email" name="email" placeholder="Email" id="email" required>
            </div>

            <div class="form-group">
                <label for="role"><i class="fas fa-lock"></i></label>
                <input type="password" name="password" placeholder="Password" id="password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Password must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters">
            </div>
            <div class="form-group">
                <label for="confirm_password"><i class="fas fa-lock"></i></label>
                <input type="password" name="confirm_password" placeholder="Confirm Password" id="confirm_password" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-block" id="submit" style="background-color: #57923d; color: white;">Register</button>
            </div>

            <p class="text-center"><a href="login.php">Already have an account? Login</a></p>

        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();

                var email = $('#email').val();
                var password = $('#password').val();
                var confirmPassword = $('#confirm_password').val();

                if (!password.match(/(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}/)) {
                    Swal.fire('Error', 'Password must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters', 'error');
                    return
                }

                if (password !== confirmPassword) {
                    Swal.fire('Error', 'Passwords do not match', 'error');
                    return
                }

                $.ajax({
                    url: '../api/register.php',
                    type: 'POST',
                    data: {
                        email: email,
                        password: password,
                        username: $('#username').val()
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success', response.message, 'success');
                            setTimeout(() => {
                                window.location.href = 'login.php';
                            }, 2000);

                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'An error occurred while processing your request.', 'error');
                    }
                });
            });
        });
    </script>
</body>

</html>