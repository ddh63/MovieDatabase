<?php
	session_start();

	if (!isset($_SESSION['username'])) {
		header("location: index.php");
		exit();
	}
	
	if (isset($_GET['logout'])) {
		session_destroy();
		unset($_SESSION['username']);
		unset($_SESSION['userid']);
		header("location: index.php");
	}
?>