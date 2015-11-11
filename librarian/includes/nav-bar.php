			<?php
				$sql = mysql_query("SELECT * FROM users WHERE username='$username'");

				while($row=mysql_fetch_array($sql))
				{
					$firstName = $row['firstName'];
					$middleName = $row['middleName'];
					$lastName = $row['lastName'];
				}
			?>
			<div class="row">
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
					  <a class="navbar-brand" href="index">Librarian</a>
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
					  <ul class="nav navbar-nav">
						<li class="<?php echo ($page == "dashboard" ? "active" : "")?>"><a href="index">Dashboard <span class="sr-only">(current)</span></a></li>
						<li class="<?php echo ($page == "books" ? "active" : "")?>"><a href="books">Books</a></li>			
					  </ul>

					  <ul class="nav navbar-nav navbar-right">
						<li><a>Hello, <?php echo $firstName." ".$middleName." ".$lastName ?></a></li>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-cog"></span> Settings <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
							  <li><a href="changePassword"><span class="glyphicon glyphicon-lock"></span> Change Password</a></li>							
							  <li><a href="includes/logout"><span class="glyphicon glyphicon-off"></span> Logout</a></li>
							</ul>
						</li>		
					  </ul>
					</div><!-- /.navbar-collapse -->
				  </div><!-- /.container-fluid -->
				</nav>		
			</div>