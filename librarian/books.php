<?php 
	session_start();
	$page = "books"; 
	include("../includes/dbcon.php");
	if(!($_SESSION['login'])) {
		header("Location: sign.php");
	}
	else
	{
		$username  = $_SESSION['username'];
?>

<!DOCTYPE html>
<html class="" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Librarian | Books</title>

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
			
			<!-- books -->
			<div class="row">
				<div class="panel panel-default">
					<!-- panel-heading -->				
					<div class="panel-heading">
						<i class="glyphicon glyphicon-book"></i> Books
						<div class="pull-right">
							<div class="btn-group">	
							<a href="addBook" class="btn btn-primary btn-xs active" role="button">Add Book</a>								
							</div>
						</div>
					</div>
					<!-- /.panel-heading -->
					<!-- panel body-->
					<div class="panel-body">
						<div class="dataTable_wrapper">
							<table class="table table-striped table-bordered table-hover" id="books">
								<thead>
									<tr>
										<th>Book ID</th>
										<th>Title</th>
										<th>Author</th>
										<th>Book Category</th>										
										<th>Available Copies</th>
										<th>In</th>
										<th>Out</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									<?php
                                            $query = mysql_query("SELECT a.callNumber,a.title,a.totalOfBooks,a.noOfBookIn,a.noOfBookOut,b.bookCategory,d.authorName
                                             FROM books a
                                             JOIN book_categories b ON a.categoryId = b.bookCategoryId
                                             JOIN book_author c ON a.bookId = c.bookId
                                             JOIN authors d ON c.authorId = d.authorId") or die(mysql_error());

                                            
                                            while($row = mysql_fetch_object($query))
                                            {
                                                echo "<tr>";
                                                //echo "<td> <a href=\"editUser.php?uname=$row->username\"> $row->username </a> </td>";
                                                echo "<td>".$row->callNumber ."</td>";
                                                echo "<td>".$row->title ."</td>";
                                                echo "<td>".$row->authorName ."</td>";
                                                echo "<td>".$row->bookCategory ."</td>";
                                                echo "<td>".$row->totalOfBooks ."</td>";
                                                echo "<td>".$row->noOfBookIn ."</td>";
                                                echo "<td>".$row->noOfBookOut ."</td>";
                                                echo "<td> <div class=\"btn-group\">	
													<a href=\"editBook.php?bookId=$row->callNumber\" class=\"btn btn-info btn-sm active\" role=\"button\">Edit Book</a>								
													</div> </td>";
                                                echo "</tr>";
                                            }
                                        ?>
								</tbody>
							</table>
						</div>	<!-- /.table-responsive -->
					</div>	<!-- /.panel-body -->
				</div>			
			</div>
			<!-- /.books -->			
			
		</div>	<!-- /.container-fluid-->
	</div>	<!-- /.container-->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/filter-table.js"></script>
	<script src="../js/validator.min.js"></script>

	<!-- jQuery -->
    <script src="bower_components/jquery/dist/jquery.min.js"></script>
    <!-- DataTables JavaScript -->
    <script src="bower_components/DataTables/media/js/jquery.dataTables.min.js"></script>
    <script src="bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#books').DataTable({
                responsive: true
        });
    });
    </script>
  </body>
</html>
<?php
}
?>