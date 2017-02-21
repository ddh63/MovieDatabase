<?php
	session_start();
	include("inc/config.php");

	if (isset($_POST['submit'])) {
		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		$id = mysqli_real_escape_string($db, $_GET['id']);
		$name = mysqli_real_escape_string($db, $_POST['name']);

		try {
			mysqli_query($db, "INSERT INTO studios (name) VALUES ('$name')");
			$result = mysqli_query($db, "SELECT id FROM studios WHERE name = '$name'");
			$row = mysqli_fetch_array($result);
			$id = $row['id'];
			mysqli_close($db);
			header("location: studio.php?id=$id");
			exit();
		}
		catch (Exception $e) {
			echo "Data could not be added to the database. try again later.";
			mysqli_close($db);
			exit();
		}
	}

	$title = "Add Studio";
	include_once("inc/head.php");
	include_once("inc/nav.php");
?>

<div class="container">
	<form action="" method="post" style="max-width:300px; margin:0 auto; padding-top: 15px;">
		<div class="form-group">
			<label for="studioName">Studio Name</label>
			<input type="text" name="name" class="form-control" id="studioName" placeholder="Name" autocomplete="off" required>
		</div>
		<button type="submit" name="submit" class="btn btn-primary">Submit</button>
	</form>
</div>