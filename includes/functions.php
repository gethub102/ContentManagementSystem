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

	/* find all subjects, true all visible and not visible. fasle only find visible ones */ 
	function find_all_subjects($public = true) {
		global $connection;
		// perform database query
		$query = "SELECT * ";
		$query .= "FROM subjects ";
		if ($public) {
			$query .= "WHERE visible = 1 ";
		}
		$query .= "ORDER BY position ASC ";
		$subject_set = mysqli_query($connection, $query);
		// test if query error
		confirm_query($subject_set);
		return $subject_set;
	}

	/* find all pages */
	function find_pages_for_subject($subject_id, $public = true) {
		global $connection;
		$safe_subject_id = mysqli_real_escape_string($connection, $subject_id);
		// perform database query
		$query = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE  ";
		$query .= " subject_id = {$safe_subject_id} ";
		if ($public) {
			$query .= " AND visible = 1 ";
		}
		$query .= "ORDER BY position ASC ";
		$page_set = mysqli_query($connection, $query);
		// test if query error
		confirm_query($page_set);
		return $page_set;
	}


	function find_subject_by_id($subject_id, $public = true) {
		global $connection;

		$safe_subject_id = mysqli_real_escape_string($connection, $subject_id);

		// perform database query
		$query = "SELECT * ";
		$query .= "FROM subjects ";
		$query .= "WHERE id = {$safe_subject_id} ";
		if ($public) {
			$query .= "AND visible = 1 ";
		}
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
	function find_page_by_id($page_id, $public = true) {
		global $connection;

		$safe_page_id = mysqli_real_escape_string($connection, $page_id);
		$query = "SELECT * ";
		$query .= "FROM pages ";
		$query .= "WHERE id = {$safe_page_id} ";
		if ($public) {
			$query .= "AND visible = 1 ";
		}
		$query .= "LIMIT 1 ";
		$page_set = mysqli_query($connection, $query);
		confirm_query($page_set);
		if ($pages = mysqli_fetch_assoc($page_set)) {
			return $pages;
		} else {
			return null;
		}
	}

	/* find default page for subject */
	function find_default_page_for_subject($subject_id) {
		$page_set = find_pages_for_subject($subject_id);
		if ($first_page = mysqli_fetch_assoc($page_set)) {
			return $first_page;
		} else {
			return null;
		}
	}

	/* find the current subject and page array value */
	function find_selected_page($public=false) {
		global $current_subject;
		global $current_page;
		if (isset($_GET["subject"])) {
			$current_subject = find_subject_by_id($_GET["subject"], $public);
			if ($current_subject && $public) {
				$current_page = find_default_page_for_subject($current_subject["id"]);	
			} else {
				$current_page = null;
			}
		} elseif (isset($_GET["page"])) {
			$current_page = find_page_by_id($_GET["page"], $public);
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
		$subject_set = find_all_subjects(false); 
		while ($subject = mysqli_fetch_assoc($subject_set)) {
			$output .=  "<li";
			if ($current_subject != null && $subject["id"] == $current_subject["id"]) {
				$output .= " class=\"selected\"";
			}
			$output .= ">"; 
			$output .= "<a href=\"manage_content.php?subject=";
			$output .= urlencode($subject["id"]); 
			$output .= "\">";
			$output .= htmlentities($subject["menu_name"]); 
			$output .= "</a>";
			$page_set = find_pages_for_subject($subject["id"], false);

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
						$output .= htmlentities($page["menu_name"]);
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

	function public_navigation ($current_subject, $current_page) {
		$output = "<ul class=\"subjects\">";
		$subject_set = find_all_subjects(); 
		while ($subject = mysqli_fetch_assoc($subject_set)) {
			$output .=  "<li";
			if ($current_subject != null && $subject["id"] == $current_subject["id"]) {
				$output .= " class=\"selected\"";
			}
			$output .= ">"; 
			$output .= "<a href=\"index.php?subject=";
			$output .= urlencode($subject["id"]); 
			$output .= "\">";
			$output .= htmlentities($subject["menu_name"]); 
			$output .= "</a>";

			if ($current_subject["id"] == $subject["id"] ||
				$current_page["subject_id"] == $subject["id"]) {
				$page_set = find_pages_for_subject($subject["id"]);
				$output .= "<ul class=\"pages\">";
				while ($page = mysqli_fetch_assoc($page_set)) {
					$output .= "<li";
					if ($current_page != null && $page["id"] == $current_page["id"]) {
						$output .= " class=\"selected\"";
					}
					$output .= ">"; 
					$output .= "<a href=\"index.php?page=";
					$output .= urlencode($page["id"]); 
					$output .= "\">";
					$output .= htmlentities($page["menu_name"]);
					$output .= "</a>";
					$output .= "</li>";
				}
				mysqli_free_result($page_set);
				$output .= "</ul>";
			}
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

	/* output the errors message from array to string */ 
	function from_errors($errors = array()) {
		$output = "";
		if (!empty($errors)) {
			$output .= "<div class=\"error\">";
			$output .= "Please fix the following errors:";
			$output .= "<ul>";
			foreach ($errors as $key=>$error) {
				$output .= "<li>";
				$output .= htmlentities($error);
				$output .= "</li>";
			}
			$output .= "</ul>";
			$output .= "</div>";

		}
		return $output;
	}

	/* find all the admin users through sql search */
	function find_all_admins() {
		global $connection;
		$query = "SELECT * ";
		$query .= "FROM admins ";
		$query .= "ORDER BY username ASC ";
		$admin_set = mysqli_query($connection, $query);
		confirm_query($admin_set);
		return $admin_set;
	}

	/* find the specific admin user through its id number */
	function find_admin_by_id($admin_id) {
		global $connection;
		$safe_admin_id = mysqli_real_escape_string($connection, $admin_id);
		$query = "SELECT * ";
		$query .= "FROM admins ";
		$query .= "WHERE id = {$safe_admin_id} ";
		$query .= "LIMIT 1 ";
		$admin_set = mysqli_query($connection, $query);
		confirm_query($admin_set);
		if ($admin = mysqli_fetch_assoc($admin_set)) {
			return $admin;
		} else {
			return null;
		}
	}

	/* use blow fish algorithm to build encrypted password */
	function pwd_encrypt($password) {
		$hash_format = "$2y$10$"; // PHP to use Blowfish with a cost of 10
		$salt_length = 22;        // Blowfish salts should be 22 chars or more
		$salt = generate_salt($salt_length);
		$format_and_salt = $hash_format . $salt;
		$hash = crypt($password, $format_and_salt);
		return $hash;
	}

	/* create a unique random string */
	function generate_salt($length) {
		// md5 returns 32 chars
		$unique_random_string = md5(uniqid(mt_rand(), true));
		// valid chars fro a salt are [a-Z0-9./], it returns '+' rather than '.'
		$base64_string = base64_encode($unique_random_string);
		// so replace '+', then...
		$modified_base64_string = str_replace('+', '.', $base64_string);
		// trunvate string to the correct length
		$salt = substr($modified_base64_string, 0, $length);
		return $salt;
	}

	function password_check($password, $existing_hash) {
		// exsiting contains format and salt at start
		$hash = crypt($password, $existing_hash);
		if ($hash === $existing_hash) {
			return true;
		} else {
			return false;
		}
	}
?>