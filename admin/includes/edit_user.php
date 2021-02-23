<?php

if(isset($_GET['user_id'])){
    $user_id = $_GET['user_id'];


$get_data_sql = "SELECT * FROM users WHERE user_id = $user_id";
$get_data_query = mysqli_query($conn, $get_data_sql);
while($data = mysqli_fetch_assoc($get_data_query)) {
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
        $sql11 = "SELECT * FROM users WHERE user_id = $user_id ";
        $image_query1 = mysqli_query($conn, $sql11);
        while ($row = mysqli_fetch_array($image_query1)) {
            $image = $row['user_image'];
        }
    }

    $update_sql = "UPDATE users SET username = '$username', password = '$password', ";
    $update_sql.= "user_role = '$user_role', firstName = '$firstName', lastName = '$lastName', user_email = '$user_email', user_image = '$image' ";
    $update_sql.= "WHERE user_id = '$user_id'" ;
    $update_query = mysqli_query($conn, $update_sql);

    okQuery($update_query);
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
            $select_query = mysqli_query($conn, $sql);
            okQuery($select_query);
            while($data = mysqli_fetch_assoc($select_query)) {
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
