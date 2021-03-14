<?php session_start(); ?>
<?php include "db.php"; ?>

<?php
if(isset($_POST['login'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
    $date = date("Y-m-d H:i:s");

    $selectUsers = "SELECT * FROM users WHERE username = ? ";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $selectUsers)){
        echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
    } else {
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
    }
    $userData = mysqli_stmt_get_result($stmt);

    while($row = mysqli_fetch_array($userData)){
        $get_id = $row['user_id'];
        $get_username = $row['username'];
        $get_password = $row['password'];
        $get_firstName = $row['firstName'];
        $get_lastName = $row['lastName'];
        $get_role = $row['user_role'];
    }

    if($username === $get_username && $password !== $get_password){
        header("Location: ../index.php");
    } else if ($username == $get_username && $password == $get_password){
        $_SESSION['username'] = $get_username;
        $_SESSION['firstName'] = $get_firstName;
        $_SESSION['lastName'] = $get_lastName;
        $_SESSION['user_role'] = $get_role;

        header("Location: ../admin");
    } else {
        header("Location: ../index.php");
    }


}
