<?php
if(isset($_POST['checkBoxArray'])) {
    foreach($_POST['checkBoxArray'] as $value ){
        $bulkOptions = $_POST['bulkOptions'];
//        echo $bulkOptions;

        switch ($bulkOptions){
            case 'published':
                $publish_sql = "UPDATE posts SET post_status = 'Published' WHERE post_id = ? ";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $publish_sql)){
                    echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
                } else {
                    mysqli_stmt_bind_param($stmt, 's', $value);
                    mysqli_stmt_execute($stmt);
                }
                echo "<p class='alert alert-success' role='alert'>Post(s) published</p>";
                break;
            case 'draft':
                $draft_sql = "UPDATE posts SET post_status = 'draft' WHERE post_id = ? ";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $draft_sql)){
                    echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
                } else {
                    mysqli_stmt_bind_param($stmt, 's', $value);
                    mysqli_stmt_execute($stmt);
                }
                echo "<p class='alert alert-success' role='alert'>Post(s) drafted.</p>";
                break;
            case 'delete':
                $delete_sql = "DELETE FROM posts WHERE post_id = ? ";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $delete_sql)){
                    echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
                } else {
                    mysqli_stmt_bind_param($stmt, 's', $value);
                    mysqli_stmt_execute($stmt);
                }
                echo "<p class='alert alert-success' role='alert'>Post(s) deleted!</p>";
                break;

        }
    }
}

?>
<form action="" method="post">
<table class="table table-striped table-hover table-responsive">

    <div id="bulkOptionsContainer" class="col-xs-4 col-xs-4-2">
        <select class="form-control" id="" name="bulkOptions">
            <option value="">Select Option</option>
            <option value="published">Publish</option>
            <option value="draft">Draft</option>
            <option value="delete">Delete</option>
        </select>
    </div>

    <div class="col-xs-4">
        <input type="submit" name="submit" class="btn btn-success" value="Apply">
        <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>
    </div>

    <thead>
    <tr>
        <th><input class="form-check-input" type="checkbox" value="" id="selectAll"></th>
        <th>Id</th>
        <th>Author</th>
        <th>Title</th>
        <th>Category</th>
        <th>Status</th>
        <th>Image</th>
        <th>Tags</th>
        <th>Comments</th>
        <th>Date</th>
        <th>Edit</th>
        <th>Delete</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <?php
        $sql = "SELECT * FROM posts ORDER BY `post_id` ASC";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $sql)){
            echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
        } else {
            mysqli_stmt_execute($stmt);
        }
        $postsData = mysqli_stmt_get_result($stmt);
        while($data = mysqli_fetch_assoc($postsData)){
            $id = $data['post_id'];
            $category_id = $data['post_category_id'];
            $author = $data['post_author'];
            $title = $data['post_title'];
            $date = $data['post_date'];
            $image = $data['post_image'];
            $content = $data['post_content'];
            $tags = $data['post_tags'];
            $comment_count = $data['post_comment_count'];
            $status = $data['post_status'];
?>
            <td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $id; ?>'></td>
            <?php
            echo"<td>$id</td>";
            echo"<td>$author</td>";
            echo"<td><a href='../post.php?p_id=$id'>$title</a></td>";

            $categories_sql = "SELECT * FROM categories WHERE cat_id = ? ";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $categories_sql)){
                echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
            } else {
                mysqli_stmt_bind_param($stmt, 's', $category_id);
                mysqli_stmt_execute($stmt);
            }
            $categoriesData = mysqli_stmt_get_result($stmt);
            while($row = mysqli_fetch_assoc($categoriesData)){
                $c_id = $row['cat_id'];
                $title = $row['cat_title'];
            }

            echo"<td>$title</td>";
            echo"<td>$status</td>";
            echo"<td><img class='img-responsive' src='../images/$image' alt='something' width='100px' height='100px'></td>";
            echo"<td>$tags</td>";

            $comments_sql = "SELECT * FROM comments WHERE comment_post_id = ? ";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $comments_sql)){
                echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
            } else {
                mysqli_stmt_bind_param($stmt, 's', $id);
                mysqli_stmt_execute($stmt);
            }
            $commentsData = mysqli_stmt_get_result($stmt);

            $rows = mysqli_num_rows($commentsData);

            echo "<td>$rows</td>";
            echo"<td>$date</td>";
            echo"<td><a href='posts.php?source=edit_post&p_id=$id'>Edit</a></td>";
            echo"<td><a href='posts.php?delete=$id'>DELETE</a></td>";
            echo"</tr>";
        }

        ?>

    </tbody>
</table>
</form>

<?php

if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    $deletePosts = "DELETE FROM posts WHERE post_id = ? ";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $deletePosts)){
        echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
    } else {
        mysqli_stmt_bind_param($stmt, 's', $id);
        mysqli_stmt_execute($stmt);
    }

    header("Location: posts.php");
//    okQuery($query);
//    echo "<p class='alert alert-success' role='alert'>Post Deleted</p>";

}