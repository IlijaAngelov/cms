<?php include "includes/header.php"; ?>
<?php include "includes/db.php"; ?>
<?php include "includes/navigation.php"; ?>

<?php
if(isset($_GET['mismatchPassword'])){
    echo "Your password and repeat password field doesnt match! ";
}

if(isset($_GET['emailExists'])) {
    echo "An account with that email is already registered! ";
    echo "If you thats you and you've forgotten your password click here!";
}

if(isset($_GET['usernameExists'])){
    echo "That username already exists in Database. Please choose another one!";
}

if(isset($_GET['emptyFields'])){
    echo "Please fill all the required fields!";
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

                    <button type="submit" name="submit">Sign Up</button>

                    <input type="submit" class="btn btn-default" value="Register">

                </form>
            </div>
        </div>
    </div>
</div>

<?php //include "includes/footer.php"; ?>