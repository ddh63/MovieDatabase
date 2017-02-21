<?php
	session_start();
	include("inc/config.php");

	if (isset($_POST['submit'])) {
		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		$id = mysqli_real_escape_string($db, $_GET['id']);
		$movieid = mysqli_real_escape_string($db, $_POST['movieid']);

		try {
			$result = mysqli_query($db, "SELECT * FROM actedin WHERE mid = $movieid AND aid = $id");
			if (mysqli_num_rows($result) != 0) {
				$_SESSION['error'] = "That actor is already in this movie";
				mysqli_close($db);
			}
			else {
				mysqli_query($db, "INSERT INTO actedin (mid, aid) VALUES ($movieid, $id)");
				mysqli_close($db);
				header("location: actor.php?id=$id");
				exit();
			}
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
			$result = mysqli_query($db, "SELECT name FROM actors WHERE id = $id");
			if (mysqli_num_rows($result) == 0) {
				header("location: index.php");
				mysqli_close($db);
				exit();
			}
			$row = mysqli_fetch_array($result);
			mysqli_close($db);
		}
		catch (Exception $e) {
			echo "Data could not be retrieved from database.";
			mysqli_close($db);
			exit();
		}
	}

	$title = "Add movie to " . $row['name'];
	include_once("inc/head.php");
	include_once("inc/nav.php");
?>
<div class="container">
	<h3 class="text-center">Add movie to <strong><?php echo $row['name']; ?></strong>.</h3>
	<?php if (isset($_SESSION['error'])) { echo "<h4 class='text-danger text-center'>" . $_SESSION['error'] . "</h4>"; unset($_SESSION['error']); } ?>
	<form action="" method="post" style="max-width:300px; margin:0 auto; padding-top: 15px;">
		<div class="form-group">
			<label for="movieID">Movie ID</label>
			<input type="text" name="movieid" class="form-control" id="movieID" autocomplete="off" required>
		</div>
		<div id="mov"></div>
		<button type="submit" name="submit" class="btn btn-primary" id="button" disabled="true">Submit</button>
	</form>

	<div class="row" style="margin-top:30px;">
		<div class="col-md-4 col-md-offset-4">
			<div class="col-md-12" style="margin-bottom:20px;">
				<button type="button" name="actor" id="movie" class="btn btn-default btn-block">Search Movies for ID</button>
			</div>
			<div class="searchhide" id="searchhidemovie">
				<div class="form-group">
					<input type="text" class="form-control search-size center-block" id="movieidsearch" placeholder="Search Movies">
				</div>
				<div id="movieidstuff"></div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<script>
$(document).ready(function() {
	$("#movieID").keyup(function() {
		var text = $(this).val();
		$.ajax({
			url: "inc/movieupdate.php",
			method: "post",
			data: {text:text},
			dataType: "text",
			success: function(data) {
				$("#mov").html(data);
				if (data.indexOf("with this ID.") != -1 || data.indexOf("must be a number") != -1)
					$('#button').attr('disabled', true);
				else
					$('#button').attr('disabled', false);
			}
		});
	});

	$("#movieidsearch").keyup(function() {
		var text = $(this).val();
		var check = 1;
		$.ajax({
			url: "inc/idcheck.php",
			method: "post",
			data: {text:text, check:check},
			dataType: "text",
			success: function(data) {
				$("#movieidstuff").html(data);
			}
		});
	});

	$("#movie").on("click", function() {
		$("#searchhidemovie").toggleClass("searchhide");
	});
});
</script>