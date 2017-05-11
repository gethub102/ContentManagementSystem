<?php
	/* check database query good or not */
	function confirm_query($result_set) {
		if (!$result_set) {
			die("Database query failed.");
		}
	}
?>