<?php require_once("../includes/session.php");  ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php  
	if (isset($_POST['submit'])) {
		// process the from 
		$menu_name = mysql_prep($_POST["menu_name"]);
		$position = (int)$_POST["position"];
		$visible = (int)$_POST["visible"];
		
		$menu_name = mysqli_real_escape_string($connection, $menu_name);

		$query = "INSERT INTO subjects ( ";
		$query .= "menu_name, position, visible ";
		$query .= ") VALUES ( ";
		$query .= " '{$menu_name}', {$position}, {$visible} ";
		$query .= ")";

		$result = mysqli_query($connection, $query);
		if ($result) {
			$_SESSION["message"] = "subject successed";
			redirect_to("manage_content.php");
		} else {
			$_SESSION["message"] = "subject failed";
			redirect_to("new_subject.php");
		}
	} else {
		// this may be GET request
		redirect_to("new_subject.php");
	}
?>


<?php 
	if (isset($connection)) { mysqli_close($connection); }
?>
