<?php

	define("DB_SERVER", "localhost");
	define("DB_USER", "wenbin");
	define("DB_PASS", "wen");
	define("DB_NAME", "widget_corp");
	// create connection
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	// test if connected
	if (mysqli_connect_errno()) {
		die("Database connection failed: " . 
			mysqli_connect_error() .
			" (" . mysqli_connect_errno() . ") "
			);
	}
	$var = "wenbin";
?>