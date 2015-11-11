<?php
 	session_start();
	include("includes/dbcon.php");
	
	if(!($_SESSION['login']) && $_SESSION['role']!="Student/Faculty") {
		header("Location: index.php");
	}
	else {
		$username  = $_SESSION['username'];
		$sql = mysql_query("SELECT * FROM users WHERE username='$username'")
					or die(mysql_error()) ;			   
			while($row = mysql_fetch_array($sql))
			{
				$firstName = $row['firstName'];
				$middleName = $row['middleName'];
				$lastName = $row['lastName'];		
				$userID = $row['userId'];
			}
 	}
?>

<!DOCTYPE html>
<html class="" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Library System</title>

    <!-- Bootstrap -->
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
  				<?php 
  					if(isset($_GET['bookID'])){

  						$checkUserQuery = mysql_query("SELECT * FROM borrowed_books WHERE userId=".$userID." and status=1");

  						if(mysql_num_rows($checkUserQuery)>=4){
  							echo "<h4 class=\"help-block\" style=\"color:red\" align=\"center\">You may only borrow 4 books at a time</h4>";
  						}else{

  						$checkBook = mysql_query("SELECT * FROM books WHERE bookId=".$_GET['bookID']);

  						$available = 0;
  						$out = 0;
  			
  						while($row=mysql_fetch_array($checkBook)){
  							$available = $row['totalOfBooks'];
  							$out = $row['noOfBookOut'];
  						}

  						if($available==$out){
  							echo "<h4 class=\"help-block\" style=\"color:red\" align=\"center\">All copies are out</h4>";
  						}else{

  	  					$dateToday = date('Y-m-d');
  						$dateToday = strtotime($dateToday);
  						$dateToday = strtotime("+7 weekday", $dateToday);
  						$dateToBeReturned = date('Y-m-d', $dateToday);
  						$dateToday = date('Y-m-d');

  				  		$insertQuery = "INSERT INTO borrowed_books (bookId, userId, dateBorrowed, dateToBeReturned, status)
  										VALUES (".$_GET['bookID'].",".$userID.",'".$dateToday."','".$dateToBeReturned."',1)";

  						mysql_query($insertQuery) or die(mysql_error());

  						$updateBookQuery = "UPDATE books SET noOfBookIn = noOfBookIn - 1, noOfBookOut = noOfBookOut + 1 WHERE bookId=".$_GET['bookID'];
  						mysql_query($updateBookQuery) or die(mysql_error());

  						unset($_GET['bookID']);
  						header("Location: profile.php");
  					}
  					}
  					} 
  				?>
	
					<nav class="navbar navbar-default">

				  <div class="container-fluid">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
					  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					  </button>
					  <a class="navbar-brand" href="#">Library System</a>
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
						<li><a>Hello, <?php echo $firstName." ".$middleName." ".$lastName; ?></a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-cog"></span> Settings <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
							  <li><a href="changePassword"><span class="glyphicon glyphicon-lock"></span> Change Password</a></li>							
							  <li><a href="includes/logout"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
							</ul>
						</li>						</div><!-- /.navbar-collapse -->
				  </div><!-- /.container-fluid -->
				</nav>
  
	<div class="container">
		<?php
			include("search.php");
		?>
		<div class="container-fluid">

			<div class="row col-lg-8">
				<div class="panel panel-primary">
				  <!-- Default panel contents -->
				  <div class="panel-heading">Borrowed Books</div>

				  <!-- Table -->
				<?php
					$sql = mysql_query("SELECT * FROM borrowed_books a
							JOIN books b ON b.bookID = a.bookID
							JOIN book_author c ON c.bookID = a.bookID
							JOIN authors d ON c.authorID = d.authorID
							WHERE a.userID =$userID")
							or die(mysql_error());	
					if(mysql_num_rows($sql)>0){

				?>
				<table class="table">

					<thead>
						<tr>
							<th>Call Number</th>						
							<th>Title</th>
							<th>Author</th>
							<th>Date Borrowed</th>
							<th>Return Due Date</th>	
							<th>Status</th>	
						</tr>
					</thead>
					<tbody class="searchable">
						<?php
							while($row = mysql_fetch_array($sql)){
								echo "<tr>";
								echo "<td>".$row['callNumber']."</td>";
								echo "<td>".$row['title']."</td>";
								echo "<td>".$row['authorName']."</td>";
								$date1 = date_create($row['dateBorrowed']);
								$date2 = date_create($row['dateToBeReturned']);
								echo "<td>".date_format($date1, 'M j, Y')."</td>";
								echo "<td>".date_format($date2, 'M j, Y')."</td>";
								if($row['status']==1){
									if(strtotime($row['dateToBeReturned']) < time()){ 
										echo "<td><button class=\"btn btn-danger\" type=\"submit\">OVERDUE</button></td>";
									}else{
										echo "<td><a class=\"btn btn-warning\">ON HAND</a>";
									}
								}else{
									echo "<td><label class=\"btn btn-success\">RETURNED</td>";
								}
							}
						?>					
					</tbody>
				</table>

				<?php 
					}else{
						echo "<tr><th colspan=\"6\"><center> No borrow history </th></tr>";
					}
				?>
				</div>
			</div>
			
            <div class="col-lg-4">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<i class="fa fa-bell fa-fw"></i> Book Overdue Notifications
					</div>
					<!-- /.panel-heading -->
					<div class="panel-body">
						<div class="list-group">
						<?php 
							$sql2 = mysql_query("SELECT * FROM borrowed_books a
									JOIN books b ON a.bookID = b.bookID
									WHERE a.userID = $userID 
									AND a.STATUS =1")
									or die(mysql_error());

							$total = 0;
							while($row = mysql_fetch_array($sql2)){
								$now = time();
								$dateToBeReturned = strtotime($row['dateToBeReturned']);
								$datediff = floor(($now - $dateToBeReturned)/(60*60*24));		
								if($datediff>=1){
									$penalty = $datediff * 5;
									$total+=$penalty;
									$updateQuery = "UPDATE borrowed_books SET penalty=$penalty, noOfDaysLate=$datediff WHERE borrowedBookID=".$row['borrowedBookId']." ";
									mysql_query($updateQuery) or die(mysql_error());
									echo "<a href=\"#\" class=\"list-group-item\">
										<i class=\"glyphicon glyphicon-book\"></i> ".$row['title'].
										"<span class=\"pull-right text-muted small glyphicon glyphicon-ruble\"><em>$penalty</em>
										</span>
										</a>";
								}

							}

							$sql3 = mysql_query("SELECT SUM(penalty) AS totalIncurred FROM borrowed_books WHERE userID=$userID")
									or die(mysql_error());

							while($row = mysql_fetch_array($sql3)){
								$totalIncurred = $row['totalIncurred'];
							}

						?>		

							<a href="#" class="list-group-item">
							Total Current Penalty:<span class="pull-right text-muted small glyphicon glyphicon-ruble"><em><?php echo $total; ?></em></span>
							</a>
							<a href="#" class="list-group-item">
							Total Incurred Penalty:<span class="pull-right text-muted small glyphicon glyphicon-ruble"><em><?php echo $totalIncurred; ?></em></span>
							</a>

						</div>
						<!-- /.list-group -->
					</div>
					<!-- /.panel-body -->
				</div>
				<!-- /.panel -->			
			</div>
		</div>
	</div>

	</div>

	</div>
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/filter-table.js"></script>
  </body>
</html>
<?php
/* } */
?>