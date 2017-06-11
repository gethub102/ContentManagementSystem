<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php include("../includes/layouts/header.php"); ?>
<?php find_selected_page(); ?>
	
<div id="main">
	<div id="navigation">
		<?php echo navigation($current_subject, $current_page); ?>
		<br />
		<a href="new_subject.php">+ Add a subject</a>
	</div>
	<div id="page">
		<?php echo message(); ?>
		<?php if ($current_subject) { ?>
			<h2>Manage Content</h2>
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
