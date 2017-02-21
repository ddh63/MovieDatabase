<?php
	session_start();
	include("inc/config.php");

	if (isset($_POST['submit'])) {
		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		$id = mysqli_real_escape_string($db, $_GET['id']);
		$name = mysqli_real_escape_string($db, $_POST['name']);

		try {
			mysqli_query($db, "UPDATE studios SET name = '$name' WHERE id = $id");
			mysqli_close($db);
			header("location: studio.php?id=$id");
			exit();
		}
		catch (Exception $e) {
			echo "Data could not be updated in the database. try again later.";
			mysqli_close($db);
			exit();
		}
	}

	if (isset($_GET['id'])) {
		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		$id = mysqli_real_escape_string($db, $_GET['id']);

		try {
			$result = mysqli_query($db, "SELECT name 
							FROM studios
							WHERE id = $id");
			$row = mysqli_fetch_array($result);
			mysqli_close($db);
		} 
		catch (Exception $e) {
			echo "Data could not be retrieved from the database.";
			mysqli_close($db);
			exit();
		}
	}
	else {
		header("location: index.php");
		exit();
	}

	$title = "Edit " . $row['name'];
	include_once("inc/head.php");
	include_once("inc/nav.php");
?>

<div class="container">
	<form action="" method="post" style="max-width:300px; margin:0 auto; padding-top: 15px;">
		<div class="form-group">
			<label for="studioName">Studio Name</label>
			<input type="text" name="name" class="form-control" id="studioName" <?php if (isset($row['name'])) echo 'value="' . $row['name'] . '"'; else echo 'placeholder="Name"'; ?> autocomplete="off" required>
		</div>
		<button type="submit" name="submit" class="btn btn-primary">Submit</button>
	</form>
</div>