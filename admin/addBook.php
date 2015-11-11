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
    <title>Administrator | Add Book</title>

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
					<h3 class="panel-title">Add Book</h3>
				  </div>
				  <div class="panel-body">
							<form method="post" data-toggle="validator" role="form">
							  <div class="form-group">
								<label for="callNumer">Call Number</label>
								<input type="text" class="form-control" name="callNumber" id="callNumber" placeholder="Enter book call number" 
									value="<?php
									if(isset($_POST['sign'])){
										echo $_POST['callNumber'];
									}else{
										echo "";
									}
								?>"  required>						
							  </div>							
							  <div class="form-group" >
								<label for="bookTitle">Book Title</label>
								<input type="text" class="form-control" id="bookTitle" name="bookTitle" placeholder="Enter book title" 
									value="<?php
									if(isset($_POST['sign'])){
										echo $_POST['bookTitle'];
									}else{
										echo "";
									}
								?>" required>
							  </div>
							  <div class="form-group">
								<label for="authorName">Book Author</label>
								<input type="text" pattern="[a-zA-Z0-9 ]{2,35}" class="form-control" id="authorName" name="authorName" placeholder="Enter author name" 
									value="<?php 
									if(isset($_POST['sign'])){
										echo $_POST['authorName'];
									}else{
										echo "";
									}
								?>" required>								
							  </div>
							  <div class="form-group">
								<label for="bookCategory">Book Category</label>
								<select class="form-control" id="bookCategory" name="bookCategory" placeholder="Enter book category">
									<option value="1">Science</option>
									<option value="2">Mathematics</option>	
									<option value="3">Physical Education</option>	
									<option value="4">History</option>	
									<option value="5">Language</option>		
									<option value="6">Computer</option>	
								</select>
								<!--<span class="help-block with-errors">Call Number</span>-->
							  </div>
							  <div class="form-group">
								<label for="available">Available Copies</label>
								<input type="number" min="0" max="100" class="form-control" name="available" id="available" placeholder="Enter available books" 
									value="<?php
									if(isset($_POST['sign'])){
										echo $_POST['available'];
									}else{
										echo "";
									}
								?>" title="Digits only" required>
							  </div>							  
							<div class="pull-right">							  
							  <button type="submit" name="sign" class="btn btn-primary">Add Book</button>
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
		$bookTitle = $_POST['bookTitle'];
		$callNumber = $_POST['callNumber'];
		$authorName = $_POST['authorName'];

		$query1 = mysql_query("SELECT callNumber, title FROM books WHERE callNumber='$callNumber' AND title='$bookTitle'");
		$validate1 = mysql_num_rows($query1);
		if($validate1 >= 1)
		{
			echo "<script>alert (\"$bookTitle is already added in the list\")</script>";
		}

		else
		{
			$callNumber = $_POST['callNumber'];
			$bookTitle = $_POST['bookTitle'];
			$authorName = $_POST['authorName'];
			$bookCategory = $_POST['bookCategory'];
			$available = $_POST['available'];

			$bookQuery = mysql_query("INSERT INTO books(callNumber,categoryId,title,totalOfBooks,noOfBookIn,noOfBookOut) 
						VALUES('$callNumber','$bookCategory','$bookTitle','$available','$available',0)") or die(mysql_error()."Can't insert.");

			$authorQuery = mysql_query("INSERT INTO authors(authorName) VALUES('$authorName')") or die(mysql_error()."Can't insert.");

			$query1 = mysql_query("SELECT bookId FROM books ORDER BY bookId DESC LIMIT 1");
			$query11 = mysql_fetch_row($query1);
			$query111 = $query11[0];

			$query2 = mysql_query("SELECT authorId FROM authors ORDER BY authorId DESC LIMIT 1");
			$query22 = mysql_fetch_row($query2);
			$query222 = $query22[0];

			$bookAuthorQuery = mysql_query("INSERT INTO book_author(authorId,bookId) VALUES('$query222','$query111')") or die(mysql_error()."Can't insert.");

			echo "<script>alert (\"$bookTitle is successfully aupdated \")</script>";
			header("Refresh:0; url=books.php");
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