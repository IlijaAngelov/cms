<?php

if(isset($_GET['p_id'])){
    $id = $_GET['p_id'];
}

$sqlz = "SELECT * FROM posts WHERE post_id = $id";
$query = mysqli_query($conn, $sqlz);
while($data = mysqli_fetch_assoc($query)) {
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
}


if(isset($_POST['update_post'])){

    $author = $_POST['author'];
    $title = $_POST['title'];
    $category_id = $_POST['category'];
    $status = $_POST['status'];
    $image = $_FILES['image']['name'];
    $image_temp = $_FILES['image']['tmp_name'];
    $content = $_POST['content'];
    $tags = $_POST['tags'];

    move_uploaded_file($image_temp, "../images/$image");
    if(empty($image)) {
        $sql1 = "SELECT * FROM posts WHERE post_id = $id ";
        $image_query = mysqli_query($conn, $sql1);
        while ($row = mysqli_fetch_array($image_query)) {
            $image = $row['post_image'];
        }
    }

    $sqls = "UPDATE posts SET post_category_id = '$category_id', post_title = '$title', ";
    $sqls.= "post_author = '$author', post_date = now(), post_image = '$image', post_content = '$content', ";
    $sqls.= "post_tags = '$tags', post_status = '$status' ";
    $sqls.= "WHERE post_id = $id" ;

    $query = mysqli_query($conn, $sqls);

    okQuery($query);
    echo "<p class='bg-success'>Post Updated <a href='../post.php?p_id=$id'>View Post</a> or <a href='posts.php'>Edit More Posts</a></p>";
}
?>


<form action="" method="post" enctype="multipart/form-data" id="edit_post_form">

    <div class="form-group">
        <label for="title">Post Title</label>
        <input class="form-control" type="text" name="title" value="<?php echo $title; ?>">
    </div>
    <div class="form-group">
        <select name="category" id="category">
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
        <input class="form-control" type="text" name="author" value="<?php echo $author; ?>">
    </div>

    <div class="form-group">
        <select name="status" id="">
            <option value='<?=$status; ?>'><?=$status; ?></option>
            <?php
            if($status == 'published'){ ?>
            <option value='<?php echo 'draft'; ?>'><?php echo 'Draft' ?></option>
            <?php } else { ?>
            <option value='<?php echo 'Published'; ?>'><?php echo 'Published' ?></option>
            <?php } ?>
            ?>
        </select>
    </div>

    <div class="form-group">
        <img width="100px" height="100px" src="../images/<?php echo $image; ?>" alt="">
        <input type="file" name="image">
    </div>
    <div class="form-group">
        <label for="tags">Post Tags</label>
        <input class="form-control" type="text" name="tags" value="<?php echo $tags; ?>">
    </div>
    <div class="form-group">
        <label for="content">Post Content</label>
        <textarea class="form-control" name="content" id="" cols="30" rows="10" value="<?php echo $content; ?>"><?php echo $content; ?></textarea>
    </div>

    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="update_post" value="Update Post" id="update_post">
    </div>
</form>
