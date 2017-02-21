<?php
	session_start();
	include("inc/config.php");

	$check = false;

	if (isset($_POST['submitrating']) || isset($_POST['submityear'])) {
		$check = true;
		if (isset($_POST['submitrating'])) {
			$sql = "SELECT *
					FROM (SELECT m.id, m.title, m.year, ROUND(AVG(r.rating), 2) as rating
							FROM movies m 
							LEFT JOIN rated r on r.mid = m.id
							GROUP BY m.title) as p";
			if (isset($_POST['lessthanr'])) {
				$num = $_POST['lessthanr'];
				$top = "<h3 class='text-center'>Movies with a rating less than <strong>" . $num . "</strong></h3>";
				$sql .= " WHERE p.rating < $num";
			}
			else if (isset($_POST['greaterthanr'])) {
				$num = $_POST['greaterthanr'];
				$top = "<h3 class='text-center'>Movies with a rating greater than <strong>" . $num . "</strong></h3>";
				$sql .= " WHERE p.rating > $num";
			}
			else {
				$num1 = $_POST['lowr'];
				$num2 = $_POST['highr'];
				$top = "<h3 class='text-center'>Movies with a rating between <strong>" . $num1 . "</strong> and <strong>" . $num2 . "</strong></h3>";
				$sql .= " WHERE p.rating > $num1 AND p.rating < $num2";
			}

			switch ($_POST['order']) {
				case 1: $sql .= " ORDER BY p.rating DESC"; break;
				case 2: $sql .= " ORDER BY p.rating"; break;
				default: $sql .= " ORDER BY p.rating DESC"; break;
			}
		}

		if (isset($_POST['submityear'])) {
			$sql = "SELECT *
					FROM (SELECT m.id, m.title, m.year, ROUND(AVG(r.rating), 2) as rating
							FROM movies m 
							LEFT JOIN rated r on r.mid = m.id
							GROUP BY m.title) as p";
			if (isset($_POST['lessthany'])) {
				$num = $_POST['lessthany'];
				$top = "<h3 class='text-center'>Movies made before <strong>" . $num . "</strong></h3>";
				$sql .= " WHERE p.year < $num";
			}
			else if (isset($_POST['greaterthany'])) {
				$num = $_POST['greaterthany'];
				$top = "<h3 class='text-center'>Movies made after <strong>" . $num . "</strong></h3>";
				$sql .= " WHERE p.year > $num";
			}
			else {
				$num1 = $_POST['lowy'];
				$num2 = $_POST['highy'];
				$top = "<h3 class='text-center'>Movies made between <strong>" . $num1 . "</strong> and <strong>" . $num2 . "</strong></h3>";
				$sql .= " WHERE p.year > $num1 AND p.year < $num2";
			}

			switch ($_POST['order']) {
				case 1: $sql .= " ORDER BY p.year DESC"; break;
				case 2: $sql .= " ORDER BY p.year"; break;
				default: $sql .= " ORDER BY p.year DESC"; break;
			}
		}

		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		try {
			$result = mysqli_query($db, $sql);
			$count = mysqli_num_rows($result);
			$output = "<h1 class='text-center'>Search Results</h1>";
			if ($count == 0)
				$output .= "<h4 class='text-center text-danger'>No movies found.</h4>";
			else {
				$output .= "<h4 class='text-center'><strong>" . $count . "</strong> results</h4>";
				$output .= '<div class="table-responsive">
					<table class="table table-bordered table-hover">
						<thead>
						<tr>
							<th>Movie Title</th>
							<th>Year Released</th>
							<th>User Rating</th>
						</tr>
						</thead>
						<tbody>';
				while ($row = mysqli_fetch_array($result)) {
					$output .= '<tr class="link" role="button" data-href="movie.php?id=' . $row['id'] . '">';
					$output .= '<td>' . $row["title"] . '</td>';
					$output .= '<td>' . $row["year"] . '</td>';
					$output .= '<td>' . $row["rating"] . '</td>';
					$output .= '</tr>';
				}
				mysqli_close($db);
			}
		}
		catch (Exception $e) {
			echo "Could not connect to the database.";
			mysqli_close($db);
			exit();
		}
	}

	if (isset($_POST['submitpredict'])) {
		$check = true;
		$actorid = $_POST['actorid'];
		$directorid = $_POST['directorid'];
		$studioid = $_POST['studioid'];

		$sql = "SELECT ROUND(AVG(r.rating), 2)
					FROM rated r
					WHERE r.mid in (SELECT m.id
					FROM movies m
					WHERE m.id in ";

		$sqlall = "SELECT m.id, m.title, m.year
					FROM movies m
					WHERE m.id in ";

		$count = 0;

		$sqlcon = '';

		if (!empty($actorid) && is_numeric($actorid)) {
			$sqlcon .= "(SELECT ai.mid
						FROM actedin ai
						WHERE ai.aid = $actorid)";
			$sqlactor = "SELECT name FROM actors WHERE id = $actorid";
			$count++;
		}

		if (!empty($directorid) && is_numeric($directorid)) {
			if ($count > 0) $sqlcon .= " OR m.id in ";
			$sqlcon .= "(SELECT di.mid
						FROM directed di
						WHERE di.did = $directorid)";
			$sqldirector = "SELECT name FROM directors WHERE id = $directorid";
			$count++;
		}

		if (!empty($studioid) && is_numeric($studioid)) {
			if ($count > 0) $sqlcon .= " OR m.id in ";
			$sqlcon .= "(SELECT p.mid
						FROM produced p
						WHERE p.sid = $studioid)";
			$sqlstudio = "SELECT name FROM studios WHERE id = $studioid";
		}

		$sql .= $sqlcon . ")";
		$sqlall .= $sqlcon;

		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		try {
			$output = "<h1 class='text-center'>Predicted Rating</h1>";
			if (!empty($sqlactor)) {
				$resultactor = mysqli_query($db, $sqlactor);
				$rowactor = mysqli_fetch_array($resultactor);
				if (!empty($rowactor[0]))
					$output .= "<h3 class='text-center'>Actor: <strong>" . $rowactor[0] . "</strong></h3>";
			}
			if (!empty($sqldirector)) {
				$resultdirector = mysqli_query($db, $sqldirector);
				$rowdirector = mysqli_fetch_array($resultdirector);
				if (!empty($rowdirector[0]))
					$output .= "<h3 class='text-center'>Director: <strong>" . $rowdirector[0] . "</strong></h3>";
			}
			if (!empty($sqlstudio)) {
				$resultstudio = mysqli_query($db, $sqlstudio);
				$rowstudio = mysqli_fetch_array($resultstudio);
				if (!empty($rowstudio[0]))
					$output .= "<h3 class='text-center'>Studio: <strong>" . $rowstudio[0] . "</strong></h3>";
			}
			$result = mysqli_query($db, $sql);
			$row = mysqli_fetch_array($result);
			$output .= "<h1 class='text-center' style='font-size:8em'><strong>" . $row[0] . "</strong><h1>";
			$output .= "<h3 class='text-center'>Movies Used</h3>";
			$output .= "<div class='col-md-8 col-md-offset-2'><div class='table-responsive'>";
			$output .= '<table class="table table-bordered table-hover">
						<thead>
						<tr>
							<th>Movie Title</th>
							<th>Year Released</th>
						</tr>
						</thead>
						<tbody>';
			$resultmov = mysqli_query($db, $sqlall);
			while ($rowmov = mysqli_fetch_array($resultmov)) {
				$output .= '<tr class="link" role="button" data-href="movie.php?id=' . $rowmov['id'] . '">';
				$output .= '<td>' . $rowmov["title"] . '</td>';
				$output .= '<td>' . $rowmov["year"] . '</td>';
				$output .= '</tr>';
			}
			$output .= "</div></div>";
			mysqli_close($db);
		}
		catch (Exception $e) {
			echo "Data could not be retrieved from database.";
			mysqli_close($db);
			exit();
		}

	}

	if (isset($_POST['submitshared'])) {
		$check = true;
		$actor1id = $_POST['actor1id'];
		$actor2id = $_POST['actor2id'];
		$output = "<h1 class='text-center'>Search Results</h1>";

		$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

		try {
			$actres = mysqli_query($db, "SELECT name
											FROM actors
											WHERE id = $actor1id");
			$row = mysqli_fetch_array($actres);
			$output .= "<h3 class='text-center'>Actor 1: <strong>" . $row['name'] . "</strong></h3>";
			$actres = mysqli_query($db, "SELECT name
											FROM actors
											WHERE id = $actor2id");
			$row = mysqli_fetch_array($actres);
			$output .= "<h3 class='text-center'>Actor 2: <strong>" . $row['name'] . "</strong></h3>";
			$result = mysqli_query($db, "SELECT m.id, m.title, m.year
											FROM movies m
											WHERE m.id in (SELECT ai.mid
										               		FROM actedin ai
										               		WHERE ai.aid = $actor1id)
											AND m.id in (SELECT ai.mid
										             		FROM actedin ai
										             		WHERE ai.aid = $actor2id)
										    ORDER BY m.year");
			if (mysqli_num_rows($result) == 0) {
				$output .= "<br><h4 class='text-center'>These actors have not been in a movie together.</h4>";
			}
			else {
				$output .= "<div class='col-md-8 col-md-offset-2'><div class='table-responsive'>";
				$output .= '<table class="table table-bordered table-hover">
							<thead>
							<tr>
								<th>Movie Title</th>
								<th>Year Released</th>
							</tr>
							</thead>
							<tbody>';
				while ($row = mysqli_fetch_array($result)) {
					$output .= '<tr class="link" role="button" data-href="movie.php?id=' . $row['id'] . '">';
					$output .= '<td>' . $row["title"] . '</td>';
					$output .= '<td>' . $row["year"] . '</td>';
					$output .= '</tr>';
				}
			}
			mysqli_close($db);
		}
		catch (Exception $e) {
			echo "Data could not be retrieved from the database.";
			mysqli_close($db);
			exit();
		}
	}

	if (!$check) {
		header("location: advancedsearch.php");
		exit();
	}

	$title = "Search Results";
	include_once("inc/head.php");
?>
<script src="inc/tablelinks.js"></script>
<?php
	include_once("inc/nav.php");
?>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<?php if (isset($output)) echo $output; ?>
		</div>
	</div>
</div>