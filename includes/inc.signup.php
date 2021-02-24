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

//$firstname = mysqli_real_escape_string($conn, $firstname);
//$lastname = mysqli_real_escape_string($conn, $lastname);
//$username = mysqli_real_escape_string($conn, $username);
//$password = mysqli_real_escape_string($conn, $repeat_password);
//$repeat_password = mysqli_real_escape_string($conn, $repeat_password);
//$email = mysqli_real_escape_string($conn, $email);

// try with !== false logic !!!
if(mismatchPassword($password, $repeat_password) !== false){
    header("Location: ../signup.php?mismatchPassword");
    exit();
//    echo "Passwords submitted does not match! . <br>";
//    echo "Please submit two identical passwords.";
//    header("Location: ../signup.php?mismatchPassword");
//    exit();
}

if(existingEmail($conn, $email) !== false){
    header("Location: ../signup.php?emailExists");
    exit();
//    echo $sql;
}

//if(existingUsername($conn, $username) !== false){
//    header("Location: ../signup.php?usernameExists");
//    exit();
//}
//
if(emptyFields($firstname, $lastname, $password, $repeat_password, $email) !== false){
    header("Location: ../signup.php?emptyFields");
    exit();
}
//
createUser($conn, $username, $password, $firstname, $lastname, $email);

} else {
    header("LOCATION: /signup.php");
    exit();
}