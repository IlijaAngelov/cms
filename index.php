<?php error_reporting(E_ALL); ?>
<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>
<?php session_start(); ?>

    <!-- Navigation -->
<?php include "includes/navigation.php" ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

                <?php
                $published = 'published';
                $postsSql = "SELECT * FROM posts WHERE post_status = ? ";
                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $postsSql)){
                    echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
                } else {
                    mysqli_stmt_bind_param($stmt, 's', $published);
                    mysqli_stmt_execute($stmt);
                }
                $postsData = mysqli_stmt_get_result($stmt);
                while($row = mysqli_fetch_assoc($postsData)){
                    $post_id = $row['post_id'];
                    $post_title = $row['post_title'];
                    $post_author = $row['post_author'];
                    $post_date = $row['post_date'];
                    $post_image = $row['post_image'];
                    $post_content = substr($row['post_content'], 0, 100);
                ?>
                <h1>
                    <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title; ?></a>
                </h1>
                    <a href="post.php?p_id=<?=$post_id;?>">
                        <img class="img-responsive" src="images/<?php echo $post_image; ?>" alt="">
                    </a>
                <p class="lead">
                    by <a href="index.php"><?php echo $post_author; ?></a>
                </p>
                <p><span class="glyphicon glyphicon-time"></span> <?php echo $post_date; ?></p>
<!--                <hr>-->
<!--                    -->
<!--                <hr>-->
                <p><?php echo $post_content; ?></p>
                <a class="btn btn-primary" href="post.php?p_id=<?=$post_id;?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

                <hr>

                <?php } ?>

            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>
        <!-- /.row -->

        <hr>

<?php include "includes/footer.php"; ?>