<?php 
session_start();
include("../includes/dbcon.php");
$page = "dashboard"; 
if(isset($_SESSION['login']) && $_SESSION['role']=="Administrator") {
	$username  = $_SESSION['username'];
?>
<!DOCTYPE html>
<html class="" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrator</title>

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
						<i class="glyphicon glyphicon-book"></i> Borrowed Books
					</div><!-- /.panel-heading -->
					
					<!-- panel body-->
					<div class="panel-body">
						<div class="dataTable_wrapper">
									<?php 
										$sql1 = mysql_query("SELECT borrowedBookId, dateBorrowed, dateToBeReturned
										FROM borrowed_books
										WHERE borrowed_books.status='1'");

										while ($row=mysql_fetch_array($sql1))
											{
												$now = time();
  												$dateToBeReturned = strtotime($row['dateToBeReturned']);
												$datediff = floor(($now - $dateToBeReturned)/(60*60*24));		
												if($datediff>=1){
													$penalty = $datediff * 5;
													$updateQuery = "UPDATE borrowed_books SET penalty=$penalty, noOfDaysLate=$datediff WHERE borrowedBookID=".$row['borrowedBookId']." ";
													mysql_query($updateQuery) or die(mysql_error());
												}
											}
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
										<th>Penalty</th>
									</tr>
								</thead>
								<tbody>
									<?php					
											$sql = mysql_query("SELECT borrowedBookId, firstName, middleName, lastName, dateBorrowed, dateToBeReturned, title, callNumber, authorName, penalty
													FROM borrowed_books
													JOIN users ON users.userId = borrowed_books.userId
													JOIN books ON books.bookId = borrowed_books.bookId
													JOIN book_author ON book_author.bookId = books.bookId
													JOIN authors ON authors.authorId = book_author.authorId
													WHERE borrowed_books.status='1'");
		
											while ($row=mysql_fetch_array($sql))
											{
											$a = $row['firstName'];
											$b = $row['middleName'];
											$c = $row['lastName'];
											$d = $row['callNumber'];
											$e = $row['title'];
											$f = $row['authorName'];
											$g = date_create($row['dateBorrowed']);
											$h = date_create($row['dateToBeReturned']);
					
											echo "<tr>";
											echo "<td>".$a." ".$b." ".$c."</td>";
											echo "<td>".$d."</td>";
											echo "<td>".$e."</td>";
											echo "<td>".$f."</td>";
											echo "<td>".date_format($g, 'M j, Y')."</td>";
											echo "<td>".date_format($h, 'M j, Y')."</td>";
											echo "<td>".$row['penalty']."</td>";
											echo "</tr>";
										}
									?>
								</tbody>
							</table>
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
										<th>Penalty</th>
									</tr>
								</thead>
								<tbody>
									<?php
										$sql = mysql_query("SELECT firstName, middleName, lastName, dateBorrowed, dateToBeReturned, dateReturned, penalty, title, callNumber, authorName
										FROM borrowed_books
										JOIN users ON users.userId = borrowed_books.userId
										JOIN books ON books.bookId = borrowed_books.bookId
										JOIN book_author ON book_author.bookId = books.bookId
										JOIN authors ON authors.authorId = book_author.authorId
										WHERE borrowed_books.status='0'");
										while ($row=mysql_fetch_array($sql))
										{
											$a = $row['firstName'];
											$b = $row['middleName'];
											$c = $row['lastName'];
											$d = $row['callNumber'];
											$e = $row['title'];
											$f = $row['authorName'];
											$g = date_create($row['dateBorrowed']);
											$h = date_create($row['dateToBeReturned']);
											$i = date_create($row['dateReturned']);
											$j = $row['penalty'];

											echo "<tr>";
											echo "<td>".$a." ".$b." ".$c."</td>";
											echo "<td>".$d."</td>";
											echo "<td>".$e."</td>";
											echo "<td>".$f."</td>";
											echo "<td>".date_format($g, 'M j, Y')."</td>";
											echo "<td>".date_format($h, 'M j, Y')."</td>";
											echo "<td>".date_format($i, 'M j, Y')."</td>";
											echo "<td>".$j."</td>";
											echo "</tr>";
										}
									?>
								</tbody>
							</table>
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
	header("location: sign.php");
}
?>