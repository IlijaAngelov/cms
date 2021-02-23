<?php

session_start();

include_once ('../includes/db.php');
include_once ('../admin/functions.php');

if( !isLoggedIn() ){
    header('index.php');
}

$post_id = $_REQUEST['id'];
$action = $_REQUEST['a'];

if (!isset($_SESSION['username'])) {
    $result['res'] = null;
    $result['error'] = "Inavlid access";
    // set header as json
    header("Content-type: application/json");
    echo json_encode($result);
    exit();
}
if ( $action == "send" ){
    $category = $_REQUEST['category'];
    $title = $_REQUEST['title'];
    $author = $_REQUEST['author'];
    $image = $_REQUEST['image'];
    $content = $_REQUEST['content'];
    $tags = $_REQUEST['tags'];
    $status = $_REQUEST['status'];

//    $sqls = "UPDATE posts SET post_author = 'MEEZZ22222' WHERE post_id = '$post_id'";
    $sqls = "UPDATE posts SET post_category_id = '$category', post_title = '$title', ";
    $sqls.= "post_author = '$author', post_date = now(), post_image = '$image', post_content = '$content', ";
    $sqls.= "post_tags = '$tags', post_status = '$status' ";
    $sqls.= "WHERE post_id = $post_id" ;
    $result = mysqli_query($conn, $sqls);
} else {
    $result['res'] = null;
    $result['error'] = "Inavlid request";
}
//echo $result;
// set header as json
header("Content-type: application/json");
echo json_encode($result);
exit();
?>