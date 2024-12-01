<?php
require_once('config.php');


// Add new todo 

if (isset($_POST['save'])) {

    $todo = mysqli_real_escape_string($con, $_POST['todo']);

    if (empty($todo)) {
        $_SESSION['errors'][] = 'Please Enter Todo';
    } else {

        $created_at = date('Y-m-d H:i:s');
        $created_by = $_SESSION['id'];
        $sql = "INSERT INTO todos (name, created_by, created_at)"
            . " VALUES('$todo' , '$created_by' , '$created_at')";

        $insert = mysqli_query($con, $sql);

        if ($insert) {
            $_SESSION['success'] = 'Todo inserted successfully';
        } else {
            $_SESSION['errors'][] = 'Internal processing error';
        }
    }

    header('location: index.php');
}


// Delete todo 

if (isset($_POST['delete'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);

    if (!is_numeric($id)) {
        $_SESSION['errors'][] = 'Invalid Todo Id';
    } else {

        $sql = "SELECT * FROM todos WHERE id='$id'";
        $result = mysqli_query($con, $sql);

        if (mysqli_num_rows($result)) {

            $sql = "DELETE FROM todos WHERE id='$id'";
            $delete = mysqli_query($con, $sql);

            if ($delete) {
                $_SESSION['success'] = 'Todo deleted successfully';
            } else {
                $_SESSION['errors'][] = 'something went wrong while deleting todo.';
            }
        } else {
            $_SESSION['errors'][] = 'Todo not found';
        }
    }


    header('location: index.php');
}


// update todo

if (isset($_POST['update'])) {
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $name = mysqli_real_escape_string($con, $_POST['todo']);

    if (!is_numeric($id)) {
        $_SESSION['errors'][] = 'Invalid Todo Id';
    } else {

        if (!empty($name)) {
            $sql = "SELECT * FROM todos WHERE id='$id'";
            $result = mysqli_query($con, $sql);

            if (mysqli_num_rows($result)) {

                $sql = "UPDATE todos SET name='$name' WHERE id='$id'";
                $update = mysqli_query($con, $sql);

                if ($update) {
                    $_SESSION['success'] = 'Todo updated successfully';
                } else {
                    $_SESSION['errors'][] = 'something went wrong while updating todo.';
                }
            } else {
                $_SESSION['errors'][] = 'Todo not found';
            }
        } else {
            $_SESSION['errors'][] = 'Invalid Todo Name';
        }
    }

    header('location: index.php');
}

if(isset($_GET['deleteall'])){
    $logged_in_user = $_SESSION['id'];
    $sql = "DELETE FROM todos WHERE created_by = '$logged_in_user'";
    $delete = mysqli_query($con , $sql);

    if($delete){
        $_SESSION['succcess']= 'Todos Deleted successfully';
    }else{
        $_SESSION['errors'][] = 'something went wrong while deleting todos';
    }
    header('location: index.php');
}