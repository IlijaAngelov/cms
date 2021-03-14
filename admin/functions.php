<?php

function isLoggedIn()
{
    if (isset($_SESSION['username']) AND $_SESSION['username'] !== '') {
        return true;
        echo $_SESSION['username'];
    } else {
        return false;
    }
}

function okQuery($result){
    global $conn;
    if(!$result) {
        die("Query Failed! " . mysqli_error($conn, $result));
    } else {
        echo "<p style='text-align: center'>Successful!</p>";
    }

    return $result;

}

function insertCategories(){
    global $conn;
    if(isset($_POST['submit'])) {
        $cat_title = mysqli_real_escape_string($conn, $_POST['cat_title']);
        if($cat_title == "" || empty($cat_title)) {
            echo "Enter a title for the Category!";
        } else {
            $sql = "INSERT INTO categories (cat_title) VALUE ( ? ) ";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
            } else {
                mysqli_stmt_bind_param($stmt, 's', $cat_title);
                mysqli_stmt_execute($stmt);
                echo "<p style='text-align: center'>New Category added!11</p>";
            }
        }
    }
}

function findAllCategories(){
    global $conn;

    $sql = "SELECT * FROM categories";
    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $sql)){
        echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
    } else {
        mysqli_stmt_execute($stmt);
    }

    $resultData = mysqli_stmt_get_result($stmt);

    while($data = mysqli_fetch_assoc($resultData)){
    $id = $data['cat_id'];
    $title = $data['cat_title'];

    echo "<tr>";
    echo "<td>$id</td>";
    echo "<td>$title</td>";
    echo "<td><a href='categories.php?delete={$id}'>Delete</a></td>";
    echo "<td><a href='categories.php?edit={$id}'>Edit</a></td>";
    echo "</tr>";
    }
}

function updateCategories(){
    global $conn;

    if(isset($_GET['edit'])) {
        $cat_id = $_GET['edit'];
        include "includes/update_categories.php";
    }
}

function deleteCategories(){
    global $conn;
    if(isset($_GET['delete'])) {
        $delete_id = $_GET['delete'];
        $query = "DELETE FROM categories WHERE cat_id = ? ";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $query)){
            echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
        }
        $stmt->bind_param('s', $delete_id);
        $stmt->execute();
//        echo "<p style='text-align: center'>Category deleted!</p>";
        header("LOCATION: categories.php");
    }
}

function createPost($category_id, $title, $author, $image, $content, $tags, $status, $post_comment_count){
    global $conn;
    $post_comment_count = 0;
    $query = "INSERT INTO posts (post_category_id, post_title, post_author, post_date, post_image, post_content, post_tags, post_status, post_comment_count) ";
    $query.= "VALUES ( ?, ?, ?, now(), ?,?,?,?,? ) ";

    $stmt = mysqli_stmt_init($conn);
    if(!mysqli_stmt_prepare($stmt, $query)){
        header("Location: posts.php?source=add_post&error=stmtfailed");
        exit();
    }

    mysqli_stmt_bind_param($stmt, 'ssssssss', $category_id, $title, $author, $image, $content, $tags, $status, $post_comment_count );
    mysqli_stmt_execute($stmt);

    header("Location: posts.php?source=add_post&error=none");
    exit();
}

