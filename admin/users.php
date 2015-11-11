<?php 
session_start();
include("../includes/dbcon.php");
$page = "users"; 
if(!($_SESSION['login'])) {
    header("Location: sign.php");
}
else
{
?>

<!DOCTYPE html>
<html class="" lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Administrator | Users</title>

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
			<div class="row">
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="glyphicon glyphicon-user"></i> Users
						<div class="pull-right">
							<div class="btn-group">	
							<a href="addUser" class="btn btn-primary btn-xs active" role="button">Add User</a>								
							</div>
						</div>
					</div>
					<!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="users">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>First Name</th>
                                            <th>Middle Name</th>
                                            <th>Last Name</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $query = mysql_query("SELECT a.username,a.firstName,a.middleName,a.lastName,b.role,a.status
                                             FROM users a
                                             JOIN user_role b ON a.roleId = b.roleId");
                                            while($row = mysql_fetch_object($query))
                                            {
                                                echo "<tr>";
                                                echo "<td> <a href=\"editUser.php?uname=$row->username\"> $row->username </a> </td>";
                                                echo "<td>".$row->firstName."</td>";
                                                echo "<td>".$row->middleName."</td>";
                                                echo "<td>".$row->lastName."</td>";
                                                echo "<td>".$row->role."</td>";
                                                if($row->status==1) 
                                                {
                                                    echo "<td><a href=\"editStatus.php?uname=$row->username\">Active</a></td>";
                                                }
                                                else 
                                                {
                                                    echo "<td><a href=\"editStatus.php?uname=$row->username\">Inactive</a></td>";
                                                }
                                            }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
				</div>			
			</div>
			
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