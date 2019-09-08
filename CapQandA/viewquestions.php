<?php
session_start();
include_once 'dbconnect.php';

if (!isset($_SESSION['userSession'])) {
    header("Location: index.php");
}

$query = $DBcon->query("SELECT * FROM users WHERE user_id=".$_SESSION['userSession']);
$userRow=$query->fetch_array();
$DBcon->close();

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
            
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>


            <a class="navbar-brand" >Cap Q&A</a>



        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li ><a href="home.php">Home</a></li>
            <li ><a href="categories.php">Categories</a></li>
            <li ><a href="askquestion.php">Ask a Question</a></li>
            <li class="active"><a href="viewquestions.php">View All Questions</a></li>
            <li ><a href="help.php">Help</a></li>
            
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#"><span class="glyphicon glyphicon-user"></span>&nbsp; <?php echo $userRow['username']; ?></a></li>
              <li><a href="admin_home.php"><span class="glyphicon glyphicon-sunglasses"></span>&nbsp; Admin Access</a></li>
              <li><a href="logout.php?logout"><span class="glyphicon glyphicon-log-out"></span>&nbsp; Logout</a></li>

          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

<div class="container">

    <br />
    <br />

    <br />
    

    <div class="row">

    
        <?php

if(isset($_GET['sub_category'])) {
    $selected_subcat = $_GET['sub_category'];

    $stmt = $DB_con->prepare("SELECT question_id, question_name, question_description, question_category, sub_category FROM questions where sub_category = '$selected_subcat' and question_id NOT IN(select question_id from question_moderation where approval_status = 'Denied') ORDER BY question_id ASC ");
        $stmt->execute();
}
else
{
    $stmt = $DB_con->prepare("SELECT question_id, question_name, question_description, question_category, sub_category FROM questions where question_id NOT IN(select question_id from question_moderation where approval_status = 'Denied') ORDER BY question_id ASC ");
        $stmt->execute();
    $selected_subcat = 'All Questions';
}
?>
<h2 class="form-signin-heading"><center><?php echo $selected_subcat; ?></center></h2><hr />
       
<?php
        if($stmt->rowCount() > 0)
        {
            while($row=$stmt->fetch(PDO::FETCH_ASSOC))
            {
                extract($row);
                ?>
                <div class="col-xs-12">
                    
                    <p class="page-header"><?php echo $question_name."&nbsp;-&nbsp;".$sub_category; ?></p>
                    <p class="question-bottom"><?php //$question_description = substr($question_description, 0, 100); echo $question_description."...&nbsp;&nbsp;"?></p>
                    <p class="page-header">
				<span>
				<a class="btn btn-default" href="viewquestiondetail.php?question_id=<?php echo $row['question_id']; ?>" title="View Question" "><span class="glyphicon glyphicon-eye-open"></span>  View Question</a>
				</span>
                    </p>
                </div>
                <?php
            }
        }
        else
        {
            ?>
            <div class="col-xs-12">
                <div class="alert alert-warning">
                    <span class="glyphicon glyphicon-info-sign"></span> &nbsp; No Questions Found
                </div>
            </div>
            <?php
        }

        ?>
    </div>


    


</div>


<!-- Latest compiled and minified JavaScript -->
<script src="bootstrap/js/bootstrap.min.js"></script>


</body>
</html>