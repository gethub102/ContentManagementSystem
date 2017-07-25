<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_function.php"); ?>
	
<?php  
	$username = "";
	if (isset($_POST["submit"])) {
		// process the from

		// validation
		$required_fields = array("username", "password");
		validate_presence($required_fields);
		if (empty($errors)) {
			// Attempt Login
			$username = $_POST["username"];
			$password = $_POST["password"];
			$found_admin = attempt_login($username, $password);
			if ($found_admin) {
				// success
				// Mark user as logged in
				$_SESSION["id"] = $found_admin["id"];
				$_SESSION["username"] = $found_admin["username"];
				redirect_to("admin.php");
			} else {
				// failure
				$_SESSION["message"] = "Username/password login failed.";
			}
		}
	} else {
		// this may be a GET request
	} // end: if (isset($_POST["submit"]))  
?>

<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
<div id="main">
	<div id="navigation">
		&nbsp;
	</div>
	<div id="page">
		<?php echo message(); ?>
		<?php echo from_errors($errors); ?>
		<h2>Login</h2>
			<form action="login.php" method="post">
				<p>Username:
					<input type="text" name="username" value="<?php echo htmlentities($username); ?>" />
				</p>
				<p>Password:
					<input type="password" name="password" value="" />
				</p>
				<input type="submit" name="submit" value="Submit" />
			</form>
	</div>
</div>



<?php include("../includes/layouts/footer.php"); ?>
