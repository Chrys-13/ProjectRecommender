<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/login.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Login</title>
</head>

<body>

    <main>
        <form action="" method="post" , name="loginForm" , id="loginForm">
            <h1>Login</h1>
            <div>
                <label for="email">Email/Username:</label>
                <input type="text" name="email" id="email" required autocomplete="off">
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required autocomplete="off">
            </div>
            <section>
                <button type="submit">Login</button>
                <a href="register.php">Register</a>
            </section>
        </form>
    </main>
    <script>
        $(document).ready(function() {
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                var email = $('#email').val();
                var password = $('#password').val();

                $.ajax({
                    url: '../api/login.php',
                    type: 'POST',
                    data: {
                        login: email,
                        password: password
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success', response.message, 'success');
                            setTimeout(() => {
                                window.location.href = '../view/dashboard.php'; // Redirect to dashboard or desired page
                            }, 2000); // Redirect after 2 seconds
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