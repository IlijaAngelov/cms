<?php include "includes/admin_header.php" ?>

<div id="wrapper" color;>

<?php //if($conn) echo "enter!"; ?>
    <!-- Navigation -->
<?php include "includes/admin_navigation.php" ?>
    <div id="page-wrapper">

        <div class="container-fluid">

            <!-- Page Heading -->
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header text-center">
                        Welcome to Admin Area! <?php echo $_SESSION['username']; ?>
                    </h1>
                </div>
            </div>
            <!-- /.row -->

            <!-- /.row -->

            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-file-text fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">

                                    <?php
                                        $postSql = "SELECT * FROM posts";
                                        $stmt = mysqli_stmt_init($conn);
                                        if(!mysqli_stmt_prepare($stmt, $postSql)){
                                            echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
                                        } else {
                                            mysqli_stmt_execute($stmt);
                                        }
                                        $postsData = mysqli_stmt_get_result($stmt);
                                        $post_count = mysqli_num_rows($postsData);
                                    ?>

                                    <div class='huge'><?php echo $post_count;?></div>
                                    <div>Posts</div>
                                </div>
                            </div>
                        </div>
                        <a href="posts.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-comments fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <?php
                                        $commentSql = "SELECT * FROM comments";
                                        $stmt = mysqli_stmt_init($conn);
                                        if(!mysqli_stmt_prepare($stmt, $commentSql)){
                                            echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
                                        } else {
                                            mysqli_stmt_execute($stmt);
                                        }
                                        $commentsData = mysqli_stmt_get_result($stmt);
                                        $comm_count = mysqli_num_rows($commentsData);
                                    ?>
                                    <div class='huge'><?php echo $comm_count;?></div>
                                    <div>Comments</div>
                                </div>
                            </div>
                        </div>
                        <a href="comments.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-user fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <?php
                                        $userSql = "SELECT * FROM users";
                                        $stmt = mysqli_stmt_init($conn);
                                        if(!mysqli_stmt_prepare($stmt, $userSql)){
                                            echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
                                        } else {
                                            mysqli_stmt_execute($stmt);
                                        }
                                        $userData = mysqli_stmt_get_result($stmt);
                                        $user_count = mysqli_num_rows($userData);
                                    ?>
                                    <div class='huge'><?php echo $user_count; ?></div>
                                    <div> Users</div>
                                </div>
                            </div>
                        </div>
                        <a href="users.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-list fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <?php
                                        $categorySql = "SELECT * FROM categories";
                                        $stmt = mysqli_stmt_init($conn);
                                        if(!mysqli_stmt_prepare($stmt, $categorySql)){
                                            echo '<p class="alert alert-warning" role="alert">Connection Error</p>';
                                        } else {
                                            mysqli_stmt_execute($stmt);
                                        }
                                        $categoryData = mysqli_stmt_get_result($stmt);
                                        $category_count = mysqli_num_rows($categoryData);
                                    ?>
                                    <div class='huge'><?php echo $category_count;?></div>
                                    <div>Categories</div>
                                </div>
                            </div>
                        </div>
                        <a href="categories.php">
                            <div class="panel-footer">
                                <span class="pull-left">View Details</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <!-- /.row -->

            <div class="row">
                <script type="text/javascript">
                    google.charts.load('current', {'packages':['bar']});
                    google.charts.setOnLoadCallback(drawChart);

                    function drawChart() {
                        var data = google.visualization.arrayToDataTable([
                            ['Data', 'Count'],
                            <?php
                            $charts = ['Posts', 'Comments', 'Users', 'Categories'];
                            $count = [$post_count, $comm_count, $user_count, $category_count];

                            for($i = 0; $i < count($charts); $i++){
                                echo "['$charts[$i]'" . "," . "$count[$i]],";
                            }
                            ?>
                        ]);

                        var options = {
                            chart: {
                                title: '',
                                subtitle: '',
                            }
                        };

                        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

                        chart.draw(data, google.charts.Bar.convertOptions(options));
                    }
                </script>
                <div id="columnchart_material" style="width: auto; height: 500px;"></div>
            </div>

        </div>
        <!-- /.container-fluid -->

    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<?php include "includes/admin_footer.php" ?>
