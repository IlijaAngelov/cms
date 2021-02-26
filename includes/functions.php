<?php
include_once 'db.php';

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

    $query = "INSERT INTO users (username, password, firstName, lastName, user_email, created_on) VALUES (?,?,?,?,?, now())";
    $stmt = $conn->prepare($query);

    $stmt->bind_param('sssss', $username, $password, $firstname, $lastname, $email);
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $query)){
        header("Location: ../signup.php?error=stmtfailed");
        exit();
    }

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt, "sssss", $username, $hashedPassword, $firstname, $lastname, $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("Location: ../signup.php?error=none");
    exit();
}

// login functions
function emptyFieldsLogin($username,$password){
    if(empty($username) || empty($password) ){
        $result = true;
    } else {
        $result = false;
    }
    return $result;
}

function loginUser($conn, $username, $password){
    $existingUsername = existingUsername($conn, $username);
//    $existingEmail = existingEmail($conn, $email);

    if($existingUsername === false) {
        header("Location: ../signup.php?error=wrongLogin");
        exit();
    }
    if($existingEmail === false) {
        header("Location: ../signup.php?error=wrongLogin");
        exit();
    }

    $passwordHashed = $existingUsername["password"];
    $checkPassword = password_verify($password, $passwordHashed);

    if($checkPassword === false){
        header("Location: ../login.php?error=wrongLogin");
        exit();
    } else if ($checkPassword === true){
        session_start();

        $query = "SELECT * FROM users WHERE username = ?;";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        while($row = mysqli_fetch_array($result)){
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['password'] = $row['password'];
            $_SESSION['firstName'] = $row['firstName'];
            $_SESSION['lastName'] = $row['lastName'];
            $_SESSION['user_role'] = $row['user_role'];
        }

//        $_SESSION["user_id"] = $existingUsername["user_id"];
//        $_SESSION["username"] = $existingUsername["username"];
//        $_SESSION["user_role"] = $existingUsername["user_role"];
        header("Location: ../index.php");
        exit();
    }
}

