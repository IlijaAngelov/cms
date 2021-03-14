<?php include "includes/admin_header.php" ?>
<?php
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $sql = "SELECT * FROM users WHERE username = ? ";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
    } else {
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
    }
    $userData = mysqli_stmt_get_result($stmt);

    while($row = mysqli_fetch_array($userData)){
        $user_id = $row['user_id'];
        $username = $row['username'];
        $password = $row['password'];
        $firstName = $row['firstName'];
        $lastName = $row['lastName'];
        $user_email = $row['user_email'];
        $user_role = $row['user_role'];
        $user_image = $row['user_image'];
    }
}


if(isset($_POST['edit_user'])){
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

        $userSql = "SELECT * FROM users WHERE user_id = ? ";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $userSql)){
            echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
        } else {
            mysqli_stmt_bind_param($stmt, 's', $user_id);
            mysqli_stmt_execute($stmt);
        }
        $userImage = mysqli_stmt_get_result($stmt);

        while ($row = mysqli_fetch_array($userImage)) {
            $image = $row['user_image'];
        }
    }

    $updateSql = "UPDATE users SET username = ?, password = ?, user_role = ?, firstName = ?, lastName = ?, user_email = ?, user_image = ? WHERE user_id = ? ";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $updateSql)){
        echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
    } else {
        mysqli_stmt_bind_param($stmt, 'ssssssss', $username, $password, $user_role, $firstName, $lastName, $user_email, $image, $user_id);
        mysqli_stmt_execute($stmt);
    }
    $updateUser = mysqli_stmt_get_result($stmt);
//    echo "<p style='text-align: center'>User updated successfully!</p>";

}

?>

<div id="wrapper">

    <!-- Navigation -->
    <?php include "includes/admin_navigation.php" ?>
    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header text-center">
                        Hello <?php echo $lastName; ?>
                    </h1>
                </div>
            </div>
            <!-- /.row -->


        <div class="col-lg-12">
            <form action="" method="post" enctype="multipart/form-data">
            <div class="col-lg-6">
                <div class="form-group">
                    <img width="250px" height="250px" src="../images/<?php echo $user_image; ?>" alt="my_pic">
                    <input type="file" name="image">
                </div>
            </div>
            <div class="col-lg-6">
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
                            $roleSql = "SELECT DISTINCT user_role FROM users ";
                            $stmt = mysqli_stmt_init($conn);
                            if(!mysqli_stmt_prepare($stmt, $roleSql)){
                                echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
                            } else {
                                mysqli_stmt_execute($stmt);
                            }
                            $rolesData = mysqli_stmt_get_result($stmt);
                            while($data = mysqli_fetch_assoc($rolesData)) {
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
                    <input class="btn btn-primary" type="submit" name="edit_user" value="Update Profile">
                </div>
            </form>
            </div>
            </div>


        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<?php include "includes/admin_footer.php" ?>
