<?php

if(isset($_GET['error'])){
    if($_GET['error'] == 'extensionError'){
        echo "<p style='text-align: center'>Error uploading the image. Only '.jpeg', '.jpg' and '.png' image extensions are supported</p>";
    } else if($_GET['error'] == 'sizeError'){
        echo "<p style='text-align: center'>Error uploading the image. Only sizes below 2MB!</p>";
    } else if($_GET['error'] == 'stmtFailed'){
        echo "<p style='text-align: center'>Error occurred! Try creating a new post.</p>";
    } else if($_GET['error'] == 'none'){
        echo "<p style='text-align: center'>Post created successfully!</p>";
    }
}

if(isset($_POST['create_post'])){
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $author = $_POST['author'];
    $status = $_POST['status'];

    // image details
    $image = $_FILES['image']['name'];
    $temp_image_name = $_FILES['image']['tmp_name'];
    $img_size = $_FILES['image']['size'];

    $tags = $_POST['tags'];
    $content = $_POST['content'];
    $date = date('d-m-y');

    $image_extension = explode('.', $image);
    $extension = strtolower(array_pop($image_extension));
    $valid_extensions = array('jpg', 'jpeg', 'png');

    if(!in_array($extension, $valid_extensions)) {
        header('Location: posts.php?source=add_post&error=extensionError');
        exit();
    }
    if($_FILES['image']['size'] > 2000000) {
        header('Location: posts.php?source=add_post&error=sizeError');
        exit();
    }

    move_uploaded_file($temp_image_name, "../images/$image");

    createPost($category_id, $title, $author, $image, $content, $tags, $status, $post_comment_count = 0);

}


?>
<form action="" method="post" enctype="multipart/form-data">

    <div class="form-group">
        <label for="title">Post Title</label>
        <input class="form-control" type="text" name="title">
    </div>
    <div class="form-group">
        <select name="category_id" id="category">
            <?php
                $sql = "SELECT * FROM categories ";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
                } else {
                    mysqli_stmt_execute($stmt);
                }
                $resultData = mysqli_stmt_get_result($stmt);
                while($data = mysqli_fetch_assoc($resultData)) {
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

