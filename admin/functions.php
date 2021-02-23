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
        echo "Query Successfully Executed!";
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
            $sql = "INSERT INTO categories (cat_title) VALUE ('$cat_title') ";
            $create_cat_query = mysqli_query($conn, $sql);

            if(!$create_cat_query) {
                die('QUERY FAILED!' . mysqli_error($conn, $create_cat_query));
            } else {
                echo "Category added successfully!";
            }
        }
    }
}

function findAllCategories(){
    global $conn;

    $sql = "SELECT * FROM categories";
    $query = mysqli_query($conn, $sql);

    while($data = mysqli_fetch_assoc($query)){
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
        $sql = "DELETE FROM categories WHERE cat_id = $delete_id";
        $query = mysqli_query($conn, $sql);
        header("LOCATION: categories.php");
    }
}

