<form action="" method="post" id="edit_form">
    <div class="form-group">
        <label for="cat_title">Edit Category</label>

        <?php
        if(isset($_GET['edit'])) {
            $the_cat_id = $_GET['edit'];
//            echo $cat_id;
            $sql = "SELECT * FROM categories WHERE cat_id = $cat_id";
            $select_query = mysqli_query($conn, $sql);

            while($data = mysqli_fetch_assoc($select_query)) {
                $cat_id = $data['cat_id'];
                $cat_title = $data['cat_title'];
                ?>

                <input value="<?php if(isset($cat_title)) {echo $cat_title;} ?>" class="form-control" type="text" name="cat_title" id="edit">

            <?php } } ?>

        <?php

        // UPDATE QUERY
        if(isset($_POST['update_category'])) {
            $the_cat_title = $_POST['cat_title'];
            $sql1 = "UPDATE categories SET cat_title = '$the_cat_title' WHERE cat_id = $the_cat_id";
            $update_query = mysqli_query($conn, $sql1);
            if(!$update_query) {
                die("Query Failed!" . mysqli_error($conn));
            } else {
                // try to make notifications!
                echo "Category with ID " . $the_cat_id . " is updated!";
                ?>
                    <script>
                        document.getElementById('edit').value = '';
                        document.getElementById('edit_form').style.display = "none";
                    </script>
        <?php
            }
        }

        ?>

    </div>
    <div class="form-group">
        <input class="btn btn-primary" type="submit" name="update_category" value="Update Category">
    </div>
</form>