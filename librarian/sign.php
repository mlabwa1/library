<?php
 	session_start();
	include("../includes/dbcon.php");
	$login = 0;	

	if(isset($_POST['btnLogin'])) {
		
		$username = mysql_real_escape_string(trim($_POST['inputUsername'], "/\'\" \;"));
		$password = mysql_real_escape_string(trim($_POST['inputPassword'], "/\'\" \;"));
		$msg = '';

			$myQuery = mysql_query("SELECT * FROM users WHERE username='$username' AND number='$password'")
					   or die(mysql_error()) ;  // Check if user exists

				if(mysql_num_rows($myQuery)>0) {
				        $row = mysql_fetch_assoc($myQuery);
						$role = $row['roleId'];
						
						$myQuery2 = mysql_query("SELECT role FROM user_role WHERE roleId='$role'") or die(mysql_error()) ;  // check if user has admin privilege
				        $row2 = mysql_fetch_assoc($myQuery2);
						
						if($row['status']!=1){
							$_SESSION['login'] = $login;
							$msg = "Inactive Account!";
						}
						else {
								if($row2['role'] == 'Librarian')
								{
									$login = 1;
									$_SESSION['login'] = $login;
									$_SESSION['username'] = $username;
									$_SESSION['firstName'] = $firstName;
									$_SESSION['middleName'] = $middleName;
									$_SESSION['lastName'] = $lastName;
									$_SESSION['role'] = "Librarian";
									header("Location: index");
								}
								else {
									$_SESSION['login'] = $login;
									$msg = "Unauthorized Access! Not a Librarian!";
								}
						}
				}
				else {
					$_SESSION['login'] = $login;
					$msg = " Invalid username or password. ";
				}
			
			
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

    <title>Sign In</title>

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
      <form class="form-signin" role="form" data-toggle="validator" id="signIn" method="post">
        <h2 class="form-signin-heading" align="center">Please sign in</h2>
		
		<!-- Error Message -->
		<h4 class="help-block" style="color:red" align="center"><?php if(isset($_POST['btnLogin'])) { echo $msg;}?></h4>
		<!-- /. Error Message -->
		
        <label for="inputUsername" class="sr-only">Username</label>
        <input type="text" id="inputUsername" name="inputUsername" class="form-control" placeholder="Username" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="inputPassword" id="inputPassword" class="form-control" placeholder="Password" required>
        <button class="btn btn-lg btn-primary btn-block" type="submit" name="btnLogin">Sign in</button>
      </form>
    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="js/ie10-viewport-bug-workaround.js"></script>
	
	<script src="../js/bootstrap.min.js"></script>
	<script src="../js/validator.min.js"></script>
	<script src="../js/bootstrap.js"></script>	
  </body>
</html>
