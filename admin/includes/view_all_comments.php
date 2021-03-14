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
        $sql = "SELECT * FROM comments";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
        } else {
            mysqli_stmt_execute($stmt);
        }
        $allComments = mysqli_stmt_get_result($stmt);
        while($data = mysqli_fetch_assoc($allComments)){
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
            echo "<td>$email</td>";
            echo "<td>$status</td>";

            $sql = "SELECT * FROM posts WHERE post_id = ? ";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
            } else {
                mysqli_stmt_bind_param($stmt, 's', $comment_id);
                mysqli_stmt_execute($stmt);
            }
            $allPosts = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_assoc($allPosts)){
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

    $sql = "DELETE FROM comments WHERE comment_id = ? ";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
    } else {
        mysqli_stmt_bind_param($stmt, 's', $c_id);
        mysqli_stmt_execute($stmt);
    }
    header("Location: comments.php");
}


if(isset($_GET['unapprove'])){
    $c_id = $_GET['unapprove'];
    $sql = "UPDATE comments SET comment_status = 'unapproved' WHERE comment_id = ? ";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
    } else {
        mysqli_stmt_bind_param($stmt, 's', $c_id);
        mysqli_stmt_execute($stmt);
    }
    header("Location: comments.php");

}

if(isset($_GET['approve'])){
    $c_id = $_GET['approve'];
    $sql = "UPDATE comments SET comment_status = 'approved' WHERE comment_id = ? ";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
    } else {
        mysqli_stmt_bind_param($stmt, 's', $c_id);
        mysqli_stmt_execute($stmt);
    }
    header("Location: comments.php");
}