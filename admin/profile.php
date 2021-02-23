<?php include "includes/admin_header.php" ?>
<?php
if(isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $query = mysqli_query($conn, $sql);

    while($row = mysqli_fetch_array($query)){
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
        $sql11 = "SELECT * FROM users WHERE user_id = '$user_id' ";
        $image_query1 = mysqli_query($conn, $sql11);
        while ($row = mysqli_fetch_array($image_query1)) {
            $image = $row['user_image'];
        }
    }

    $update_sql = "UPDATE users SET username = '$username', password = '$password', ";
    $update_sql.= "user_role = '$user_role', firstName = '$firstName', lastName = '$lastName', user_email = '$user_email', user_image = '$image' ";
    $update_sql.= "WHERE user_id = '$user_id'" ;
    $update_query = mysqli_query($conn, $update_sql);
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
                        $sql = "SELECT DISTINCT user_role FROM users ";
                        $select_query = mysqli_query($conn, $sql);
//                        okQuery($select_query);
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
