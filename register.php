<?php
require_once "config.php";

$errors = [];
$success = '';

$name = '';
$email = '';
$phone = '';
$password = '';

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($con, trim($_POST['name']));
    $email = mysqli_real_escape_string($con, trim($_POST['email']));
    $phone = mysqli_real_escape_string($con, trim($_POST['phone']));
    $password = mysqli_real_escape_string($con, trim($_POST['password']));


    if ($name === '') {
        $errors[] = 'Name can not be blank';
    }

    if ($email === '') {
        $errors[] = 'Email can not be blank';
    }

    if ($phone === '') {
        $errors[] = 'Phone can not be blank';
    }

    if (!(is_string($phone) && strlen($phone) === 10)) {
        $errors[] = 'Invalid phone number, it must be length of 10 digits';
    }

    if ($phone === '') {
        $errors[] = 'Password can not be blank';
    }

    if (!(is_string($password) && strlen($password) > 4)) {
        $errors[] = 'Invalid password, it must be length morre than 4 digits';
    }

    $hashed_password = md5($password);

    $existed_user_sql =  "SELECT * FROM users WHERE email = '$email' OR phone='$phone'";
    $existed_user_result = mysqli_query($con, $existed_user_sql);

    if (mysqli_num_rows($existed_user_result) > 0) {
        $errors[] = 'User With email or phone already exist';
    } else {
        $sql = "INSERT INTO users (name, email, password, phone)"
            . " VALUES ('$name', '$email','$hashed_password', '$phone')";
        $insert_new_user = mysqli_query($con, $sql);

        if ($insert_new_user) {
            $success = 'User Inserted Successfully.';
        } else {
            $errors[] = 'Something went wrong';
        }
    }
}

if (empty($errors)) {
    $name = '';
    $email = '';
    $phone = '';
    $password = '';
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php include './components/errors.php' ?>
    <?php include './components/success.php' ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Register</h2>
                <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <!-- Name -->
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" class="form-control" id="name" name="name" value="<?= $name ?>" required>
                    </div>
                    <!-- Email -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= $email ?>" required>
                    </div>
                    <!-- Phone -->
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" class="form-control" id="phone" name="phone" value="<?= $phone ?>" required>
                    </div>
                    <!-- Password -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <!-- Submit Button -->
                    <input type="submit" class="btn btn-primary btn-block" name="register" value="Register">
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>