<?php
	session_start();
	include("inc/config.php");

	if (isset($_POST['submit'])) {
		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		$id = mysqli_real_escape_string($db, $_GET['id']);
		$actorid = mysqli_real_escape_string($db, $_POST['actorid']);

		try {
			$result = mysqli_query($db, "SELECT * FROM actedin WHERE mid = $id AND aid = $actorid");
			if (mysqli_num_rows($result) != 0) {
				$_SESSION['error'] = "That actor is already in this movie";
				mysqli_close($db);
			}
			else {
				mysqli_query($db, "INSERT INTO actedin (mid, aid) VALUES ($id, $actorid)");
				mysqli_close($db);
				header("location: movie.php?id=$id");
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
			$result = mysqli_query($db, "SELECT title FROM movies WHERE id = $id");
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

	$title = "Add actor to " . $row['title'];
	include_once("inc/head.php");
	include_once("inc/nav.php");
?>
<div class="container">
	<h3 class="text-center">Add actor to <strong><?php echo $row['title']; ?></strong> cast.</h3>
	<?php if (isset($_SESSION['error'])) { echo "<h4 class='text-danger text-center'>" . $_SESSION['error'] . "</h4>"; unset($_SESSION['error']); } ?>
	<form action="" method="post" style="max-width:300px; margin:0 auto; padding-top: 15px;">
		<div class="form-group">
			<label for="actorID">Actor ID</label>
			<input type="text" name="actorid" class="form-control" id="actorID" autocomplete="off" required>
		</div>
		<div id="act"></div>
		<button type="submit" name="submit" class="btn btn-primary" id="button" disabled="true">Submit</button>
	</form>

	<div class="row" style="margin-top:30px;">
		<div class="col-md-4 col-md-offset-4">
			<div class="col-md-12" style="margin-bottom:20px;">
				<button type="button" name="actor" id="actor" class="btn btn-default btn-block">Search Actors for ID</button>
			</div>
			<div class="searchhide" id="searchhideactor">
				<div class="form-group">
					<input type="text" class="form-control search-size center-block" id="actoridsearch" placeholder="Search Actors">
				</div>
				<div id="actoridstuff"></div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<script>
$(document).ready(function() {
	$("#actorID").keyup(function() {
		var text = $(this).val();
		$.ajax({
			url: "inc/actorupdate.php",
			method: "post",
			data: {text:text},
			dataType: "text",
			success: function(data) {
				$("#act").html(data);
				if (data.indexOf("with this ID.") != -1 || data.indexOf("must be a number") != -1)
					$('#button').attr('disabled', true);
				else
					$('#button').attr('disabled', false);
			}
		});
	});

	$("#actoridsearch").keyup(function() {
		var text = $(this).val();
		var check = 2;
		$.ajax({
			url: "inc/idcheck.php",
			method: "post",
			data: {text:text, check:check},
			dataType: "text",
			success: function(data) {
				$("#actoridstuff").html(data);
			}
		});
	});

	$("#actor").on("click", function() {
		$("#searchhideactor").toggleClass("searchhide");
	});
});
</script>
