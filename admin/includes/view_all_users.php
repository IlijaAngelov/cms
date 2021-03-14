<table class="table table-striped table-hover table-responsive">
    <thead>
    <tr>
        <th>Id</th>
        <th>Username</th>
        <th>Password</th>
        <th>FirstName</th>
        <th>LastName</th>
        <th>Email</th>
        <th>Image</th>
        <th>Role</th>
        <th>Created On</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <?php

        $users_sql = "SELECT * FROM users ";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $users_sql)){
            echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
        } else {
            mysqli_stmt_execute($stmt);
        }
        $usersData = mysqli_stmt_get_result($stmt);
        while($data = mysqli_fetch_assoc($usersData)){
            $user_id = $data['user_id'];
            $username = $data['username'];
            $password = $data['password'];
            $firstName = $data['firstName'];
            $lastName = $data['lastName'];
            $user_email = $data['user_email'];
            $user_image = $data['user_image'];
            $user_role = $data['user_role'];
            $created_on = $data['created_on'];

            echo "<tr>";
            echo "<td>$user_id</td>";
            echo "<td>$username</td>";
            echo "<td>***</td>";
            echo "<td>$firstName</td>";
            echo "<td>$lastName</td>";
            echo "<td>$user_email</td>";
            echo"<td><img class='img-responsive' src='../images/$user_image' alt='something' width='50px' height='50px'></td>";
            echo "<td>$user_role</td>";
            echo "<td>$created_on</td>";
            echo "<td><a href='users.php?source=edit_user&user_id=$user_id'>Edit</a></td>";
            echo "<td><a href='users.php?delete=$user_id'>Delete</a></td>";
            echo"</tr>";
        }

        ?>

    </tbody>
</table>


<?php

if(isset($_GET['delete'])){
    $user_id = $_GET['delete'];
    $deleteUser = "DELETE FROM users WHERE user_id = ? ";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $deleteUser)){
        echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
    } else {
        mysqli_stmt_bind_param($stmt, 's', $user_id);
        mysqli_stmt_execute($stmt);
    }
    header("Location: users.php");
}
