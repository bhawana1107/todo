<?php
function pr($array)
{
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

function prx($array)
{
    echo "<pre>";
    print_r($array);
    exit;
}

function check_session()
{
    if (!(isset($_SESSION['is_login']) && $_SESSION['is_login'] === true)) {
        header('location: login.php');
        exit;
    }
}
