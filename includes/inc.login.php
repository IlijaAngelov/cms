<?php
session_start();
include 'db.php';
include 'functions.php';

if(isset($_POST['submit'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(emptyFieldsLogin($username, $password) !== false){
        header("Location: ../login.php?error=emptyFields");
        exit();
    }

    loginUser($conn, $username, $password);

} else {
    header("Location: ../login.php");
    exit();
}
