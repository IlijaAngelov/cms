<?php session_start(); ?>

<?php

$_SESSION['username'] = null;
$_SESSION['firstName'] = null;
$_SESSION['lastName'] = null;
$_SESSION['user_role'] = null;
$_SESSION['user_id'] = null;
$_SESSION['user_image'] = null;
$_SESSION['user_email'] = null;
//session_unset();
//session_destroy();

header("Location: ../index.php");
exit();
?>