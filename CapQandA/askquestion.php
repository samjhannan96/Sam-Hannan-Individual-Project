<?php

error_reporting( ~E_ALL);
session_start();
include_once 'dbconnect.php';
ini_set('display_errors', '1');
//if(function_exists('xdebug_disable')) { xdebug_disable(); }


if (!isset($_SESSION['userSession'])) {
    header("Location: index.php");
}

$query = $DBcon->query("SELECT * FROM users WHERE user_id=".$_SESSION['userSession']);
$userRow=$query->fetch_array();

$active_user = $userRow['username'];


if(isset($_POST['btn-submit-found'])) {

    $question_name = strip_tags($_POST['question_name']);
    $question_description = strip_tags($_POST['question_description']);
    
    $ques_category = strip_tags($_POST['category']);
    $sub_category = strip_tags($_POST['subcat']);
  



   $question_name = $DBcon->real_escape_string($question_name);
   $question_description = $DBcon->real_escape_string($question_description);
   
   
    

    $imgFile = $_FILES['item_photo']['name'];
    $tmp_dir = $_FILES['item_photo']['tmp_name'];
    $imgSize = $_FILES['item_photo']['size'];

//metadata from the image is stripped, to allow checking against size constraints


$upload_dir = 'question_images/'; // upload directory

$imgExt = strtolower(pathinfo($imgFile,PATHINFO_EXTENSION)); // gets image extension to check validity

// valid image extensions
$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); // list of valid extensions

// rename uploading image
$ques_image = time().".".$active_user.".".$imgExt; //file is renamed using the current time (seconds since Unix epoch + username) to ensure it is unique

// allow valid image file formats
if(in_array($imgExt, $valid_extensions)){
    // Check file size less than 5 megabytes
    if($imgSize < 5000000)				{
        move_uploaded_file($tmp_dir,$upload_dir.$ques_image);
    }
    else{
        $errMSG = "Sorry, your file is larger than 5MB. Please upload a smaller file.";
    }
}
else {
    $errMSG = "Sorry, only JPG, JPEG, PNG & GIF file extensions are permitted."; //informs the user if they upload an incorrect filetype

}

    if(!isset($errMSG)){

        $query = "INSERT INTO questions(question_name, question_description, question_category, sub_category, question_image,  user_name) 
                  VALUES('$question_name','$question_description', '$ques_category', '$sub_category', '$ques_image', '$active_user')";}

    //if no error message has been returned, the data from the form is inserted into the questions table.


        if ($DBcon->query($query)) { //if the query is successful, show a success message to the user
            $msg = "<div class='alert alert-success'>
						<span class='glyphicon glyphicon-info-sign'></span> &nbsp; Submission Successful
					</div>";
        }else { //else show a submission error
            $msg = "<div class='alert alert-danger'>
						<span class='glyphicon glyphicon-info-sign'></span> &nbsp; Submission Error
					</div>";
    }

    $DBcon->close();
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
            <li class="active"><a href="askquestion.php">Ask a Question</a></li>
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

<?php
if(isset($errMSG)){
    ?>
    <div class="alert alert-danger">
        <span class="glyphicon glyphicon-info-sign"></span> <strong><?php echo $errMSG; ?></strong>
    </div>
    <?php
}
else if(isset($successMSG)){
    ?>
    <div class="alert alert-success">
        <strong><span class="glyphicon glyphicon-info-sign"></span> c</strong>
    </div>
    <?php
}
?>


<div class="signin-form">

    <div class="container">


        <form class="form-signin" method="post" id="register-form" enctype="multipart/form-data">

            <h2 class="form-signin-heading">Ask a Question</h2><hr />

            <?php
            if (isset($msg)) {
                echo $msg;
            }
            ?>



            <div class="form-group">
                <label for="qname">Question Title:</label>
                <textarea class="form-control" rows="1" maxlength = "200" id="question_name" name="question_name"required></textarea>
            </div>
         
            
            <div class="form-group">
                <label for="qdescription">Question Description:</label>
                <textarea class="form-control" maxlength = "500" rows="5" id="question_description" name="question_description" required></textarea>
            </div>


            <div class="form-group">
                <label for="itemcategory">Question Category:</label>

            </div>

            <div class="radio">
                <label><input type="radio" name="category" value="Oracle" id="category2" required>Oracle</label>
            </div>
            <div class="radio">
                <label><input type="radio" name="category" value="Microsoft" id="category1" required>Microsoft</label>
            </div>
            <div class="radio">
                <label><input type="radio" name="category" value="SAP" id="category3" required>SAP </label>
            </div>
            <div class="radio">
                <label><input type="radio" name="category" value="Salesforce" id="category4" required>Salesforce</label>
            </div>

           

            
            <div id = "Oracle" class="desc">
            <label for="desc">Subcategory:</label>
            <div id="Oracle" class="descs">
             <label><input type="radio" name="subcat" value="Oracle Databases" required> Oracle Databases </label>
            </div>

            <div id="Oracle" class="descs">
             <label><input type="radio" name="subcat" value="Oracle Business Intelligence Enterprise Edition (OBIEE)" required> Oracle Business Intelligence Enterprise Edition (OBIEE)</label>
            </div>

            <div id="Oracle" class="descs">
             <label><input type="radio" name="subcat" value="Oracle BI Publisher" required> Oracle BI Publisher</label>
            </div>

            <div id="Oracle" class="descs">
             <label><input type="radio" name="subcat" value="Oracle E-Business Suite (EBS)" required> Oracle E-Business Suite (EBS)</label>
            </div>

            <div id="Oracle" class="descs">
             <label><input type="radio" name="subcat" value="Oracle SOA Suite" required> Oracle SOA Suite</label>
            </div>

            <div id="Oracle" class="descs">
             <label><input type="radio" name="subcat" value="Oracle APEX" required> Oracle APEX</label>
            </div>

            <div id="Oracle" class="descs">
             <label><input type="radio" name="subcat" value="Oracle JET" required> Oracle JET</label>
            </div>

            <div id="Oracle" class="descs">
             <label><input type="radio" name="subcat" value="Oracle PLSQL" required> Oracle PLSQL</label>
            
            </div>

            <div id="Oracle" class="descs">
             <label><input type="radio" name="subcat" value="Oracle ADF" required> Oracle ADF</label>
            </div>
            </div>


            <div id = "Microsoft" class="desc1">
            <label for="desc1">Subcategory:</label>
            <div id="Microsoft" class="descs">
             <label><input type="radio" name="subcat" value="Microsoft Azure" required> Microsoft Azure</label>
            </div>

            <div id="Microsoft" class="descs">
             <label><input type="radio" name="subcat" value="Microsoft SQL Server" required> Microsoft SQL Server</label>
            </div>

            <div id="Microsoft" class="descs">
             <label><input type="radio" name="subcat" value="Microsoft Active Directory" required> Microsoft SSIS</label>
            </div>

            
            </div>

            <div id = "SAP" class="desc2">
            <label for="desc2">Subcategory:</label>
            <div id="SAP" class="descs">
             <label><input type="radio" name="subcat" value="SAP HANA" required> SAP HANA</label>
            </div>

            <div id="SAP" class="descs">
             <label><input type="radio" name="subcat" value="SAP Concur" required> SAP Concur</label>
            </div>

            <div id="SAP" class="descs">
             <label><input type="radio" name="subcat" value="SAP Leonardo" required> SAP Leonardo</label>
            </div>

            
            </div>

            <div id = "Salesforce" class="desc3">
            <label for="desc3">Subcategory:</label>
            <div id="Salesforce" class="descs">
             <label><input type="radio" name="subcat" value="Salesforce Sales" required> Salesforce Sales</label>
            </div>

            <div id="Salesforce" class="descs">
             <label><input type="radio" name="subcat" value="Salesforce Commerce" required> Salesforce Commerce</label>
            </div>

            <div id="Salesforce" class="descs">
             <label><input type="radio" name="subcat" value="Salesforce Analytics" required> Salesforce Analytics</label>
            </div>

            
            </div>


            <script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.js'></script>
            <script type="text/javascript">
            $(document).ready(function() {
            $("div.desc").hide();
            $("input[name$='category']").click(function() {
             var slctd = $(this).val();
            $("div.desc").hide();
            $("#" + slctd).show();
            });
            });
            </script>

            <script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.js'></script>
            <script type="text/javascript">
            $(document).ready(function() {
            $("div.desc1").hide();
            $("input[name$='category']").click(function() {
             var slctd = $(this).val();
            $("div.desc1").hide();
            $("#" + slctd).show();
            });
            });
            </script>

            <script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.js'></script>
            <script type="text/javascript">
            $(document).ready(function() {
            $("div.desc2").hide();
            $("input[name$='category']").click(function() {
             var slctd = $(this).val();
            $("div.desc2").hide();
            $("#" + slctd).show();
            });
            });
            </script>

            <script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.js'></script>
            <script type="text/javascript">
            $(document).ready(function() {
            $("div.desc3").hide();
            $("input[name$='category']").click(function() {
             var slctd = $(this).val();
            $("div.desc3").hide();
            $("#" + slctd).show();
            });
            });
            </script>

            <div class="form-group">
                <label for="item_photo">Upload File (image files only): </label>
                <input type="file" class="form-control" placeholder="Image files only" name="item_photo" required  />

            </div>

            




            <hr />

            <div class="form-group">
                <button type="submit" class="btn btn-default" name="btn-submit-found">
                    <span class="glyphicon glyphicon-log-in"></span> &nbsp; Submit Question
                </button>
            </div>



        </form>

    </div>

</div>







</body>
</html>