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
            <li ><a href="categories.php">Categories</a></li>
            <li ><a href="askquestion.php">Ask a Question</a></li>
            <li><a href="viewquestions.php">View All Questions</a></li>
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

    }
    else
    {
        header("Location: index.php");
    }
    $stmt = $DB_con->prepare("SELECT question_id, question_name, question_description, question_category, sub_category, question_image FROM questions where question_id = '$selected_id' ORDER BY question_id ASC limit 1;");
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
                    <th width="15%">Question Title</th>
                    <th>Question Description</th>
                    <th>Question Category</th>
                    <th>Question Subcategory</th>
                    <th>Question Screenshot
                    (Click for full size) </th>
                   
                  
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo $question_name ?></td>
                    <td><?php echo $question_description ?></td>
                    
                    <td><?php echo $question_category ?></td>
                    <td><?php echo $sub_category ?></td>
                    <td> <a href="question_images/<?php echo $question_image?>"> <img src= "question_images/<?php echo $question_image?>"width="270px" height="270px"></a> </td>
                    

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
                <span class="glyphicon glyphicon-info-sign"></span> &nbsp; No Data Found ...
            </div>
        </div>
        <?php
    }

    $stmt = $DB_con->prepare("SELECT question_id, answer_id, answer_description, user_name FROM answers where question_id = '$selected_id' ORDER BY question_id ASC");
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
                    <th width="20%">User Name</th>
                    <th>Suggested Answer</th>
                    
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?php echo $user_name ?></td>
                    <td><?php echo $answer_description ?></td>
                    



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
                <span class="glyphicon glyphicon-info-sign"></span> &nbsp; No Answers Submitted Yet
            </div>
        </div>
        <?php
    }


    if(isset($_POST['btn_request'])) {



    $request_reason = ($_POST['request_reason']);
    $request_reason = $DBcon->real_escape_string($request_reason);

    $query = "INSERT INTO answers(question_id, answer_description, user_name) VALUES('$selected_id','$request_reason', '$active_user')";

    if ($DBcon->query($query)) {
        $msg = "<div class='alert alert-success'>
            <span class='glyphicon glyphicon-info-sign'></span> &nbsp; Answer Submitted Successfully </div>";
    
        echo $msg;
        echo "<meta http-equiv='refresh' content='0.5'>";

    
        }else {
        $msg = "<div class='alert alert-danger'>
            <span class='glyphicon glyphicon-info-sign'></span> &nbsp; A Submission Error </div>";
    
        echo $msg;
    
        }
    
    
    
        }
        $DBcon->close();



    ?>




    <form class="form-request" method="post" id="register-form" enctype="multipart/form-data">

    <div class="form-group-request">
        <label for="request_reason">Your Answer:</label>
        <textarea class="form-control" rows="5" id="request_reason" name="request_reason" required></textarea>
    </div>

    <br />

    <button type="submit" name="btn_request" class="btn btn-default" >
        <span class="glyphicon glyphicon-save"></span> Submit
    </button>

    <a class="btn btn-default" onClick="history.go(-1);"> <span class="glyphicon glyphicon-backward"></span> Return to Questions </a>

   

     



    </form>
    &nbsp;&nbsp;

</div>