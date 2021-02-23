<?php
if(isset($_POST['checkBoxArray'])) {
    foreach($_POST['checkBoxArray'] as $value ){
        $bulkOptions = $_POST['bulkOptions'];
//        echo $bulkOptions;

        switch ($bulkOptions){
            case 'published':
                $sql_publish = "UPDATE posts SET post_status = 'Published' WHERE post_id = $value";
                $query = mysqli_query($conn, $sql_publish);
                okQuery($query);
                break;
            case 'draft':
                $sql_draft = "UPDATE posts SET post_status = 'draft' WHERE post_id = $value";
                $query = mysqli_query($conn, $sql_draft);
                okQuery($query);
                break;
            case 'delete':
                $sql_delete = "DELETE FROM posts WHERE post_id = $value";
                $query = mysqli_query($conn, $sql_delete);
                okQuery($query);
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
//        global $conn;
        $sql = "SELECT * FROM posts ORDER BY `post_id` ASC";
        $query = mysqli_query($conn, $sql);
        while($data = mysqli_fetch_assoc($query)){
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

            $select_sql = "SELECT * FROM categories WHERE cat_id = $category_id";
            $select_query = mysqli_query($conn, $select_sql);
            while($row = mysqli_fetch_assoc($select_query)){
                $c_id = $row['cat_id'];
                $title = $row['cat_title'];
            }

            echo"<td>$title</td>";
            echo"<td>$status</td>";
            echo"<td><img class='img-responsive' src='../images/$image' alt='something' width='100px' height='100px'></td>";
            echo"<td>$tags</td>";

            $comment_number_sql = "SELECT * FROM comments WHERE comment_post_id = $id";
//            echo "<td>$comment_number_sql</td>";
            $comment_number_query = mysqli_query($conn, $comment_number_sql);
            $rows = mysqli_num_rows($comment_number_query);
//                echo $rows;

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

    $sql = "DELETE FROM posts WHERE post_id = $id";
    $query = mysqli_query($conn, $sql);
    header("Location: posts.php");
    okQuery($query);
}