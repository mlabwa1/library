<?php
 	session_start();
	include("../includes/dbcon.php");

	if(!($_SESSION['login']) && $_SESSION['role']!="Librarian") {
		header("Location: index");
	}
	else {
		$username  = $_SESSION['username'];
		$sql = mysql_query("SELECT userId, number FROM users WHERE username='$username'") or die(mysql_error()) ;			   
			while($row = mysql_fetch_array($sql))
			{				
				$userID = $row['userId'];
				$number = $row['number'];
			}

	}

	if(isset($_POST['btnSave'])){

		if($number!=$_POST['inputOldPass']){
			$msg = "The old password is incorrect. Please retype.";
		}else{
      if($number==$_POST['inputNewPass']){
        $msg = "You entered an old password";
      }else if(!preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}$/", $_POST['inputNewPass'])){
        $msg="Must contain at least one number and one uppercase and lowercase letter, and only 6-16 characters";
      }else if($_POST['inputNewPass']!=$_POST['inputNewPass2']){
        $msg="New password does not match the confirmation password. Please try again.";
      }else{
			 $changePassSql = "UPDATE users SET number='".$_POST['inputNewPass']."' WHERE userId=$userID";
			 mysql_query($changePassSql) or die(mysql_error());
			 $msg = "<font color=\"green\">Your password has been changed.</font>";
      }
		}
	}

  if(isset($_POST['btnCancel'])){
    header("Location: index");
  }

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Change Password</title>

    <!-- Bootstrap core CSS -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../css/signin.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="../js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>

  <body>

    <div class="">
      <form class="form-signin" role="form" data-toggle="validator" id="changePass" method="post">
        <h2 class="form-signin-heading" align="center">Change Password</h2>
		
		<!-- Error Message -->
		<h4 class="help-block" style="color:red" align="center"><?php if(isset($_POST['btnSave'])) { echo $msg; }?></h4>
		<!-- /. Error Message -->
		
        <label for="inputOldPass" class="sr-only">Old Password</label>
        <input type="password" id="inputOldPass" name="inputOldPass" class="form-control" placeholder="Old Password" required autofocus>
         <label for="inputNewPass2" class="sr-only">New Password</label>
        <input type="password" id="inputNewPass2" name="inputNewPass2" class="form-control" placeholder="New Password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}" onchange="form.pwd2.pattern = this.value;" title="Must contain at least one number and one uppercase and lowercase letter, and only 6-16 characters">
        <label for="inputNewPass" class="sr-only">Confirm New Password</label>
        <input type="password" name="inputNewPass" id="inputNewPass" class="form-control" placeholder="Confirm New Password" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,16}" title="Must contain at least one number and one uppercase and lowercase letter, and only 6-16 characters">
        <button class="btn btn-lg btn-primary" type="submit" name="btnSave">Save</button>
        <button class="btn btn-lg btn-primary" type="submit" name="btnCancel" formnovalidate>Back</button>

      </form>
    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
	
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/validator.min.js"></script>
	<script src="../js/bootstrap.js"></script>	
  </body>
</html>
