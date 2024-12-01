<?php
session_start();
require_once "functions.php";

$host = 'localhost';
$user = 'root';
$password = '';
$db_name = 'todo';

$con = mysqli_connect($host, $user, $password, $db_name) or die('Connect not established.');

define("BASE_URL", $_SERVER['DOCUMENT_ROOT'] . '/to-do');
