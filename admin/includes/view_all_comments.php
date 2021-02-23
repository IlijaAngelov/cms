<table class="table table-striped table-hover table-responsive">
    <thead>
    <tr>
        <th>Id</th>
        <th>Author</th>
        <th>Comment</th>
        <th>Email</th>
        <th>Status</th>
        <th>In Response to</th>
        <th>Date</th>
        <th>Approve</th>
        <th>Unapprove</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <?php
        //        global $conn;
        $sql = "SELECT * FROM comments";
        $query = mysqli_query($conn, $sql);
        while($data = mysqli_fetch_assoc($query)){
            $c_id = $data['comment_id'];
            $comment_id = $data['comment_post_id'];
            $author = $data['comment_author'];
            $email = $data['comment_email'];
            $content = $data['comment_content'];
            $status = $data['comment_status'];
            $date = $data['comment_date'];

            echo "<tr>";
            echo "<td>$c_id</td>";
            echo "<td>$author</td>";
            echo "<td>$content</td>";


//            $select_sql = "SELECT * FROM categories WHERE cat_id = $category_id";
//            $select_query = mysqli_query($conn, $select_sql);
//            while($row = mysqli_fetch_assoc($select_query)){
//                $id = $row['cat_id'];
//                $title = $row['cat_title'];
//            }

            echo "<td>$email</td>";
            echo "<td>$status</td>";

            $select_sql = "SELECT * FROM posts WHERE post_id = '$comment_id'";
            $select_query = mysqli_query($conn, $select_sql);
            while($row = mysqli_fetch_assoc($select_query)){
                $id = $row['post_id'];
                $response = $row['post_title'];
                echo "<td><a href='../post.php?p_id=$id'>$response</a></td>";
            }
            echo "<td>$date</td>";
            echo"<td><a href='comments.php?approve=$c_id'>Approve</a></td>";
            echo"<td><a href='comments.php?unapprove=$c_id'>Unapprove</a></td>";
            echo"<td><a href='comments.php?delete=$c_id'>DELETE</a></td>";
            echo"</tr>";
        }

        ?>

    </tbody>
</table>


<?php

if(isset($_GET['delete'])){
    $comment_id = $_GET['delete'];

    $delete_sql = "DELETE FROM comments WHERE comment_id = $c_id";
    $delete_query = mysqli_query($conn, $delete_sql);
    header("Location: comments.php");
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