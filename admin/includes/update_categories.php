<form action="" method="post" id="edit_form">
    <div class="form-group">
        <label for="cat_title">Edit Category</label>

        <?php
        if(isset($_GET['edit'])) {
            $the_cat_id = $_GET['edit'];

            $sql = "SELECT * FROM categories WHERE cat_id = ? ";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
            } else {
                mysqli_stmt_bind_param($stmt, 's', $cat_id);
                mysqli_stmt_execute($stmt);
            }

            $resultData = mysqli_stmt_get_result($stmt);
            while($data = mysqli_fetch_assoc($resultData)) {
                $cat_id = $data['cat_id'];
                $cat_title = $data['cat_title'];
                ?>

                <input value="<?php if(isset($cat_title)) {echo $cat_title;} ?>" class="form-control" type="text" name="cat_title" id="edit">

            <?php } } ?>

        <?php

        if(isset($_POST['update_category'])) {
            $the_cat_title = $_POST['cat_title'];
            $sql = "UPDATE categories SET cat_title = ? WHERE cat_id = ? ";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
            } else {
                mysqli_stmt_bind_param($stmt, 'ss', $the_cat_title, $the_cat_id);
                mysqli_stmt_execute($stmt);
            }
            echo '<p class="alert alert-success" role="alert">Category Updated</p>';
        }

        ?>

    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="update_category" value="Update Category">
    </div>
</form>