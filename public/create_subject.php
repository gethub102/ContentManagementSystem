<?php require_once("../includes/session.php");  ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_function.php"); ?>
<?php  
	if (isset($_POST['submit'])) {
		// process the from 
		$menu_name = mysql_prep($_POST["menu_name"]);
		$position = (int)$_POST["position"];
		if (!isset($_POST["visible"])) {
			$_POST["visible"] = null;
		}
		$visible = (int)$_POST["visible"];
		
		// validation
		$required_fields = array("menu_name", "position", "visible");
		validate_presence($required_fields);

		$field_with_max_lengths = array("menu_name" => 30);
		validate_max_lengths($field_with_max_lengths);

		if (!empty($errors)) {
			$_SESSION["errors"] = $errors;
			redirect_to("new_subject.php");
		} 

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
