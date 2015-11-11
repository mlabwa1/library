<?php
	session_start();
	include("../includes/dbcon.php");
	if(!($_SESSION['login'])) {
		header("Location: sign");
	}
	else
	{
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrator | Add User</title>

    <!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body background="">
	<div class="container">
		<div class="container-fluid">
			<?php include 'includes/nav-bar.php';?>
			
			<div class="row">
				<div class="panel panel-default">
				  <div class="panel-heading">
					<h3 class="panel-title">Add User</h3>
				  </div>
				  <div class="panel-body">
							<form method="post" data-toggle="validator" role="form">
							  <div class="form-group">
								<label for="userID">Username</label>
								<input type="text" pattern="[a-zA-Z0-9]{2,35}" class="form-control" name="username" id="username" placeholder="Enter Username" 
								value="<?php
									if(isset($_POST['sign'])){
										echo $_POST['username'];
									}else{
										echo "";
									}
								?>" required>							
							  </div>			
							  <div class="form-group">
								<label for="userNumber">ID Number</label>
								<input type="text" pattern="[0-9]{9}"class="form-control" name="userNumber" id="userNumber" placeholder="Enter 9 Digits ID Number" 
								value="<?php
									if(isset($_POST['sign'])){
										echo $_POST['userNumber'];
									}else{
										echo "";
									}
								?>" required>							
							  </div>					
							  <div class="form-group" >
								<label for="firstName">First Name</label>
								<input type="text" pattern="[a-zA-Z]{2,35}" class="form-control" name="firstName" id="firstName" placeholder="Enter First Name" 
								value="<?php
									if(isset($_POST['sign'])){
										echo $_POST['firstName'];
									}else{
										echo "";
									}
								?>" required>
							  </div>
							  <div class="form-group">
								<label for="middleName">Middle Name</label>
								<input type="text" pattern="[a-zA-Z]{2,35}" class="form-control" name="middleName" id="middleName" placeholder="Enter Middle Name"
								value="<?php
									if(isset($_POST['sign'])){
										echo $_POST['middleName'];
									}else{
										echo "";
									}
								?>">						
							  </div>
							  <div class="form-group">
								<label for="lastname">Last Name</label>
								<input type="text" type="text" pattern="[a-zA-Z]{2,35}" class="form-control" name="lastName" id="lastName" placeholder="Enter Last Name" 
								value="<?php
									if(isset($_POST['sign'])){
										echo $_POST['lastName'];
									}else{
										echo "";
									}
								?>"required>
							  </div>						  
							  <!-- <div class="form-group">
								<label for="password">Password</label>
								<input type="password" type="text" pattern="^([_A-z]){6,}" class="form-control" name="password" id="password" placeholder="Enter Password" required>
							  </div> -->
							  <div class="form-group">
								<label for="role">Role</label>
								<select class="form-control" id="role" name="roleID" placeholder="Role">
									<option value="2">Librarian</option>
									<option value="3">Faculty</option>	
									<option value="4">Student</option>	
								</select>
								<!--<span class="help-block with-errors">Call Number</span>-->
							  </div>
							<div class="pull-right">							  
							  <button type="submit" name="sign" class="btn btn-primary">Add User</button>
							  <button type="reset" class="btn">Cancel</button>
							</div>
							</form>
				  </div>
				</div>			
			</div>			
		</div> <!-- /.container fluid -->
	</div>

	<?php
		if(isset($_POST['sign'])){
			$username = $_POST['username'];

			$query = mysql_query("SELECT username FROM users WHERE username='$username'");
			$validate = mysql_num_rows($query);
			if($validate>=1)
			{
				echo "<script>alert (\"Username ($username) is already taken\")</script>";
			}
			else
			{
				$username = $_POST['username'];
				$userNumber = $_POST['userNumber'];
				$firstName = $_POST['firstName'];
				$middleName = $_POST['middleName'];
				$lastName = $_POST['lastName'];
				$role = $_POST['roleID'];
				$password = 'laboratory';

				$userQuery = mysql_query("INSERT INTO users(number,firstName,middleName,lastName,username,password,roleId) 
					VALUES('$userNumber','$firstName','$middleName','$lastName','$username','$password','$role')") or die(mysql_error()."Can't insert.");

				echo "<script>alert (\"User ($firstName $lastName) is successfully added \")</script>";
				header("Refresh:0; url=users");
			}

		}
	?>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/filter-table.js"></script>
	<script src="../js/validator.min.js"></script>
	<script src="../js/bootstrap.js"></script>
  </body>
</html>
</html>
<?php
}
?>