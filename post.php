<?php session_start(); ?>
<?php include "includes/db.php" ?>
<?php include "includes/header.php" ?>
    <!-- Navigation -->
<?php include "includes/navigation.php" ?>

    <!-- Page Content -->
    <div class="container">

    <div class="row" style="">

    <!-- Blog Entries Column -->
    <div class="col-md-8">

        <?php
        if(isset($_GET['p_id'])){
            $post_id = $_GET['p_id'];
        }

//        $selectPosts = "SELECT * FROM posts WHERE post_id = ? ";
        $selectPosts = "SELECT post_title, post_author, post_image, post_content, DATE_FORMAT(post_date, '%M %d, %Y') as post_date, post_tags FROM posts WHERE post_id = ? ";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $selectPosts)){
            echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
        } else {
            mysqli_stmt_bind_param($stmt, 's', $post_id);
            mysqli_stmt_execute($stmt);
        }
        $postsData = mysqli_stmt_get_result($stmt);

        while($row = mysqli_fetch_assoc($postsData)){
            $post_title = $row['post_title'];
            $post_author = $row['post_author'];
            $post_date = $row['post_date'];
            $post_image = $row['post_image'];
            $post_content = $row['post_content'];
            $post_tags = $row['post_tags'];
//            print_r($post_date);
//            echo $post_date;
//            $dt = strtotime($post_date);
//            echo $dt;
//            $post_date1 = date_format($dt, 'Y-m-d');
//                    echo "$post_title";
//                }

            ?>
            <h1 class="page-header" style="border-bottom: 0;">
                <div>
                <a class="btn btn-primary" href="index.php"><span class="glyphicon glyphicon-chevron-left"></span> Go Back</a>
                    <?php
                    if(isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 'admin')) {
                        ?>
                        <a class="btn btn-primary" href="admin/posts.php?source=edit_post&p_id=<?php echo $post_id; ?>"><span class="glyphicon glyphicon-pencil"></span> Edit Post</a>
                        <?php
                     } ?>
                </div>
            </h1>

            <p class="lead">
                by <span style="text-decoration: underline; text-transform: capitalize;"><?php echo $post_author; ?></span> on <span><?php echo $post_date; ?></span>
            </p>

            <h1><?php echo $post_title; ?></h1>
            <a style="border: 1px solid black; border-radius: 25px; display: inline-block; padding: 5px; margin: 3vh 0; color: #337ab7; border-color: #337ab7; text-transform: uppercase; font-weight: bold; cursor: pointer; "><?php echo $post_tags; ?></a>


            <!--            <hr>-->
            <img class="img-responsive" style="width: 50%; height: 300px; position: center; margin: auto" src="images/<?php echo $post_image; ?>" alt="">
            <hr>
            <p><?php echo $post_content; ?></p>
            <hr>

        <?php } ?>

        <!-- Blog Comments -->

        <?php
        date_default_timezone_set('Europe/Skopje');
        if(isset($_POST['create_comment'])){
            $id = $_GET['p_id'];
            $author = $_POST['comment_author'];
            $email = $_POST['comment_email'];
            $comment = $_POST['comment'];
            if(!empty($_POST['comment_author']) && !empty($_POST['comment']) && !empty($_POST['comment_email'])) {

//            $date = date('H:i:s d-m-y'); // change to hh:ii:ss d-m-y date style!!!
                $date = date("Y-m-d H:i:s");
                $draft = 'draft';

                $insertSql = "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) VALUES ( ?,?,?,?,?,? ) ";

                $stmt = mysqli_stmt_init($conn);
                if(!mysqli_stmt_prepare($stmt, $insertSql)){
                    echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
                }

                mysqli_stmt_bind_param($stmt, 'ssssss', $id, $author, $email, $comment, $draft, $date);
                mysqli_stmt_execute($stmt);
                okQuery($stmt);

            } else {
                ?>
                <script>alert("Fields cannot be empty! ");</script>
                <?php
            }

        }


        ?>

        <!-- Comments Form -->
        <div class="well">
            <h4>Leave a Comment:</h4>
            <form action="" method="post"  role="form">

                <div class="form-group">
                    <label for="author">Author</label>
                    <input type="text" class="form-control" name="comment_author">
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="comment_email">
                </div>
                <div class="form-group">
                    <label for="comment">Your Comment</label>
                    <textarea class="form-control" rows="3" name="comment"></textarea>
                </div>
                <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
            </form>
        </div>

        <hr>


        <?php
//        echo $post_id;
        $commentsSql = "SELECT * FROM comments WHERE comment_post_id = ? AND comment_status = 'approved' ";
        $stmt = mysqli_stmt_init($conn);
        if(!mysqli_stmt_prepare($stmt, $commentsSql)){
            echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
        } else {
            mysqli_stmt_bind_param($stmt, 's', $post_id);
            mysqli_stmt_execute($stmt);
        }
        $commentsData = mysqli_stmt_get_result($stmt);

        while($row = mysqli_fetch_assoc($commentsData)){
            $comment_content = $row['comment_content'];
            $date = $row['comment_date'];
            $comment_author = $row['comment_author'];

        ?>

        <!--Comments-->
        <div class="media">
            <a class="pull-left" href="#">
                <img class="media-object" src="http://placehold.it/64x64" alt="">
            </a>
            <div class="media-body">
                <h4 class="media-heading"><?php echo $comment_author; ?>
<!--                    <small>August 25, 2014 at 9:30 PM</small>-->
                    <small><?php echo $date; ?></small>
                </h4>
                <?php echo $comment_content; ?></div>
        </div>
<?php } ?>

    </div>

    <?php include "includes/sidebar.php"; ?>
    </div>
    </div>
<!--    <hr>-->
<?php include "includes/footer.php"; ?>