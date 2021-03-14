<?php

if(isset($_POST['create_user'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $user_email = $_POST['email'];
    $user_role = $_POST['select_role'];
    $image = $_FILES['image']['name'];
    $image_temp = $_FILES['image']['tmp_name'];
    $date = date('d-m-y H:i:s');

    move_uploaded_file($image_temp, "../images/$image");

    $sql = "INSERT INTO users (username, password, firstName, lastName, user_email, user_image, user_role, created_on) ";
    $sql.= "VALUES ('$username', '$password', '$firstName', '$lastName', '$user_email', '$image', '$user_role', '$date') ";
    $query = mysqli_query($conn, $sql);

    okQuery($query);

    echo  "User created!: " . " " .  "<a href='users.php'>View Users</a> ";

}

?>
<p><span style="color: red">* required field</span></p>
<form method="post" enctype="multipart/form-data" action="">

    <div class="form-group">
        <label for="username">Username<span style="color: red"> *</span></label>
        <input class="form-control" type="text" name="username">
    </div>
    <div class="form-group">
        <label for="password">Password<span style="color: red"> *</span></label>
        <input class="form-control" type="password" name="password">
    </div>
    <div class="form-group">
        <label for="select_role">Select a User Role</label>
        <div></div>
        <select name="select_role" id="select_role">
            <option value="basic">Select Options</option>
            <option value="admin">Admin</option>
            <option value="basic">Basic</option>
        </select>
    </div>
    <div class="form-group">
        <label for="firstName">First Name</label>
        <input class="form-control" type="text" name="firstName">
    </div>
    <div class="form-group">
        <label for="lastName">Last Name</label>
        <input class="form-control" type="text" name="lastName">
    </div>
    <div class="form-group">
        <label for="email">Email<span style="color: #ff0000"> *</span></label>
        <input class="form-control" type="email" name="email">
    </div>
    <div class="form-group">
        <input type="file" name="image">
    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="create_user" value="Create User">
    </div>
</form>

