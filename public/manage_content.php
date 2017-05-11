<?php
	// create connection
	$dbhost = "localhost";
	$dbuser = "wenbin";
	$dbpass = "wen";
	$dbname = "widget_corp";
	$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
	// test if connected
	if (mysqli_connect_errno()) {
		die("Database connection failed: " . 
			mysqli_connect_error() .
			" (" . mysqli_connect_errno() . ") "
			);
	}
?>
<?php require_once("../includes/functions.php"); ?>

<?php
	// perform database query
	$query = "SELECT * ";
	$query .= "FROM subjects ";
	$query .= "WHERE visible = 1 ";
	$query .= "ORDER BY position ASC ";
	$ret = mysqli_query($connection, $query);
	// test if query error
	if (!$ret) {
		die("Database query failed.");
	}
?>

<?php include("../includes/layouts/header.php"); ?>


	<div id="main">
		<div id="navigation">
			<ul class="subjects">

				<?php

					// use returned data
					while ($subject = mysqli_fetch_assoc($ret)) {
				?>

				<li><?php echo $subject["menu_name"] . " (" . $subject["id"] . ") " ?></li>

				<?php

					}

				?>

			</ul>
		</div>
		<div id="page">
			<h2>Manage Content</h2>
			
		</div>
	</div>

<?php 
 // release returned data
 mysqli_free_result($ret);
 ?>

<?php include("../includes/layouts/footer.php"); ?>

<?php
	// close database
	mysqli_close($connection);
  ?>