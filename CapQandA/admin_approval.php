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
                <li><a href="home.php">Home</a></li>
                <li><a href="admin_home.php">Admin Home</a></li>
                <li class="active"><a href="admin_approval.php">Admin Approval</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a href="#"><span class="glyphicon glyphicon-user"></span>&nbsp; <?php echo $userRow['username']; ?></a></li>
                <li><a href="logout.php?logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp; Logout</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>






<br />
<br />

<br />


<div class="container">

    <br />
    <br />

    <br />


    <?php
    if(isset($_GET['question_id'])) {
        $selected_id = $_GET['question_id'];
//Gets Request ID from URL
    }
    else
    {
        header("Location: index.php");
        //sends user back to login/homepage if no Request ID
    }
    $stmt = $DB_con->prepare("select question_id, question_name, question_description, user_name from questions where question_id ='$selected_id'");
    $stmt->execute();

    if($stmt->rowCount() > 0)
    {
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)) //loops through sql result
        {
            extract($row);
            ?>

            <table class="table table-bordered">
                <thead>
                <tr>
                        <th>Question Title</th>
                        <th>Question Description</th>
                        <th>Username</th>
                        


                </tr>
                </thead>
                <tbody>
                <tr>
                        <td><?php echo $question_name ?></td>
                        <td><?php echo $question_description ?></td>
                        <td><?php echo $user_name ?></td>


                </tr>




                </tbody>


            </table>





            <?php
        }
    }
    else
    {
        header("Location: index.php");
        
    }



    if(isset($_POST['btn_approve'])) { //if approve button is clicked, update database

        if(isset($_GET['question_id'])) {
            $selected_id = $_GET['question_id'];

        }
       // $request_reason = ($_POST['request_reason']);
        //$request_reason = $DBcon->real_escape_string($request_reason);
        //$query = "UPDATE item_requests SET approval_status = 'Approved', approval_comment = '$request_reason' where request_id = '$selected_id'";

       $query = "INSERT INTO question_moderation VALUES ($selected_id, 'Approved')";

        if ($DBcon->query($query)) {
            $msg = "<div class='alert alert-success'>
        <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Request Approved Successfully </div>";
        echo $msg;
        header("refresh:2;url=admin_home.php");//Refreshes page to reflect new approval status

        }else {
            $msg = "<div class='alert alert-danger'>
        <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Request Approval Error </div>";

            echo $msg;

        }



    }

    if(isset($_POST['btn_deny'])) { //if deny button is clicked, update database

        if(isset($_GET['question_id'])) {
            $selected_id = $_GET['question_id'];

        }
       // $request_reason = ($_POST['request_reason']);
        //$request_reason = $DBcon->real_escape_string($request_reason);
        //$query = "UPDATE item_requests SET approval_status = 'Approved', approval_comment = '$request_reason' where request_id = '$selected_id'";
       $query = "INSERT INTO question_moderation VALUES ($selected_id, 'Denied')";

        if ($DBcon->query($query)) {
            $msg = "<div class='alert alert-success'>
        <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Request Denied Successfully </div>";
        echo $msg;
        header("refresh:2;url=admin_home.php");//Refreshes page to reflect new approval status
         
        }else {
            $msg = "<div class='alert alert-danger'>
        <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Request Denial Error </div>";

            echo $msg;

        }




    }
    $DBcon->close();



    ?>




    <form class="form-request" method="post" id="register-form" enctype="multipart/form-data">



        

        <br />




        <button type="submit" name="btn_approve" class="btn btn-default">
            <span class="glyphicon glyphicon-ok"></span> Approve
        </button>

        <button type="submit" name="btn_deny" class="btn btn-default">
            <span class="glyphicon glyphicon-remove"></span> Deny
        </button>

        <a class="btn btn-default" href="admin_home.php"> <span class="glyphicon glyphicon-backward"></span> Cancel </a>


    </form>

</div>