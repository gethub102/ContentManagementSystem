<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php  
	$current_page = find_page_by_id(htmlentities($_GET["page"]));
	if (!$current_page) {
		redirect_to("manage_content.php?page=" . urlencode($_GET["page"]));
	}

	$id = $current_page["id"];
	$subject_id = $current_page["subject_id"];
	$query = "DELETE FROM pages WHERE id = {$id} LIMIT 1";
	$result = mysqli_query($connection, $query);
	if ($result && mysqli_affected_rows($connection) == 1) {
		$_SESSION["message"] = "Page deleted.";
		redirect_to("manage_content.php?subject=" . urlencode($subject_id));
	} else {
		// Fail
		$_SESSION["message"] = "Subject deletion failed";
		redirect_to("manage_content.php?subject=" . urlencode($subject_id));
	}
?>