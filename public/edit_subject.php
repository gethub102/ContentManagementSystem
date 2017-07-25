<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_login(); ?>
<?php require_once("../includes/validation_function.php"); ?>
<?php find_selected_page(); ?>
<?php  
if (!$current_subject) {
	redirect_to("manage_content.php");
}
?>


<?php  
	if (isset($_POST['submit'])) {
		// process the from 
		// validation
		$required_fields = array("menu_name", "position", "visible");
		validate_presence($required_fields);

		$field_with_max_lengths = array("menu_name" => 30);
		validate_max_lengths($field_with_max_lengths);

		if (empty($errors)) {
			// perform update page
			$id = $current_subject["id"];
			$menu_name = mysql_prep($_POST["menu_name"]);
			$position = (int)$_POST["position"];
			if (!isset($_POST["visible"])) {
				$_POST["visible"] = null;
			}
			$visible = (int)$_POST["visible"];
 

			$query = "UPDATE subjects SET ";
			$query .= "menu_name = '{$menu_name}', ";
			$query .= "position = {$position}, ";
			$query .= "visible =  {$visible} ";
			$query .= "WHERE id = {$id} ";
			$query .= "LIMIT 1 ";

			$result = mysqli_query($connection, $query);
			if ($result && mysqli_affected_rows($connection) >= 0) {
				$_SESSION["message"] = "subject eidt successed";
				redirect_to("manage_content.php");
			} else {
				$_SESSION["message"] = "subject edit failed";
				// redirect_to("new_subject.php");
			}
		}
	} else {
		// this may be GET request
	}
?>

<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>

	
<div id="main">
	<div id="navigation">
		<?php echo navigation($current_subject, $current_page); ?>
	</div>
	<div id="page">
		<?php echo message(); ?>
		<?php $error = errors(); ?>
		<?php  echo from_errors($error); ?>

		<h2>Edit Subject: <?php echo htmlentities($current_subject["menu_name"]); ?></h2>
		<form action="edit_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>" method="post">
			<p>Menu name: 
				<input type="text" name="menu_name" value="<?php echo htmlentities($current_subject["menu_name"]); ?>" />
			</p>
			<p>Position: 
				<select name="position">
					<?php
						$subject_set = find_all_subjects(false);
						$subject_count = mysqli_num_rows($subject_set);
						for ($count = 1; $count <= ($subject_count); $count++) {
							echo "<option value=\"{$count}\"";
							if ($current_subject["position"] == $count) {
								echo " selected ";
							}
							echo ">{$count}</option>";
						}
					?>
				</select>
			</p>
			<p>Visiable: 
				<input type="radio" name="visible" value="0" <?php if ($current_subject["visible"] == 0) {echo " checked";} ?> /> No
				&nbsp;
				<input type="radio" name="visible" value="1" <?php if ($current_subject["visible"] == 1) {echo " checked";} ?> /> Yes
			</p>
			<input type="submit" name="submit" value="Edit Subject" />
		</form>
		<?php endLine(); ?>
		<a href="manage_content.php">Cancel</a>
		&nbsp;
		&nbsp;
		<a href="delete_subject.php?subject=<?php echo urlencode($current_subject["id"]); ?>" onclick="return confirm('Are you sure?');">Delete Subject</a>
	</div>
</div>



<?php include("../includes/layouts/footer.php"); ?>
