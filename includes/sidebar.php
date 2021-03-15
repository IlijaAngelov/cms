<div class="col-md-3">
    <div class="well">
        <h4>Login</h4>
        <form action="includes/inc.login.php" method="post">
            <div class="form-group">
                <input name="username" type="text" class="form-control" placeholder="Enter Username">
            </div>
            <div class="input-group">
                <input name="password" type="password" class="form-control" placeholder="Enter Password">
                <span class="input-group-btn"><button class="btn btn-primary" type="submit" name="submit">Log In</button></span>
            </div>

        </form>
    </div>

    <!-- Blog Search Well -->
    <div class="well">
        <h4>Search</h4>
        <form action="search.php " method="post">
        <div class="input-group">
            <input name="search" type="text" class="form-control">
            <span class="input-group-btn">
                <button name="submit" class="btn btn-default" type="submit">
                <span class="glyphicon glyphicon-search"></span>
                </button>
            </span>
        </div>
        </form>
    </div>

    <!-- Blog Categories Well -->
    <div class="well">
        <?php
            $sql = "SELECT * FROM categories";
            $stmt = mysqli_stmt_init($conn);
            if(!mysqli_stmt_prepare($stmt, $sql)){
                echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
            } else {
                mysqli_stmt_execute($stmt);
            }
            $categories = mysqli_stmt_get_result($stmt);
        ?>
        <h4>Popular Categories</h4>
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-unstyled">
                    <?php
                        while($row = mysqli_fetch_assoc($categories)){
                            $cat_id = $row['cat_id'];
                            $cat_title = $row['cat_title'];
                            echo "<li><a href='category.php?category=$cat_id'>$cat_title</a></li>";
                        }
                    ?>
                </ul>
            </div>
        </div>
    </div>

<!--    --><?php //include "widget.php"; ?>
</div>
<!--</div>-->