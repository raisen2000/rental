<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .forgot-password-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>

    <div class="forgot-password-container">
        <h3 class="text-center">Forgot Password</h3>
        <p class="text-center">Enter your email address to receive a password reset link.</p>
        <form id="forgot-password-form" method="POST" action="send_reset_link.php">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Send Reset Link</button>
        </form>
        <div class="text-center mt-3">
            <a href="login.php">Back to Login</a>
        </div>
    </div>

    <script src="path/to/jquery.js"></script>
    <script src="path/to/bootstrap.js"></script>
    <script>
        // Optional: Add jQuery AJAX to handle form submission without reloading
        $('#forgot-password-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'send_reset_link.php',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    alert(response); // Show success message
                },
                error: function() {
                    alert('There was an error. Please try again.');
                }
            });
        });
    </script>

</body>

</html>