<?php include "includes/header.php"; ?>
<?php include "includes/db.php"; ?>
<?php include "includes/navigation.php"; ?>
<?php session_start(); ?>
<?php
if(isset($_GET["error"])){
    if($_GET['error'] == "emptyFields"){
        echo "<p style='text-align:center;'>Please Fill all fields!</p>";
    } else if ($_GET['error'] == 'wrongLogin'){
        echo "<p style='text-align:center;'>Username/Password was wrong. Try again.</p>";
    }
}
?>
<div class="container">
    <div class="row">
        <div class="col-xs-4 col-xs-offset-4">
            <div class="form-wrap">
                <h1 class="text-center">Log In Page</h1>
                <form action="includes/inc.login.php" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="username" class="sr-only">Username:</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Enter Your Username">
                    </div>
                    <div class="form-group">
                        <label for="password" class="sr-only">Password:</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter Your Password">
                    </div>

                    <button style="btn btn-dark" type="submit" name="submit" >Log In</button>

                </form>
            </div>
        </div>
    </div>
</div>
