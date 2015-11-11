<?php
	session_start();
	include("includes/dbcon.php");
?>
<!DOCTYPE html>
<html class="full" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Library System</title>

    <!-- Bootstrap -->
    <script src="js/jquery-1.11.1.min.js"></script>
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
	<!-- Custom CSS -->
    <link href="css/full.css" rel="stylesheet">
  </head>

  <body>
	<nav class="navbar navbar-default">
	  <div class="container-fluid">
		<div class="navbar-header">
		  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		  </button>
		  <a class="navbar-brand" href="index">Library System</a>
		</div>

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		  <form class="navbar-form navbar-left" role="search" method="post" action="">
			<div class="form-group">
			  <input type="text" name="keyWord" class="form-control" placeholder="Search" value="<?php if(isset($_POST['searchBtn'])){ echo $_POST['keyWord']; }else{ echo "";}?>">
			  <input type="radio" name="filter" value="title" checked="checked"> By Title
			  <input type="radio" name="filter" value="author"  <?php if(isset($_POST['filter'])){ if($_POST['filter']=="author"){ echo "checked=\"checked\""; }}?>> By Author
			</div>
			<button type="submit" name="searchBtn" class="btn btn-default" href="#collapseResult" aria-expanded="false" aria-controls="collapseResult"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
		  </form>
		  <ul class="nav navbar-nav navbar-right">
			<li><a href="sign">Sign In</a></li>
		  </ul>
		</div><!-- /.navbar-collapse -->
	  </div><!-- /.container-fluid -->
	</nav>
	
	<?php
			include("search.php");
			if(isset($_GET['bookID'])){
				header("Location: sign");
				$_SESSION['msg'] = "You have to login first";
			}
	?>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>