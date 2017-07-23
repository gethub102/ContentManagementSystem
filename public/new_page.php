<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_function.php"); ?>

<?php find_selected_page(); ?>

<?php 
	// can't add a new page unless we have a subject as a parent!
	if (!$current_subject) {
		redirect_to("manage_content.php");
	}
?>

<?php
	if (isset($_POST["submit"])) {
		// process the form
		// validations
		$required_fields = array("menu_name", "position", "visible", "content");
		validate_presence($required_fields);
		$field_with_max_lengths = array("menu_name" => 30);
		validate_max_lengths($field_with_max_lengths);

		if (!empty($errors)) {
			$_SESSION["errors"] = $errors;
			redirect_to("new_page.php?subject=" . urlencode($current_subject["id"]));  // this place is really anoying, this bug cost me lots of time.
		} 

		// make sure you add the subject_id!
	    $subject_id = $current_subject["id"];
	    $menu_name = mysql_prep($_POST["menu_name"]);
	    $position = (int) $_POST["position"];
	    $visible = (int) $_POST["visible"];
	    // be sure to escape the content
	    $content = mysql_prep($_POST["content"]);
	  
	    $query  = "INSERT INTO pages (";
	    $query .= "  subject_id, menu_name, position, visible, content";
	    $query .= ") VALUES (";
	    $query .= "  {$subject_id}, '{$menu_name}', {$position}, {$visible}, '{$content}'";
	    $query .= ")";
	    $result = mysqli_query($connection, $query);
	    if ($result) {
	    	// Success
	      	$_SESSION["message"] = "Page created.";
	      	redirect_to("manage_content.php?subject=" . urlencode($current_subject["id"]));
	    } else {
	      	// Failure
	      	$_SESSION["message"] = "Page creation failed.";
	    }
	}
?>

<?php include("../includes/layouts/header.php"); ?>
<div id="main">
	<div id="navigation">
		<?php echo navigation($current_subject, $current_page); ?>
	</div>
	<div id="page">
		<?php echo message(); ?>
		<?php $error = errors(); ?>
		<?php  echo from_errors($error); ?>

		<h2>Create Page!</h2> 
		<?php
			$subject_id = $_GET["subject"];
		?>
		<form action="new_page.php?subject=<?php echo urlencode($subject_id); ?>" method="post">
			<p>Menu name: 
				<input type="text" name="menu_name" value="" />
			</p>
			<p>Position: 
				<select name="position">
					<?php
						$page_set = find_pages_for_subject($current_subject["id"]);
						$page_count = mysqli_num_rows($page_set);
						for ($count = 1; $count <= ($page_count + 1); $count++) {
							echo "<option value=\"{$count}\">{$count}</option>";
						}
					?>
				</select>
			</p>
			<p>Visiable: 
				<input type="radio" name="visible" value="0" /> No
				&nbsp;
				<input type="radio" name="visible" value="1" /> Yes
			</p>
			<p>Content:<br />
				<textarea name="content" rows="20" cols="80"></textarea>
			</p>
			<input type="submit" name="submit" value="Create Page" />
		</form>
		<?php endLine(); ?>
		<a href="manage_content.php?subject=<?php echo urlencode($current_subject["id"]); ?>">Cancel</a>
	</div>
</div>



<?php include("../includes/layouts/footer.php"); ?>