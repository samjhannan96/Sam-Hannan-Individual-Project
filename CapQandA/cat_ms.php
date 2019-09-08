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
            <li class="active"><a href="categories.php">Categories</a></li>
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

            <h2 class="form-signin-heading"><center>Microsoft</center></h2><hr />


            
           
            <br>
         
            <span class = "catbuttons">

                
				<a class="btn btn-default" style="height:60px; width:200px; margin: 20px; font-size: 25px;"  href="viewquestions.php?sub_category=<?php echo "Microsoft Azure"; ?>" ">Microsoft Azure</a>
				
				<a class="btn btn-default" style="height:60px; width:200px; margin: 20px; font-size: 21px;"  href="viewquestions.php?sub_category=<?php echo "Microsoft SQL Server"; ?>" ">Microsoft SQL Server</a>
                
                <a class="btn btn-default" style="height:60px; width:200px; margin: 20px; font-size: 25px;"  href="viewquestions.php?sub_category=<?php echo "Microsoft SSIS"; ?>" ">Microsoft SSIS</a>
                
				

            <span>

                
                
        </form>

    </div>


    





</div>


    



        











        </form>

    </div>

</div>

</body>
</html>