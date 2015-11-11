<?php $page = "import"; ?>

<?php
session_start();
if(!($_SESSION['login']) && $_SESSION['role']!="Administrator") {
		header("Location: sign") or die();
}else{
include("../includes/dbcon.php");
	
	if(isset($_POST['btnImport'])) {
		$import = $_POST['selectImport'];
		$error = 0;
		$message[$error]='';
		
		$import =  $_POST["selectImport"];
		$file = $_FILES['fileInput']['tmp_name'];

		if($file == "")
		{
		   $message[$error] =  "No File Selected!";
			$error++;
		}
		else {
			
			if($import == 'students' || $import == 'faculty')
			{
				if (($getfile = fopen($file, "r")) !== FALSE) {
					$data = fgetcsv($getfile, 1000, ",");
					$num = count($data);
					
					if($num!=5) {
						$message[$error] =  "Invalid File.";
						$error++;
					}	
					else {
						$count = 0;
						while (($data = fgetcsv($getfile, 1000, ",")) !== FALSE) {
							for ($c=0; $c < $num; $c++) {
								$result = $data;
								$str = implode(",", $result);
								$slice = explode(",", $str);
							}
							
							$count++;
							
							//validation for Student Number
							if($slice[0]==''){
								$message[$error] =  "Row ".$count.": Student Number cannot be null.";
								$error++;
							}
							else if(!preg_match('/^[0-9\s]+$/',$slice[0])){
								$message[$error] =  "Row ".$count.": Invalid Student Number.";
								$error++;							
							}
							
							//validation for first name
							if($slice[1]==''){
								$message[$error] =  "Row ".$count.": First Name cannot be null.";
								$error++;
							}
							else if(!preg_match('/^[A-Z][a-zA-Z -]+$/',$slice[1])){
								$message[$error] =  "Row ".$count.": First Name should not contain numbers of special characters.";
								$error++;							
							}
							
							//validation for middle name
							if(!preg_match('/^[A-Z][a-zA-Z -]+$/',$slice[2])){
								$message[$error] =  "Row ".$count.": Middle Name should not contain numbers of special characters.";
								$error++;							
							}
							
							//validation for middle name
							if($slice[3]==''){
								$message[$error] =  "Row ".$count.": Last Name cannot be null.";
								$error++;
							}
							else if(!preg_match('/^[A-Z][a-zA-Z -]+$/',$slice[3])){
								$message[$error] =  "Row ".$count.": Last Name should not contain numbers of special characters.";
								$error++;							
							}
							if($slice[4]==''){
								$message[$error] =  "Row ".$count.": Username cannot be null.";
								$error++;
							}
							
							if($error == 0) {
								if($import == 'students') 
									$role = '4';
								else
									$role = '3';
									
								$query = mysql_query("INSERT INTO users(number, firstName, middleName, lastName, username, password, roleId)
									VALUES('".$slice[0]."','".$slice[1]."','".$slice[2]."','".$slice[3]."','".$slice[4]."','".$slice[1]."','".$role."')") 
									or die(mysql_error());
							}
						}
					}
				}
			}
			else {
$categoryId = "";
				if (($getfile = fopen($file, "r")) !== FALSE) {
					$data = fgetcsv($getfile, 1000, ",");
					$num = count($data);
					
					if($num!=5) {
						$message[$error] =  "Invalid File.";
						$error++;
					}	
					else {
						$count = 0;
							while (($data = fgetcsv($getfile, 1000, ",")) !== FALSE) {
							for ($c=0; $c < $num; $c++) {
								$result = $data;
								$str = implode(",", $result);
								$slice = explode(",", $str);
							}
							
							$count++;
							
							//validation for Call Number
							if($slice[0]==''){
								$message[$error] =  "Row ".$count.": Call Number cannot be null.";
								$error++;
							}
							
							//validation for Title
							if($slice[1]==''){
								$message[$error] =  "Row ".$count.": Title cannot be null.";
								$error++;
							}
							
							//validation for Authors
							if($slice[2]==''){
								$message[$error] =  "Row ".$count.": Author cannot be null.";
								$error++;
							}

//validation for Category
if($slice[3] == '') {
      $message[$error] =  "Row ".$count.": Book Category cannot be null.";
								$error++;

}
else {
 $query =mysql_query("Select bookCategory from book_categories");
 $categories = array();
 $query =mysql_query("Select * from book_categories");
 while($data = mysql_fetch_assoc($query)) {
     $categories[] = $data["bookCategory"];
 }
 if(in_array($slice[3], $categories)) {
     $query2 = mysql_query("Select bookCategoryId From book_categories WHERE bookCategory LIKE '".$slice[3]."'") or die(mysql_error());
    	while($row = mysql_fetch_array($query2)){
    	$categoryId = $row['bookCategoryId'];
    } 
}else {
$message[$error] =  "Row ".$count.": Book Category doesn't exist in the list of Categories.".$slice[3]."";
$error++;
 }
}
						
							//validation for Total Number of Books
							if($slice[4]==''){
								$message[$error] =  "Row ".$count.": Total Number of books cannot be null.";
								$error++;
							}
							else if(!is_numeric($slice[4])){
								$message[$error] =  "Row ".$count.": Invalid Total Number of books.".$slice[4];	
$error++;				
							}

							
							if($error == 0) {
									
								$query = mysql_query("INSERT INTO books(callNumber, title, categoryId, totalOfBooks, noOfBookIn, noOfBookOut)
									VALUES('".$slice[0]."','".$slice[1]."','".$categoryId."','".$slice[4]."','".$slice[4]."','0')") 
									or die(mysql_error());
								$bookQuery = mysql_query("SELECT bookId FROM books ORDER BY bookId DESC LIMIT 1") or die(mysql_error());
								$bookId = mysql_fetch_row($bookQuery);
								$query111 = $bookId[0];
								
								$authors = explode(";",$slice[2]);
								foreach($authors as $value){
									$query = mysql_query("INSERT INTO authors(authorName) VALUES('".$value."')") 
									or die(mysql_error());
									$authorQuery = mysql_query("SELECT authorId FROM authors ORDER BY authorId DESC LIMIT 1") or die(mysql_error());
									$authorId = mysql_fetch_row($authorQuery);
									$query222 = $authorId[0];
									$query = mysql_query("INSERT INTO book_author(authorId, bookId) VALUES('$query222','$query111')")
									or die(mysql_error());
								}
							}
						}
					}
				}
			}
		}
	}

?>

<!DOCTYPE html>
<html class="" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrator | Import</title>

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
  <body background="">
	<div class="container">
		<div class="container-fluid">
			<?php include 'includes/nav-bar.php';?>
				<form method="post" enctype="multipart/form-data">
				<select name="selectImport" class="form-control input-lg">
					<option value="students">Students</option>
					<option value="faculty">Faculties</option>
					<option value="books">Books</option>
				</select>
				<input type="file" name="fileInput" class="form-control" accept=".csv"></input>
				<input type="submit" value="Go" name="btnImport" class="btn btn-primary"></input>
			</form>
		
			<br><br>
			
			<?php 
				 if(isset($_POST['btnImport'])) {
			?>
			<div class="row">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="glyphicon glyphicon-user"></i> System Message(s)
					</div>
					<!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="users">
                                    <tbody>
                                        <tr>
																	<?php
																		foreach ( $message as $value){
																	?>			
																				<td><?php if($error!=0){
																				 echo $value;
																				 }else{
																				 echo "Data have been imported to the database";
																				}
																				?></td>
																	<?php
																	}
																	?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
				</div>			
			</div>
						<?php } ?>
			
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
        $('#users').DataTable({
                responsive: true
        });
    });
    </script>
  </body>
</html>
<?php
}
?>




