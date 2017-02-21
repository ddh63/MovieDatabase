<?php
	session_start();
	include("inc/config.php");

	if (isset($_POST['submit'])) {
		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		$id = mysqli_real_escape_string($db, $_GET['id']);
		$name = mysqli_real_escape_string($db, $_POST['name']);
		$gender = mysqli_real_escape_string($db, $_POST['gender']);

		try {
			mysqli_query($db, "UPDATE actors SET name = '$name', gender = '$gender' WHERE id = $id");
			mysqli_close($db);
			header("location: actor.php?id=$id");
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
			$result = mysqli_query($db, "SELECT id, name, gender 
							FROM actors
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

	$gender = $row['gender'];
	
	switch ($gender) {
		case 'M': $gender = 1; break;
		case 'F': $gender = 2; break;
		default: $gender = 3;
	}

	$title = "Edit " . $row['name'];
	include_once("inc/head.php");
	include_once("inc/nav.php");
?>

<div class="container">
	<form action="" method="post" style="max-width:300px; margin:0 auto; padding-top: 15px;">
		<div class="form-group">
			<label for="actorName">Actor Name</label>
			<input type="text" name="name" class="form-control" id="actorName" <?php if (isset($row['name'])) echo 'value="' . $row['name'] . '"'; else echo 'placeholder="Name"'; ?> autocomplete="off" required>
		</div>
		<label>Gender</label>
		<div class="radio">
			<label>
				<input type="radio" name="gender" value="M" <?php if ($gender == 1 OR $gender == 3) echo "checked"; ?>>
				Male
			</label>
		</div>
		<div class="radio">
			<label>
				<input type="radio" name="gender" value="F" <?php if ($gender == 2) echo "checked"; ?>>
				Female
			</label>
		</div>
		<button type="submit" name="submit" class="btn btn-primary">Submit</button>
	</form>
</div>