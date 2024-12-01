<?php
require_once "config.php";
$email_or_phone = '';

$success = '';
$errors = [];

if (isset($_POST['login'])) {
    // get user data from ui
    // validate or sanitize data
    // check user exsists
    // login
    // session

    $email_or_phone = mysqli_real_escape_string($con, $_POST['emailOrPhone']);
    $password = mysqli_real_escape_string($con, $_POST['password']);


    if (empty($email_or_phone)) {
        $errors[] = 'Please enter email or phone.';
    }


    if (preg_match('/^\d+$/i', $email_or_phone)) {

        if (strlen($email_or_phone) !== 10) {
            $errors[] = 'Phone number should be 10 digits';
        }
    } else {

        $pattern = '/^[^@]+@[^@]+\.[^@]+$/i';

        if (!preg_match($pattern, $email_or_phone)) {
            $errors[] = 'Invalid Email Address';
        }
    }

    if (empty($password)) {
        $errors[] = 'Please enter password';
    }

    if (strlen($password) <= 4) {
        $errors[] = 'Password length should be more than 4 digits';
    }

    $hashed_password = md5($password);
    $sql = "SELECT * FROM users WHERE (email='$email_or_phone' OR phone='$email_or_phone') AND password='$hashed_password'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) === 1) {
        $existed_user = mysqli_fetch_assoc($result);

        $_SESSION['is_login'] = true;
        $_SESSION['id'] = $existed_user['id'];
        $_SESSION['name'] = $existed_user['name'];
        $_SESSION['email'] = $existed_user['email'];
        $_SESSION['phone'] = $existed_user['phone'];

        if (empty($_SESSION)) {
            $errors[] = 'Internal Processing Error, Please try again later.';
        } else {
            header('location: index.php');
        }
    } else {
        $errors[] = 'User Not Exists';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <?php include './components/errors.php' ?>
    <?php include './components/success.php' ?>

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg" style="max-width: 400px; width: 100%;">
            <div class="card-body">
                <h4 class="card-title text-center mb-4">Login</h4>
                <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                    <div class="mb-3">
                        <label for="emailOrPhone" class="form-label">Email or Phone</label>
                        <input type="text" class="form-control" id="emailOrPhone" name="emailOrPhone" value="<?= $email_or_phone ?>" placeholder="Enter your email or phone number" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" name="login">Login</button>
                    </div>
                    <div class="text-center mt-3">
                        <a href="#">Forgot password?</a>
                    </div>
                    <div class="text-center mt-2">
                        <p>Don't have an account? <a href="<?= BASE_URL ?>/register.php">Sign up</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>