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

        $sql = "SELECT * FROM `users` ";
        $query = mysqli_query($conn, $sql);
        while($data = mysqli_fetch_assoc($query)){
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


//            $select_sql = "SELECT * FROM categories WHERE cat_id = $category_id";
//            $select_query = mysqli_query($conn, $select_sql);
//            while($row = mysqli_fetch_assoc($select_query)){
//                $id = $row['cat_id'];
//                $title = $row['cat_title'];
//            }

            echo "<td>$user_email</td>";
            echo"<td><img class='img-responsive' src='../images/$user_image' alt='something' width='50px' height='50px'></td>";
//            echo "<td>$user_image</td>";
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
    $delete_sql = "DELETE FROM users WHERE user_id = $user_id";
    $delete_query = mysqli_query($conn, $delete_sql);
    header("Location: users.php");
}


if(isset($_GET['unapprove'])){
    $c_id = $_GET['unapprove'];
    $unapprove_sql = "UPDATE comments SET comment_status = 'unapproved' WHERE comment_id = $c_id";
    $unapprove_query = mysqli_query($conn, $unapprove_sql);
    header("Location: comments.php");
//    okQuery($unapprove_query);
}

if(isset($_GET['approve'])){
    $c_id = $_GET['approve'];
    $approve_sql = "UPDATE comments SET comment_status = 'approved' WHERE comment_id = $c_id";
    $approve_query = mysqli_query($conn, $approve_sql);
    header("Location: comments.php");
//    okQuery($unapprove_query);
}