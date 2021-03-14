<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.php">CMS</a>
        </div>
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <?php if(isset($_SESSION['username'])) {
                    echo "<li><a class='navbar-brand' href='includes/inc.logout.php'>Logout</a></li>";
                } else {
                    echo "<li><a class='navbar-brand' href='includes/inc.login.php'>Login</a></li>";
                }
                ?>

                <li><a class="navbar-brand" href="signup.php">Sign Up</a></li>
                <?php
                if(isset($_SESSION['user_role'])) {
                    if ($_SESSION['user_role'] === 'admin') {
                        echo "<li><a class='navbar-brand' href='admin/index.php'>Go to Admin</a></li>";
                    }
                }
                ?>
            </ul>
            <?php if(isset($_SESSION['username'])) { ?>
            <ul class="nav navbar-right navbar-nav top-nav">
                <li class="dropdown">
                    <a href="#" style="text-transform:uppercase;" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $_SESSION['username']; ?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li>
                            <a href="profile.php"><i class="fa fa-fw fa-user"></i> Profile</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="includes/logout.php"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
                        </li>
                    </ul>
                </li>
            </ul>
            <?php } ?>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container -->
</nav>