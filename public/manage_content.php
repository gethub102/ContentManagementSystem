<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php include("../includes/layouts/header.php"); ?>
<?php 
	if (isset($_GET["subject"])) {
		$current_subject = find_subject_by_id($_GET["subject"]);
		$selected_page_id = null;
		$current_page = null;
	} elseif (isset($_GET["page"])) {
		$current_page = find_page_by_id($_GET["page"]);
		$selected_subject_id = null;
		$current_subject = null;
	} else {
		$current_page = null;
		$current_subject = null;
	}
?>
	
<div id="main">
	<div id="navigation">
		<?php echo navigation($current_subject, $current_page); ?>
	</div>
	<div id="page">
		<h2>Manage Content</h2>
		<?php if ($current_subject) { ?>
			Menu Name: <?php echo $current_subject["menu_name"]; ?> <br />
		<?php } elseif ($current_page) { ?>
			<?php echo "Page Name: " . $current_page["menu_name"]; ?>
			<br />
			<?php echo "Contents: " . $current_page["content"]; ?>
		<?php } else { ?>
			Please select subject or page.
		<?php } ?>
	</div>
</div>



<?php include("../includes/layouts/footer.php"); ?>
