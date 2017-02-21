<?php
	include("inc/config.php");
	session_start();

	$error = false;


	if (empty($_POST['username']) || empty($_POST['password'])) {
		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$loc = mysqli_real_escape_string($db, $_POST['page']);
		header("location: $loc");
		mysqli_close($db);
		exit();
	}

	if (!$error) {
		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$user = mysqli_real_escape_string($db, $_POST['username']);
		$pass = mysqli_real_escape_string($db, $_POST['password']);
		$loc = mysqli_real_escape_string($db, $_POST['page']);
		try {
			$result = mysqli_query($db, "SELECT * FROM users WHERE uname = '$user'");
			$row = mysqli_fetch_array($result);

			if (password_verify($pass, $row['pass'])) {
				$_SESSION['username'] = $row['uname'];
				$_SESSION['userid'] = $row['id'];
				mysqli_close($db);
				header("location: $loc");
			}
			else {
				mysqli_close($db);
				header("location: $loc");
			}
		} 
		catch (Exception $e) {
			echo "Data could not be retrieved from the database.";
			mysqli_close($db);
		}
	}

?>