<div class="container container-fluid">
	<div class="row">
	<!-- /collapse results -->
	<?php 
		if(isset($_POST['searchBtn'])){
			echo "<div>
				  <div class=\"well\">
				  <div class=\"page-header\">";

			$searchTerm = mysql_real_escape_string(strip_tags(stripslashes($_POST['keyWord'])));

			if($_POST['filter']=="title"){
				$sql1 = mysql_query("SELECT a.bookId, title, authorName, callNumber, bookCategory,
									totalOfBooks, noOfBookIn, noOfBookOut FROM books a
									JOIN book_author b ON b.bookID = a.bookID
									JOIN authors c ON c.authorID = b.authorID
									JOIN book_categories d ON d.bookCategoryId = a.categoryID
									WHERE a.title LIKE '%$searchTerm%'") 
									or die(mysql_error());
			}else{
				$sql1 = mysql_query("SELECT a.bookId, title, authorName, callNumber, bookCategory,
							 		totalOfBooks, noOfBookIn, noOfBookOut FROM books a
									JOIN book_author b ON b.bookID = a.bookID
									JOIN authors c ON c.authorID = b.authorID
									JOIN book_categories d ON d.bookCategoryId = a.categoryID
									WHERE c.authorName LIKE '%$searchTerm%'") 
									or die(mysql_error());
			}

			$result = mysql_num_rows($sql1);
			if($result>1){
				echo "<h3>$result <small>results</small></h3></div>";
			}else if($result==0){
				echo "<h3><small>No result</small></h3></div>";
			}else{
				echo "<h3>$result <small>result</small></h3></div>";
			}
						
									

			while($row=mysql_fetch_array($sql1)){
				echo "<div class=\"media\">
				<div class=\"media-left\">
				<a href=\"#\">
				<span class=\"glyphicon glyphicon-book\" aria-hidden=\"true\"></span>
				</a>
				</div>
				<div class=\"media-body\">
				<a class=\"showModalForBook\" href=\"#myModal".$row['bookId']."\" type=\"button\" data-toggle=\"modal\" data-target=\"#myModal".$row['bookId']."\" data-toggle=\"tooltip\" data-placement=\"right\" title=\"Click for Book Information\">".$row['title']."</a>
				<p>".$row['authorName']."</p>
				<p>".$row['callNumber']."</p>
				</div>	  
				</div>";
			
							  echo "<div class=\"modal fade\" id=\"myModal".$row['bookId']."\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"myModalLabel\" aria-hidden=\"true\">";
							  echo "<div class=\"modal-dialog\">";
							  echo "<div class=\"modal-content\">";
							  echo "<div class=\"modal-header\">";
							  echo "<button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\"><span aria-hidden=\"true\">&times;</span></button>";
							  echo "<h4 class=\"modal-title\" id=\"myModalLabel\">".$row['title']."</h4>";
							  echo "</div>";
							  echo "<div class=\"modal-body\">";
							  echo "<div class=\"borrow\">";
							  echo "<div class=\"media-body\">";

							  echo "<p>Author: ".$row['authorName']."</p>";
							  echo "<p>Call Number: ".$row['callNumber']."</p>";
							  echo "<p>Book Category: ".$row['bookCategory']."</p>";
							  echo "</div>";
							  echo "<table class=\"table\">";
							  echo "<thead>";
							  echo "<tr>";
							  echo "<th>Available Copies</th>";
							  echo "<th>In</th>";
							  echo "<th>Out</th>";								
							  echo "</tr>";
							  echo "</thead>";
							  echo "<tbody class=\"searchable\">";
							  echo "<tr>";
							  echo "<td>".$row['totalOfBooks']."</td>";
							  echo "<td>".$row['noOfBookIn']."</td>";
							  echo "<td>".$row['noOfBookOut']."</td>";						
							  echo "</tr>";
							  echo "</tbody>";
							  echo "</table>";
							  echo "</div>";
							  echo "</div>";
							  echo "<div class=\"modal-footer\">";
							  echo "<button type=\"button\" class=\"btn btn-default\" data-dismiss=\"modal\">Close</button>";
							  echo "<a name=\"btnBorrow\" class=\"btn btn-primary\" href=\"?bookID=".$row['bookId']."\">Borrow</a>";
							  echo "</div>";
							  echo "</div>";
							  echo "</div>";
							  echo "</div>";
			}

		}
	?>
</div>
