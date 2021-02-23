<?php

if(isset($_POST['create_post'])){
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $author = $_POST['author'];
    $status = $_POST['status'];
    $image = $_FILES['image']['name'];
    $image_temp = $_FILES['image']['tmp_name'];
    $tags = $_POST['tags'];
    $content = $_POST['content'];
    $date = date('d-m-y');

    move_uploaded_file($image_temp, "../images/$image");

    $insert_sql = "INSERT INTO posts (post_category_id, post_title, post_author, post_date, post_image, post_content, post_tags, post_status, post_comment_count) ";
    $insert_sql.= " VALUES ('$category_id', '$title', '$author', now(), '$image', '$content', '$tags', '$status', '0') ";
    $query = mysqli_query($conn, $insert_sql);

    okQuery($query);

}


?>
<form action="" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label for="title">Post Title</label>
        <input class="form-control" type="text" name="title">
    </div>
    <div class="form-group">
<!--        <label for="category_id">Post Category Id</label>-->
<!--        <input class="form-control" type="text" name="category_id">-->
        <select name="category_id" id="category">
            <?php

            $sql = "SELECT * FROM categories ";
            $select_query = mysqli_query($conn, $sql);
            okQuery($select_query);
            while($data = mysqli_fetch_assoc($select_query)) {
                $cat_id = $data['cat_id'];
                $cat_title = $data['cat_title'];
                echo "<option value=$cat_id>$cat_title</option>";
            }


            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="author">Post Author</label>
        <input class="form-control" type="text" name="author">
    </div>
<!--    <div class="form-group">-->
<!--        <label for="status">Post Status</label>-->
<!--        <input class="form-control" type="text" name="status">-->
<!--    </div>-->
    <div class="form-group">
        <select name="status" id="status">
            <option value="published">Published</option>
            <option value="draft" selected>Draft</option>
        </select>
    </div>
    <div class="form-group">
        <input type="file" name="image">
    </div>
    <div class="form-group">
        <label for="tags">Post Tags</label>
        <input class="form-control" type="text" name="tags">
    </div>
    <div class="form-group">
        <label for="content">Post Content</label>
        <textarea class="form-control" name="content" id="" cols="30" rows="10"></textarea>
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="create_post" value="Publish Post">
    </div>
</form>

