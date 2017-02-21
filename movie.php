<?php
	session_start();
	include("inc/config.php");
	include("inc/functions.php");

	if (isset($_GET['id']))
		$id = $_GET['id'];

	if (isset($_GET['del'])) {
		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		try {
			mysqli_query($db, "DELETE FROM movies WHERE id = $id");
			mysqli_close($db);
			$_SESSION['deleted'] = "Successfully deleted movie.";
			header("location: index.php");
			exit();
		}
		catch (Exception $e) {
			echo "Could not make connection with the database.";
			mysqli_close($db);
			exit();
		}
	}

	// Checks if user has rated the movie already
	// Also deletes rating if user clicks the change link
	if (isset($_SESSION['username'])) {
		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		$alreadyRated = false;

		try {
			$result = mysqli_query($db, "SELECT * FROM rated WHERE uid = " . $_SESSION['userid'] . " AND mid = " . $id);
			$count = mysqli_num_rows($result);
			if ($count != 0) {
				$alreadyRated = true;
				$row = mysqli_fetch_array($result);
				$userRating = $row['rating'];

				if (isset($_GET['delRating']) && $_GET['delRating'] == 1) {
					mysqli_query($db, "DELETE FROM rated WHERE uid = " . $_SESSION['userid'] . " AND mid = " . $id);
					$alreadyRated = false;
				}
			}
			mysqli_close($db);
		}
		catch (Exception $e) {
			echo "Could not retrieve data from database.";
			mysqli_close($db);
			exit();
		}
	}

	// Used when a user rates the movie
	if (isset($_POST['rate'])) {
		if (!isset($_SESSION['username']))
			break;

		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		try {
			mysqli_query($db, "INSERT INTO rated(`uid`, `mid`, `rating`) VALUES (" . $_SESSION['userid'] . "," . $id . "," . $_POST['rate'] . ")");
			$userRating = $_POST['rate'];
			$alreadyRated = true;
			mysqli_close($db);
		}
		catch (Exception $e) {
			echo "Could not add rating to database. Try again later.";
			mysqli_close($db);
			exit();
		}
	}

	// Deletes actor from movie if x was clicked
	if (isset($_GET['aid'])) {
		if (!isset($_SESSION['username']))
			break;

		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		$actorid = mysqli_real_escape_string($db, $_GET['aid']);

		try {
			mysqli_query($db, "DELETE FROM actedin WHERE mid = $id AND aid = $actorid");
			mysqli_close($db);
		}
		catch (Exception $e) {
			echo "Could not delete from database. Try again later.";
			mysqli_close($db);
			exit();
		}
	}

	$movie = movieInfo($id);
	$title = $movie['title'];
	$year = $movie['year'];
	$rating = $movie['rating'];
	$ratingCount = $movie['ratecount'];
	$director = $movie['director'];
	$did = $movie['did'];
	$producer = $movie['producer'];
	$pid = $movie['pid'];

	$actedIn = actorsInMovie($id);

	include_once("inc/head.php");
	include_once("inc/nav.php");
?>

<div class="container">
	<div class="row">
		<div class="col-md-12">

			<div class="jumbotron">
					<h1 class="text-center"><strong><?php echo $title; ?></strong> (<?php echo $year; ?>)</h1>
					<h3 class="text-center">Directed by <strong><a href="director.php?id=<?php echo $did; ?>"><?php echo $director; ?></a></strong></h3>
					<h4 class="text-center">Produced by <strong><a href="studio.php?id=<?php echo $pid; ?>"><?php echo $producer; ?></a></strong></h4>
					<?php if (isset($_SESSION['username'])) { ?>
						<h5 class="text-center edit"><a href="editmovie.php?id=<?php echo $id; ?>">Edit</a>&emsp;<a href="movie.php?id=<?php echo $id; ?>&del=1">Delete</a></h5>
					<?php } ?>
			</div>
			<div class="col-md-6">
				<h3>Currently rated: <strong><?php echo $rating; ?></strong> / 5 by <?php echo $ratingCount; ?> user<?php if ($ratingCount != 1) echo "s"; ?>.</h3>
			</div>
			<div class="col-md-6">
			<?php if (isset($_SESSION['username'])) { 
					if (!$alreadyRated) { ?>
					<form action="movie.php?id=<?php echo $id; ?>" method="post">
					<h3 class="rate-text">Give this movie a rating: </h3>
						<select class="form-control rate-menu" name="rate">
							<option value="1">1</option>
							<option value="2">2</option>
							<option value="3">3</option>
							<option value="4">4</option>
							<option value="5">5</option>
						</select>
					<button type="submit" class="btn btn-primary">Rate</button>
					</form>
			<?php }
					else { ?>
						<h3>You gave this movie a <strong><?php echo $userRating; ?></strong> <a href="movie.php?id=<?php echo $id; ?>&delRating=1">Change</a></h3>
			<?php	}
					}
				else { ?>
					<h3>Login to rate this movie.</h3>
			<?php } ?>
			</div>
			<div class="col-md-12">
				<hr>
				<?php echo $actedIn; ?>
				<?php if (isset($_SESSION['username'])) { ?>
					<div class="text-center">
						<a href="addactormovie.php?id=<?php echo $id; ?>" class="btn btn-primary" role="button">Add Actor</a>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>