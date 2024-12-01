<?php
require_once('config.php');

check_session();

$errors = [];
$success = '';
$todos = [];

if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
    unset($_SESSION['errors']);
}

if (isset($_SESSION['success'])) {
    $success = $_SESSION['success'];
    unset($_SESSION['success']);
}

$logged_in_user = $_SESSION['id'];
$sql = "SELECT * FROM todos WHERE created_by='$logged_in_user'";
$result = mysqli_query($con, $sql);

if (mysqli_num_rows($result)) {
    $todos = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

if (isset($_POST['edit'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);

    if (!is_numeric($id)) {
        $errors[] = 'Invalid Todo Id';
    } else {
        $sql = "SELECT * FROM todos WHERE id='$id'";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result)) {
            $todo_details = mysqli_fetch_assoc($result);
        } else {
            $errors[] = 'Todo not found';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo List</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <?php include './components/errors.php' ?>
    <?php include './components/success.php' ?>

    <div class="container mt-5">
        <!-- Page Title -->
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="text-center mb-4">My Todo List</h2>
            <div class="d-flex gap-3">
                <?php if (count($todos) > 0): ?>
                    <a href="<?= BASE_URL ?>/handle_todo.php?deleteall=yes" class="btn btn-danger btn-sm">Delete all todo</a>
                <?php endif; ?>
                <?php if (isset($todo_details)): ?>
                    <a href="<?= BASE_URL ?>/" class="btn btn-danger btn-sm">Back To Add Todo</a>
                <?php endif; ?>
                <a href="<?= BASE_URL ?>/logout.php" class="btn btn-secondary btn-sm">Logout</a>
            </div>
        </div>

        <!-- Form to Add a New Todo -->
        <div class="mb-4">
            <form action="<?= BASE_URL ?>/handle_todo.php" method="POST">
                <div class="input-group">
                    <?php if (isset($todo_details)): ?>
                        <input type="hidden" name="id" value="<?= $todo_details['id'] ?>">
                    <?php endif; ?>
                    <input type="text" class="form-control" placeholder="Enter a new todo" name="todo" value="<?= (isset($todo_details) ? $todo_details['name'] : '') ?>" required autofocus>
                    <button class="btn btn-primary" type="submit" name="<?= (isset($todo_details) ? 'update' : 'save') ?>"><?= (isset($todo_details) ? 'Edit' : 'Add') ?> Todo</button>
                </div>
            </form>
        </div>


        <!-- Display Todos -->
        <ul class="list-group">

            <?php foreach ($todos as $todo) : ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= $todo['name'] ?>

                    <div class="d-flex gap-3">
                        <?php if (!isset($todo_details) || (isset($todo_details) && $todo_details['id'] !== $todo['id'])): ?>
                            <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" method="POST">
                                <input type="hidden" name="id" value="<?= $todo['id'] ?>">
                                <button type="submit" class="btn btn-primary btn-sm" name="edit">Edit</button>
                            </form>
                        <?php endif; ?>

                        <form action="<?= BASE_URL ?>/handle_todo.php" method="POST">
                            <input type="hidden" name="id" value="<?= $todo['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm" name="delete">Delete</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>