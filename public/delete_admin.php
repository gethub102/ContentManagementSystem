<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_login(); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/validation_function.php"); ?>

<?php  
	$admin = find_admin_by_id($_GET["id"]);
	if (!$admin) {
		// admin ID was missing or invalid or
		// admin could not be found in database;
		redirect_to("manage_admins.php");
	}
	$id = $admin["id"];
	$query = "DELETE FROM admins WHERE id = {$id} LIMIT 1; ";
	$result = mysqli_query($connection, $query);
	if ($result && mysqli_affected_rows($connection) == 1) {
		// success
		$_SESSION["message"] = "Admin deleted.";
		redirect_to("manage_admins.php");
	} else {
		// failed
		$_SESSION["message"] = "Admin deletion failed.";
		redirect_to("manage_admins.php");
	}
?>


<?php include("../includes/layouts/footer.php"); ?>
