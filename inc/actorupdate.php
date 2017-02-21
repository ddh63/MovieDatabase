<?php
	include("config.php");

	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$output = '';
	if (isset($_POST['text'])) {
		$query = mysqli_real_escape_string($db, $_POST['text']);
	}
	if (empty($_POST['text'])) {
		$output = "<h4>No actor ID entered</h4>";
		mysqli_close($db);
		echo $output;
		exit();
	}
	if (!is_numeric($query)) {
		$output = "<h4>Actor ID must be a number</h4>";
		mysqli_close($db);
		echo $output;
		exit();
	}

	try {
		$result = mysqli_query($db, "SELECT * FROM actors WHERE id = $query");
		if (mysqli_num_rows($result) == 0) {
			$output = "<h4>No actor with this ID.</h4>";
			mysqli_close($db);
			echo $output;
			exit();
		}

		$row = mysqli_fetch_array($result);
		$output = "<h4>" . $row['name'] . "</h4>";
		echo $output;
		mysqli_close($db);
		exit();
	}
	catch (Exception $e) {
		echo "Could not retrieve data from database. Try again later.";
		mysqli_close($db);
		exit();
	}
?>