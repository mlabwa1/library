<?php 
session_start();
include("../includes/dbcon.php");
$page = "dashboard"; 
if(isset($_SESSION['login']) && $_SESSION['role']=="Librarian") {
	$username  = $_SESSION['username'];
?>
<!DOCTYPE html>
<html class="" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Librarian</title>

    <!-- Bootstrap -->
    <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	
    <!-- DataTables CSS -->
    <link href="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
    <!-- DataTables Responsive CSS -->
    <link href="bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">	

	<!-- Custom CSS -->
    <link href="../css/full.css" rel="stylesheet">
  </head>
  <body>
	<div class="container">
		<div class="container-fluid">

			<!-- navigation bar -->
			<?php include 'includes/nav-bar.php';?>
			<!-- /.navigation bar -->

			<!-- borrowed books -->
			<div class="row">
				<div class="panel panel-success">
					<!-- panel-heading -->				
					<div class="panel-heading">
					  				<?php 
  					if(isset($_GET['borrowID'])){

  						$borrowSql = mysql_query("SELECT bookId FROM borrowed_books WHERE borrowedBookId=".$_GET['borrowID']);

  						while($row=mysql_fetch_array($borrowSql)){
  							$bookID = $row['bookId'];  	
  						}

  						$updateBookQuery = "UPDATE books SET noOfBookIn = noOfBookIn + 1, noOfBookOut = noOfBookOut - 1 WHERE bookId=".$bookID;
  						mysql_query($updateBookQuery) or die(mysql_error());

				
  						$updateBorrowQuery = "UPDATE borrowed_books SET dateReturned='".date('Y-m-d')."', status=0 WHERE borrowedBookId=".$_GET['borrowID'];
  						mysql_query($updateBorrowQuery) or die(mysql_error());

  						unset($_GET['borrowID']);
  						header("Location: index");
  					}
  				
  				?>
						<i class="glyphicon glyphicon-book"></i> Borrowed Books
					</div><!-- /.panel-heading -->
					
					<!-- panel body-->
					<div class="panel-body">
						<div class="dataTable_wrapper">

					<?php
					$sql = mysql_query("SELECT firstName, middleName, lastName, borrowedBookId, dateToBeReturned, callNumber, title, 
							dateBorrowed, dateToBeReturned, authorName, penalty FROM borrowed_books a
							JOIN books b ON b.bookID = a.bookID
							JOIN book_author c ON c.bookID = a.bookID
							JOIN authors d ON c.authorID = d.authorID
							JOIN users e ON a.userId = e.userId
							WHERE a.status=1")
							or die(mysql_error());	
					if(mysql_num_rows($sql)>0){
					?>
							<table class="table table-striped table-bordered table-hover" id="borrowedBooks">
								<thead>
									<tr>
										<th>Borrower</th>									
										<th>Book ID</th>
										<th>Title</th>
										<th>Author</th>
										<th>Date Borrowed</th>
										<th>Return Due</th>
										<th>Penalty (Peso)</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
						<?php
							while($row = mysql_fetch_array($sql)){
								$now = time();
  								$dateToBeReturned = strtotime($row['dateToBeReturned']);
								$datediff = floor(($now - $dateToBeReturned)/(60*60*24));		
								if($datediff>=1){
									$penalty = $datediff * 5;									
									$updateQuery = "UPDATE borrowed_books SET penalty=$penalty, noOfDaysLate=$datediff WHERE borrowedBookID=".$row['borrowedBookId']." ";
									mysql_query($updateQuery);
								}
								echo "<tr>";
								echo "<td>".$row['firstName']." ".$row['middleName']." ".$row['lastName']."</td>";
								echo "<td>".$row['callNumber']."</td>";
								echo "<td>".$row['title']."</td>";
								$date1 = date_create($row['dateBorrowed']);
								$date2 = date_create($row['dateToBeReturned']);
								echo "<td>".$row['authorName']."</td>";
								echo "<td>".date_format($date1, 'M j, Y')."</td>";
								echo "<td>".date_format($date2, 'M j, Y')."</td>";
								echo "<td>".$row['penalty']."</td>";
							    echo "<td><a class=\"btn btn-warning\" href=\"?borrowID=".$row['borrowedBookId']."\">RETURN</a>";
								
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
					</div>	<!-- /.panel-body -->
				</div>			
			</div>
			<!-- /.borrowed books -->
			
			<!-- returned books -->
			<div class="row">
				<div class="panel panel-info">
					<!-- panel-heading -->				
					<div class="panel-heading">
						<i class="glyphicon glyphicon-book"></i> Returned Books
					</div><!-- /.panel-heading -->
					
					<!-- panel body-->
					<div class="panel-body">
						<div class="dataTable_wrapper">

											<?php
					$sql = mysql_query("SELECT firstName, middleName, lastName, borrowedBookId, dateToBeReturned, number, callNumber, title, 
							dateBorrowed, dateToBeReturned, authorName, dateReturned FROM borrowed_books a
							JOIN books b ON b.bookID = a.bookID
							JOIN book_author c ON c.bookID = a.bookID
							JOIN authors d ON c.authorID = d.authorID
							JOIN users e ON a.userId = e.userId
							WHERE a.status=0")
							or die(mysql_error());	
					if(mysql_num_rows($sql)>0){

				?>

							<table class="table table-striped table-bordered table-hover" id="returnedBooks">
								<thead>
									<tr>
										<th>Borrower</th>									
										<th>Book ID</th>
										<th>Title</th>
										<th>Author</th>
										<th>Date Borrowed</th>
										<th>Return Due</th>
										<th>Date Returned</th>
									</tr>
								</thead>
								<tbody>
								<?php
							while($row = mysql_fetch_array($sql)){
								echo "<tr>";
								echo "<td>".$row['firstName']." ".$row['middleName']." ".$row['lastName']."</td>";
								echo "<td>".$row['callNumber']."</td>";
								echo "<td>".$row['title']."</td>";
								$date1 = date_create($row['dateBorrowed']);
								$date2 = date_create($row['dateToBeReturned']);
								$date3 = date_create($row['dateReturned']);
								echo "<td>".$row['authorName']."</td>";
								echo "<td>".date_format($date1, 'M j, Y')."</td>";
								echo "<td>".date_format($date2, 'M j, Y')."</td>";
								echo "<td>".date_format($date3, 'M j, Y')."</td>";							
							}
						?>	
								</tbody>
							</table>
								<?php 
					}else{
						echo "<tr><th colspan=\"6\"><center> No return history </th></tr>";
					}
				?>

						</div>
					</div>	<!-- /.panel-body -->
				</div>			
			</div>
			<!-- /.returned books -->			
			
		</div>
	</div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/filter-table.js"></script>
	
	<!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>

    <!-- DataTables JavaScript -->
    <script src="bower_components/DataTables/media/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>


    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#returnedBooks').DataTable({
                responsive: true
        });
    });
	
	    $(document).ready(function() {
        $('#borrowedBooks').DataTable({
                responsive: true
        });
    });
    </script> 	
  </body>
</html>
<?php
}
else
{
	header("location: sign");
}
?>