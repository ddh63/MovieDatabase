<?php
	session_start();
	include("inc/config.php");
	include("inc/functions.php");

	if (isset($_GET['id']))
		$id = $_GET['id'];

	if (isset($_GET['del'])) {
		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		try {
			mysqli_query($db, "DELETE FROM directors WHERE id = $id");
			mysqli_close($db);
			$_SESSION['deleted'] = "Successfully deleted director.";
			header("location: index.php");
			exit();
		}
		catch (Exception $e) {
			echo "Could not make connection with the database.";
			mysqli_close($db);
			exit();
		}
	}

	// Deletes movie from actor if x was clicked
	if (isset($_GET['mid'])) {
		if (!isset($_SESSION['username']))
			break;

		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		$movieid = mysqli_real_escape_string($db, $_GET['mid']);

		try {
			mysqli_query($db, "DELETE FROM directed WHERE mid = $movieid AND did = $id");
			mysqli_close($db);
		}
		catch (Exception $e) {
			echo "Could not delete from database. Try again later.";
			mysqli_close($db);
			exit();
		}
	}

	$directed = directorMovieInfo($id);
	$directorInfo = directorInfo($id);
	$title = $directorInfo['name'];

	include_once("inc/head.php");
	include_once("inc/nav.php");
?>
<div class="container">
	<div class="row">
		<div class="jumbotron">
				<h1 class="text-center"><strong><?php echo $title; ?></strong></h1>
				<?php if (isset($_SESSION['username'])) { ?>
					<h5 class="text-center edit"><a href="editdirector.php?id=<?php echo $id; ?>">Edit</a>&emsp;<a href="director.php?id=<?php echo $id; ?>&del=1">Delete</a></h5>
				<?php } ?>
		</div>
		<div class="col-md-12">
			<?php echo $directed; ?>
		</div>
	</div>
</div>