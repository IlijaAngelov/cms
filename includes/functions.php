<?php
include_once 'db.php';

//function createUser($username, $password, $firstname, $lastname, $email){
//    global $conn;
//    $createUser = "INSERT INTO users ('username', 'password', 'firstName', 'lastName', 'email', 'created_on') ";
//    $createUser.= " VALUES ($username, $password, $firstname, $lastname, $email, now()) ";
//}

function mismatchPassword($password, $repeat_password){
    if($password !== $repeat_password){
        return true;
    } else {
        return false;
    }
}

function existingUsername($conn, $username){
    global $conn;
    $findusername_query = "SELECT * FROM users WHERE username = ?;";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $findusername_query)){
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    if($row = mysqli_fetch_assoc($resultData)){
        return $row;
    } else {
        $result = false;
        return $result;
    }
    mysqli_stmt_close($stmt);
}

// do the same for the email
function existingEmail($conn, $email)
{
    global $conn;
    $query = "SELECT * FROM users WHERE user_email = ?;";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if(mysqli_fetch_assoc($result)){
        return true;
    } else {
        $result = false;
        return $result;
    }


//    $stmt = mysqli_stmt_init($conn);
//    if(!mysqli_stmt_prepare($stmt, $query)){
//        header("Location: ../signup.php?error=stmtfailed");
//        exit();
//    }
//    mysqli_stmt_bind_param($stmt, "s", $email);
//    mysqli_mysqli_stmt_execute($stmt);
//
//    $resultData = mysqli_stmt_get_result($stmt);
//    if(mysqli_fetch_assoc($resultData)){
//        return true;
//    } else {
//        $result = false;
//        return $result;
//    }


}

function emptyFields($username, $firstname, $lastname, $password, $email){
    if(empty($username) || empty($firstname) || empty($lastname) || empty($password) || empty($email) ){
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}


function createUser($conn, $username, $password, $firstname, $lastname, $email){
    global $conn;
//    $query = "INSERT INTO users (username, password, firstName, lastName, user_email) VALUES (?, ?, ?, ?, ?)";


    $query = "INSERT INTO users (username, password, firstName, lastName, user_email) VALUES (?,?,?,?,?)";
    $stmt = $conn->prepare($query);

//    $result = $stmt->get_result();
    if(!($stmt = $conn->prepare($query))){
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    $stmt->bind_param('sssss', $username, $password, $firstname, $lastname, $email);
    var_dump($stmt);
    $stmt->execute();

//    header("Location: ../signup.php?error=none");
//    exit();




//    $dt = now();
//    $stmt = mysqli_stmt_init($conn);
//    if(!mysqli_stmt_prepare($stmt, $query)){
//        header("Location: ../signup.php?error=stmtfailed");
//        exit();
//    }
//
//    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
//
//    mysqli_stmt_bind_param($stmt, "sssss", $username, $hashedPassword, $firstname, $lastname, $email);
//    mysqli_stmt_execute($stmt);
//    mysqli_stmt_close($stmt);
//    header("Location: ../signup.php?error=none");
//    exit();
}