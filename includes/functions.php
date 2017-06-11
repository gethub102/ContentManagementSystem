<?php
	/* redirect to pages */
	function redirect_to($new_location) {
		header("Location: " . $new_location);
		exit;
	}

	// escape the string for avoiding sql injection
	function mysql_prep($string) {
		global $connection;
		$escaped_string = 
		mysqli_real_escape_string($connection, $string);
		return $escaped_string;
	}

	/* check database query good or not */
	function confirm_query($result_set, $err_message = "errors happened") {
		if (!$result_set) {
			die($err_message . "Database query failed.");
		}
	}

	/* find all subjects */ 
	function find_all_subjects() {
		global $connection;
		// perform database query
		$query = "SELECT * ";
		$query .= "FROM subjects ";
		// $query .= "WHERE visible = 1 ";
		$query .= "ORDER BY position ASC ";
		$subject_set = mysqli_query($connection, $query);
		// test if query error
		confirm_query($subject_set);
		return $subject_set;
	}

	/* find all pages */
	function find_pages_for_subject($subject_id) {
		global $connection;
		$safe_subject_id = mysqli_real_escape_string($connection, $subject_id);
		// perform database query
		$query = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE  ";
		$query .= " subject_id = {$safe_subject_id} ";
		$query .= "ORDER BY position ASC ";
		$page_set = mysqli_query($connection, $query);
		// test if query error
		confirm_query($page_set);
		return $page_set;
	}


	function find_subject_by_id($subject_id) {
		global $connection;

		$safe_subject_id = mysqli_real_escape_string($connection, $subject_id);

		// perform database query
		$query = "SELECT * ";
		$query .= "FROM subjects ";
		$query .= "WHERE id = {$safe_subject_id} ";
		$query .= "LIMIT 1 ";
		$subject_set = mysqli_query($connection, $query);
		// test if query error
		confirm_query($subject_set);
		if ($subject = mysqli_fetch_assoc($subject_set)) {
			return $subject;	
		} else {
			return null;
		}
	}

	/* find page by page id */
	function find_page_by_id($page_id) {
		global $connection;

		$safe_page_id = mysqli_real_escape_string($connection, $page_id);
		$query = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE id = {$safe_page_id} ";
		$query .= "LIMIT 1 ";
		$page_set = mysqli_query($connection, $query);
		confirm_query($page_set);
		if ($pages = mysqli_fetch_assoc($page_set)) {
			return $pages;
		} else {
			return null;
		}
	}

	/* find the current subject and page array value */
	function find_selected_page() {
		global $current_subject;
		global $current_page;
		if (isset($_GET["subject"])) {
			$current_subject = find_subject_by_id($_GET["subject"]);
			$current_page = null;
		} elseif (isset($_GET["page"])) {
			$current_page = find_page_by_id($_GET["page"]);
			$current_subject = null;
		} else {
			$current_page = null;
			$current_subject = null;
		}
	}

	// navigation take two args
	// - the current subject array or null
	// - the current page array or null
	function navigation ($current_subject, $current_page) {
		$output = "<ul class=\"subjects\">";
		$subject_set = find_all_subjects(); 
		while ($subject = mysqli_fetch_assoc($subject_set)) {
			$output .=  "<li";
			if ($current_subject != null && $subject["id"] == $current_subject["id"]) {
				$output .= " class=\"selected\"";
			}
			$output .= ">"; 
			$output .= "<a href=\"manage_content.php?subject=";
			$output .= urlencode($subject["id"]); 
			$output .= "\">";
			$output .= $subject["menu_name"]; 
			$output .= "</a>";
			$page_set = find_pages_for_subject($subject["id"]);

				$output .= "<ul class=\"pages\">";
					while ($page = mysqli_fetch_assoc($page_set)) {
						$output .= "<li";
						if ($current_page != null && $page["id"] == $current_page["id"]) {
							$output .= " class=\"selected\"";
						}
						$output .= ">"; 
						$output .= "<a href=\"manage_content.php?page=";
						$output .= urlencode($page["id"]); 
						$output .= "\">";
						$output .= $page["menu_name"];
						$output .= "</a>";
						$output .= "</li>";
					}
					mysqli_free_result($page_set);
				$output .= "</ul>";
			$output .= "</li>";
		}
		mysqli_free_result($subject_set);
		$output .= "</ul>";
		return $output;
	}

	/* print a new line */
	function endline() {
		echo "<br />";
	}
?>