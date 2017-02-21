<?php
	session_start();
	include("inc/config.php");

	if (isset($_POST['submit'])) {
		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		$id = mysqli_real_escape_string($db, $_GET['id']);
		$title = mysqli_real_escape_string($db, $_POST['title']);
		$year = mysqli_real_escape_string($db, $_POST['year']);
		$did = mysqli_real_escape_string($db, $_POST['directorid']);
		$pid = mysqli_real_escape_string($db, $_POST['producerid']);

		try {
			mysqli_query($db, "UPDATE movies SET title = '$title', year = $year WHERE id = $id");
			$dircheck = mysqli_query($db, "SELECT * FROM directed WHERE mid = $id");
			if (mysqli_num_rows($dircheck) == 0)
				mysqli_query($db, "INSERT INTO directed VALUES ($id, $did)");
			else
				mysqli_query($db, "UPDATE directed SET did = $did WHERE mid = $id");
			$stucheck = mysqli_query($db, "SELECT * FROM produced WHERE mid = $id");
			if (mysqli_num_rows($stucheck) == 0)
				mysqli_query($db, "INSERT INTO produced VALUES ($id, $pid)");
			else
				mysqli_query($db, "UPDATE produced SET sid = $pid WHERE mid = $id");
			mysqli_close($db);
			header("location: movie.php?id=$id");
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
			$result = mysqli_query($db, "SELECT m.id, m.title, m.year, d.id as did, d.name, s.id as sid, s.name as studio 
											FROM movies m 
											LEFT JOIN directed di ON m.id = di.mid 
											LEFT JOIN directors d ON d.id = di.did 
											LEFT JOIN produced p ON m.id = p.mid
                                            LEFT JOIN studios s ON p.sid = s.id
											WHERE m.id = $id");
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

	$title = "Edit " . $row['title'];
	include_once("inc/head.php");
	include_once("inc/nav.php");
?>
<div class="container">
	<form action="" method="post" style="max-width:300px; margin:0 auto; padding-top: 15px;">
		<div class="form-group">
			<label for="movieTitle">Movie Title</label>
			<input type="text" name="title" class="form-control" id="movieTitle" <?php if (isset($row['title'])) echo 'value="' . $row['title'] . '"'; else echo 'placeholder="Title"'; ?> autocomplete="off" required>
		</div>
		<div class="form-group">
			<label for="movieYear">Year Released</label>
			<input type="text" name="year" class="form-control" id="movieYear" <?php if (isset($row['year'])) echo 'value="' . $row['year'] . '"'; else echo 'placeholder="Year"'; ?> autocomplete="off" required>
		</div>
		<div class="form-group">
			<label for="directorID">Director ID</label>
			<input type="text" name="directorid" class="form-control" id="directorID" <?php if (isset($row['did'])) echo 'value="' . $row['did'] . '"'; else echo 'placeholder="Director ID"'; ?> autocomplete="off" required>
		</div>
		<div id="dir">
			<?php if (isset($row['name'])) echo "<h4>" . $row['name'] . "</h4>"; ?>
		</div>
		<div class="form-group">
			<label for="producerID">Producer ID</label>
			<input type="text" name="producerid" class="form-control" id="producerID" <?php if (isset($row['sid'])) echo 'value="' . $row['sid'] . '"'; else echo 'placeholder="Producer ID"'; ?> autocomplete="off" required>
		</div>
		<div id="pro">
			<?php if (isset($row['studio'])) echo "<h4>" . $row['studio'] . "</h4>"; ?>
		</div>
		<button type="submit" name="submit" class="btn btn-primary" id="button">Submit</button>
	</form>
	<div class="row" style="margin-top:30px;">
		<div class="col-md-6 col-md-offset-3">
			<div class="col-md-6">
				<button type="button" name="direct" id="direct" class="btn btn-default btn-block">Search Directors for ID</button>
			</div>
			<div class="col-md-6" style="margin-bottom:20px;">
				<button type="button" name="studio" id="studio" class="btn btn-default btn-block">Search Producers for ID</button>
			</div>

			<div class="searchhide" id="searchhidedirector">
				<div class="form-group">
					<input type="text" class="form-control search-size center-block" id="directoridsearch" placeholder="Search Directors">
				</div>
				<div id="directoridstuff"></div>
			</div>

			<div class="searchhide" id="searchhideproducer">
				<div class="form-group">
					<input type="text" class="form-control search-size center-block" id="produceridsearch" placeholder="Search Producers">
				</div>
				<div id="studioidstuff"></div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
<script>
$(document).ready(function() {

	$("#directorID").keyup(function() {
		var text = $(this).val();
		$.ajax({
			url: "inc/directorupdate.php",
			method: "post",
			data: {text:text},
			dataType: "text",
			success: function(data) {
				$("#dir").html(data);
				$checkpro = $("#pro").text();
				$check = true;
				if ($checkpro.indexOf("with this ID.") != -1 || $checkpro.indexOf("must be a number") != -1)
					$check = false;

				if ((data.indexOf("with this ID.") != -1 || data.indexOf("must be a number") != -1) || !$check)
					$('#button').attr('disabled', true);
				else
					$('#button').attr('disabled', false);
			}
		});
	});

	$("#producerID").keyup(function() {
		var text = $(this).val();
		$.ajax({
			url: "inc/producerupdate.php",
			method: "post",
			data: {text:text},
			dataType: "text",
			success: function(data) {
				$("#pro").html(data);
				$checkdir = $("#dir").text();
				$check = true;
				if ($checkdir.indexOf("with this ID.") != -1 || $checkdir.indexOf("must be a number") != -1)
					$check = false;

				if ((data.indexOf("with this ID.") != -1 || data.indexOf("must be a number") != -1) || !$check)
					$('#button').attr('disabled', true);
				else
					$('#button').attr('disabled', false);
			}
		});
	});

	$("#directoridsearch").keyup(function() {
		var text = $(this).val();
		var check = 3;
		$.ajax({
			url: "inc/idcheck.php",
			method: "post",
			data: {text:text, check:check},
			dataType: "text",
			success: function(data) {
				$("#directoridstuff").html(data);
			}
		});
	});

	$("#produceridsearch").keyup(function() {
		var text = $(this).val();
		var check = 4;
		$.ajax({
			url: "inc/idcheck.php",
			method: "post",
			data: {text:text, check:check},
			dataType: "text",
			success: function(data) {
				$("#studioidstuff").html(data);
			}
		});
	});

	$("#direct").on("click", function() {
		$("#searchhidedirector").toggleClass("searchhide");

		var producer = $("#searchhideproducer").hasClass("searchhide");
		var director = $("#searchhidedirector").hasClass("searchhide");

		if (!producer && !director) 
			$("#searchhideproducer").toggleClass("searchhide");
	});

	$("#studio").on("click", function() {
		$("#searchhideproducer").toggleClass("searchhide");

		var producer = $("#searchhideproducer").hasClass("searchhide");
		var director = $("#searchhidedirector").hasClass("searchhide");

		if (!producer && !director) 
			$("#searchhidedirector").toggleClass("searchhide");
	});

});
</script>