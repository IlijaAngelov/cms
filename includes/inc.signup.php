<?php
session_start();
include 'db.php';
include 'functions.php';

if(isset($_POST['submit'])) {

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$username = $_POST['username'];
$password = $_POST['password'];
$repeat_password = $_POST['repeat_password'];
$email = $_POST['email'];

if(emptyFields($username, $firstname, $lastname, $password, $email) !== false){
    header("Location: ../signup.php?error=emptyFields");
    exit();
}

if(mismatchPassword($password, $repeat_password) !== false){
    header("Location: ../signup.php?error=mismatchPasswords");
    exit();
}

if(existingEmail($conn, $email) !== false){
    header("Location: ../signup.php?error=emailExists");
    exit();
}

if(existingUsername($conn, $username) !== false){
    header("Location: ../signup.php?error=usernameExists");
    exit();
}

createUser($conn, $username, $password, $firstname, $lastname, $email);

} else {
    header("Location: /signup.php");
    exit();
}