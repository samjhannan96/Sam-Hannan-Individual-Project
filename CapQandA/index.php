<?php
session_start();
require_once 'dbconnect.php';

if (isset($_SESSION['userSession'])!="") {
    header("Location: home.php");
    exit;
}

if (isset($_POST['btn-login'])) {

    $username = strip_tags($_POST['username']); //the user's email is returned from the form, with strip_tags used as an added security measure
    $password = strip_tags($_POST['password']);

    $username = $DBcon->real_escape_string($username);
    $password = $DBcon->real_escape_string($password); //real_escape_string escapes special characters in a string for use in an SQL statement
    $query = $DBcon->query("SELECT user_id, email, password FROM users WHERE username='$username'"); //the DB query is prepared
    $row=$query->fetch_array();

    $count = $query->num_rows; // if email/password are correct returns must be 1 row

    if (password_verify($password, $row['password']) && $count==1) {
    $_SESSION['userSession'] = $row['user_id'];
    header("Location: home.php");
    } else {
        $msg = "<div class='alert alert-danger'>
					<span class='glyphicon glyphicon-info-sign'></span> &nbsp; Invalid Username or Password
				</div>";
    }
    $DBcon->close();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Cap Q&A</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>

<div class="signin-form">

    <div class="container">


        <form class="form-signin" method="post" id="login-form">

            <h2 class="form-signin-heading">Sign In</h2><hr />

            <?php
            if(isset($msg)){
                echo $msg;
            }
            ?>

            <div class="form-group">
                <input type="text" class="form-control" placeholder="Username" name="username" required />
             
            </div>

            <div class="form-group">
                <input type="password" class="form-control" placeholder="Password" name="password" required />
            </div>

            <hr />

            <div class="form-group">
                <button type="submit" class="btn btn-default" name="btn-login" id="btn-login">
                    <span class="glyphicon glyphicon-log-in"></span> &nbsp; Sign In
                </button>

                <a href="register.php" class="btn btn-default"> <span class="glyphicon glyphicon-flash"></span> Sign Up</a>


            </div>



        </form>

    </div>

</div>

</body>
</html><?php


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Cap Q&A</title>
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="style.css" type="text/css" />
</head>

</html>