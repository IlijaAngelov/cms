<?php
session_start();
include "includes/db.php";
include "../functions.php";

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
    }
    if($_FILES['image']['size'] > 200000) {
        header('Location: posts.php?source=add_post&error=sizeError');
    }

    move_uploaded_file($temp_image_name, "../images/$image");

    createPost($category_id, $title, $author, $image, $content, $tags, $status, $post_comment_count = 0);

}