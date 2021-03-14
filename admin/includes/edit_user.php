<?php

if(isset($_GET['user_id'])){
    $user_id = $_GET['user_id'];

$sql = "SELECT * FROM users WHERE user_id = ? ";
$stmt = mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt, $sql)){
    echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
} else {
    mysqli_stmt_bind_param($stmt, 's', $user_id);
    mysqli_stmt_execute($stmt);
}
$resultData = mysqli_stmt_get_result($stmt);

while($data = mysqli_fetch_assoc($resultData)) {
    $user_id = $data['user_id'];
    $username = $data['username'];
    $password = $data['password'];
    $firstName = $data['firstName'];
    $lastName = $data['lastName'];
    $user_email = $data['user_email'];
    $user_image = $data['user_image'];
    $user_role = $data['user_role'];
}

}


if(isset($_POST['edit_user'])){

//    echo $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $user_email = $_POST['user_email'];
    $user_role = $_POST['user_role'];

    $image = $_FILES['image']['name'];
    $image_temp = $_FILES['image']['tmp_name'];

    move_uploaded_file($image_temp, "../images/$image");
    if(empty($image)) {

        $sql = "SELECT * FROM users WHERE user_id = ? ";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
        } else {
            mysqli_stmt_bind_param($stmt, 's', $user_id);
            mysqli_stmt_execute($stmt);
        }

        $resultData = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_array($resultData)) {
            $image = $row['user_image'];
        }
    }

    $update_sql = "UPDATE users SET username = ?, password = ?, user_role = ?, firstName = ?, lastName = ?, user_email = ?, user_image = ? WHERE user_id = ? ";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $update_sql)){
        echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
    } else {
        mysqli_stmt_bind_param($stmt, 'ssssssss', $username, $password, $user_role, $firstName, $lastName, $user_email, $image, $user_id);
        mysqli_stmt_execute($stmt);
    }
    echo "<p class='alert alert-success' role='alert'>User Updated. <a href='users.php' class='alert-link'>Edit More Users</a></p>";

}
?>


<form action="" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label for="username">Username</label>
        <input class="form-control" type="text" name="username" value="<?php echo $username; ?>">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input class="form-control" type="password" name="password" value="<?php echo $password; ?>">
    </div>
    <div class="form-group">
        <label for="user_role">Roles:</label>
        <select name="user_role" id="user_role">
        <?php
        $sql = "SELECT DISTINCT user_role FROM users ";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
        } else {
            mysqli_stmt_execute($stmt);
        }
        $resultData = mysqli_stmt_get_result($stmt);
        while($data = mysqli_fetch_assoc($resultData)) {
        $u_id = $data['user_id'];
        $user_role = $data['user_role'];
            echo "<option value=$user_role>$user_role</option>";
        }
        ?>
        </select>
    </div>
    <div class="form-group">
        <label for="firstName">First Name</label>
        <input class="form-control" type="text" name="firstName" value="<?php echo $firstName; ?>">
    </div>
    <div class="form-group">
        <label for="lastName">Last Name</label>
        <input class="form-control" type="text" name="lastName" value="<?php echo $lastName; ?>">
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input class="form-control" type="email" name="user_email" value="<?php echo $user_email; ?>">
    </div>
    <div class="form-group">
        <img width="100px" height="100px" src="../images/<?php echo $user_image; ?>" alt="">
        <input type="file" name="image">
    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="edit_user" value="Edit User">
    </div>
</form>
