<?php
session_start();
include_once 'dbconnect.php';

if (!isset($_SESSION['userSession'])) {
    header("Location: index.php");
}

$query = $DBcon->query("SELECT * FROM users WHERE user_id=".$_SESSION['userSession']);
$userRow=$query->fetch_array();
//$DBcon->close();

$active_user = $userRow['username'];

if ($active_user != 'Admin'){

    header("Location: index.php");

}




?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Welcome - <?php echo $userRow['username']; ?></title>

    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen">

    <link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>


            <a class="navbar-brand" >Cap Q&A</a>



        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
            <li class="active"><a href="admin_home.php">Admin Home</a></li>
            <li> <a href="home.php">Home</a></li>
            <li><a href="categories.php">Categories</a></li>
            <li><a href="askquestion.php">Ask a Question</a></li>
            <li><a href="viewquestions.php">View All Questions</a></li>
            <li><a href="help.php">Help</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><span class="glyphicon glyphicon-user"></span>&nbsp; <?php echo $userRow['username']; ?></a></li>
                <li><a href="admin_home.php"><span class="glyphicon glyphicon-sunglasses"></span>&nbsp; Admin Access</a></li>
                <li><a href="logout.php?logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp; Logout</a></li>

            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="signin-form">

    <div class="container">


        <form class="form-signin" method="post" id="register-form" enctype="multipart/form-data">

            <h2 class="form-signin-heading">Cap Q&A</h2><hr />


            <h3>Welcome to Cap Q&A - Admin Home</h3>
            <h3>Below, you can approve/deny any questions that have been submitted.</h3>
            <br>


        </form>

    </div>



    <div class="container">

        <br />
        <br />

        <br />


        <?php


        //$stmt = $DB_con->prepare("select a.request_id, a.item_id, a.request_reason, a.user_name, a.approval_status, b.question_name from item_requests a JOIN questions b on a.item_id = b.question_id where a.approval_status = 'Awaiting Approval'");
        $stmt = $DB_con->prepare("select question_id, question_name, question_description, user_name from questions where question_id NOT IN(select question_id from question_moderation) order by 1 desc");
        $stmt->execute();

        if($stmt->rowCount() > 0)
        {
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                extract($row);
                ?>


                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th width="25%">Question Title</th>
                        <th width="25%">Question Description</th>
                        <th width="25%">Username</th>
                        <th width="25%">Approve/Deny?</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td><?php echo $question_name ?></td>
                        <td><?php echo $question_description ?></td>
                        <td><?php echo $user_name ?></td>

                        <td><a class="btn btn-default" href="admin_approval.php?question_id=<?php echo $row['question_id']; ?>" title="Approve/Deny" "><span class="glyphicon glyphicon-info-sign"></span>  Approve/Deny</a></td>


                        </td>

                    </tr>

                    </tbody>


                </table>


                <?php
            }
        }
        else
        {
            ?>
            <div class="col-xs-12">
                <div class="alert alert-warning">
                    <span class="glyphicon glyphicon-info-sign"></span> &nbsp; No Outstanding Approval Requests
                </div>
            </div>
            <?php
        }



        ?>











        </form>

    </div>

</div>

</body>
</html>