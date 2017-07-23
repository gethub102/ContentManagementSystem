
	<div id="footer" > Copyright <?php echo date("Y"); ?>, Wenbin </div>
</body>
</html>

<?php
	// close database
	if (isset($connection)) {
		mysqli_close($connection);
	}
?>