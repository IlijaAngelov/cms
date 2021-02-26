<?php include "includes/header.php"; ?>
<?php include "includes/db.php"; ?>
<?php include "includes/navigation.php"; ?>
<?php session_start(); ?>
<?php
if(isset($_GET["error"])){
    if($_GET['error'] == "emptyFields"){
        echo "<p style='text-align:center;'>Please Fill all fields!</p>";
    } else if ($_GET['error'] == 'invalidUsername'){
        echo "<p style='text-align:center;'>Choose proper username!</p>";
    } else if ($_GET['error'] == 'mismatchPasswords'){
        echo "<p style='text-align:center;'>Your password and repeat password field doesnt match!</p>";
    } else if ($_GET['error'] == 'emailExists'){
        echo "<p style='text-align:center;'>User with that email already registered!</p>";
    } else if ($_GET['error'] == 'usernameExists'){
        echo "<p style='text-align:center;'>User with that username already registered. Please choose another.</p>";
    } else if ($_GET['error'] == 'stmtfailed') {
        echo "<p style='text-align:center;'>Something went wrong. Please try again.</p>";
    } else {
        echo "<p style='text-align:center;'>Sign-up is successful!</p>";
        echo "<p style='text-align:center;'><a href=''>Click here to login</a></p>";
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-xs-4 col-xs-offset-4">
            <div class="form-wrap">
                <h1 class="text-center">Sign Up Page</h1>
                <form action="includes/inc.signup.php" method="post" autocomplete="off">
                    <div class="form-group">
                        <label for="firstname" class="sr-only">First Name:</label>
                        <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Enter Your First Name">
                    </div>
                    <div class="form-group">
                        <label for="lastname" class="sr-only">Last Name:</label>
                        <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Enter Your Last Name">
                    </div>
                    <div class="form-group">
                        <label for="email" class="sr-only">Email:</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter Your Email">
                    </div>
                    <div class="form-group">
                        <label for="username" class="sr-only">Username:</label>
                        <input type="text" name="username" id="username" class="form-control" placeholder="Enter Your Desired Username">
                    </div>
                    <div class="form-group">
                        <label for="password" class="sr-only">Password:</label>
                        <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password">
                    </div>
                    <div class="form-group">
                        <label for="password" class="sr-only">Repeat Password:</label>
                        <input type="password" name="repeat_password" id="repeat_password" class="form-control" placeholder="Repeat Password">
                    </div>

                    <button style="btn btn-dark" type="submit" name="submit" >Sign Up</button>

<!--                    <input type="submit" class="btn btn-default" value="Register">-->

                </form>
            </div>
        </div>
    </div>
</div>




<?php //include "includes/footer.php"; ?>