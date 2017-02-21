<?php

// Parameters
// $item = What table is being counted
// Returns the amount of rows in the table
function item_count($item) {
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	switch ($item) {
		case 1: $table = "movies"; break;
		case 2: $table = "actors"; break;
		case 3: $table = "directors"; break;
		case 4: $table = "studios"; break;
	}

	try {
		$result = mysqli_query($db, "SELECT COUNT(id) FROM $table");
		$row = mysqli_fetch_array($result);
		mysqli_close($db);
	} 
	catch (Exception $e) {
		echo "Data could not be retrieved from the database.";
		mysqli_close($db);
		exit();
	}

	return $row[0];
}

// Parameters
// $start = What item the page starts on
// $end   = What item the page ends on
// $item  = The table being accessed
// $sort  = Ascending or descending order
// $col   = Which column is being sorted
// Returns the html for a table of the results
function get_items_sub($start, $end, $item, $sort, $col) {
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$offset = $start - 1;
	$rows = $end - $start + 1;
	$output = "";

	try {
		if ($item == 1) {
			if (isset($_SESSION['username']))
				$output = '<div style="text-align:center;padding-bottom:20px;"><a href="addmovie.php" class="btn btn-info" role="button">Add Movie</a></div>';
			$output .= '<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
					<tr>
						<th>Movie Title <a href="index.php?db=' . $item . '&c=1"><span class="glyphicon glyphicon-menu-up"></span></a> <a href="index.php?db=' . $item . '&s=1&c=1"><span class="glyphicon glyphicon-menu-down"></span></a></th>
						<th>Year Released <a href="index.php?db=' . $item . '&c=2"><span class="glyphicon glyphicon-menu-up"></span></a> <a href="index.php?db=' . $item . '&s=1&c=2"><span class="glyphicon glyphicon-menu-down"></span></th>
						<th>User Rating <a href="index.php?db=' . $item . '&c=3"><span class="glyphicon glyphicon-menu-up"></span></a> <a href="index.php?db=' . $item . '&s=1&c=3"><span class="glyphicon glyphicon-menu-down"></span></th>
					</tr>
					</thead>
					<tbody>';
					
			$sql = "SELECT m.id, m.title, m.year, ROUND(AVG(r.rating), 2) as rating
										FROM movies m 
										LEFT JOIN rated r on r.mid = m.id
										GROUP BY m.title ";

			switch ($col) {
				case 1: $sql .= "ORDER BY m.title "; break;
				case 2: $sql .= "ORDER BY m.year "; break;
				case 3: $sql .= "ORDER BY rating "; break;
				default: $sql .= "ORDER BY m.title ";
			}

			if ($sort == 0)
				$sql .= "LIMIT $offset, $rows";
			else
				$sql .= "DESC LIMIT $offset, $rows";
				
			$result = mysqli_query($db, $sql);
			while ($row = mysqli_fetch_array($result)) {
				$output .= '<tr class="link" role="button" data-href="movie.php?id=' . $row['id'] . '">';
				$output .= '<td>' . $row["title"] . '</td>';
				$output .= '<td>' . $row["year"] . '</td>';
				$output .= '<td>' . $row["rating"] . '</td>';
				$output .= '</tr>';
			}
			$output .= '</tbody></table>';
		}
		else if ($item == 2) {
			if (isset($_SESSION['username']))
				$output = '<div style="text-align:center;padding-bottom:20px;"><a href="addactor.php" class="btn btn-info" role="button">Add Actor</a></div>';
			$output .= '<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
					<tr>
						<th>Name <a href="index.php?db=' . $item . '&c=1"><span class="glyphicon glyphicon-menu-up"></span></a> <a href="index.php?db=' . $item . '&s=1&c=1"><span class="glyphicon glyphicon-menu-down"></span></a></th>
						<th>Gender <a href="index.php?db=' . $item . '&c=2"><span class="glyphicon glyphicon-menu-up"></span></a> <a href="index.php?db=' . $item . '&s=1&c=2"><span class="glyphicon glyphicon-menu-down"></span></a></th>
					</tr>
					</thead>
					<tbody>';

			$sql = "SELECT id, name, gender
					FROM actors ";

			switch ($col) {
				case 1: $sql .= "ORDER BY name "; break;
				case 2: $sql .= "ORDER BY gender "; break;
				default: $sql .= "ORDER BY name "; break;
			}
			
			if ($sort == 0)
				$sql .= "LIMIT $offset, $rows";
			else
				$sql .= "DESC LIMIT $offset, $rows";

			$result = mysqli_query($db, $sql);
			while ($row = mysqli_fetch_array($result)) {
				$output .= '<tr class="link" role="button" data-href="actor.php?id=' . $row['id'] . '">';
				$output .= '<td>' . $row["name"] . '</td>';
				$output .= '<td>' . $row["gender"] . '</td>';
				$output .= '</tr>';
			}
			$output .= '</tbody></table>';
		}
		else if ($item == 3) {
			if (isset($_SESSION['username']))
				$output = '<div style="text-align:center;padding-bottom:20px;"><a href="adddirector.php" class="btn btn-info" role="button">Add Director</a></div>';
			$output .= '<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
					<tr>
						<th>Name <a href="index.php?db=' . $item . '&c=1"><span class="glyphicon glyphicon-menu-up"></span></a> <a href="index.php?db=' . $item . '&s=1"><span class="glyphicon glyphicon-menu-down"></span></a></th>
					</tr>
					</thead>
					<tbody>';

			$sql = "SELECT id, name
					FROM directors 
					ORDER BY name ";

			if ($sort == 0)
				$sql .= "LIMIT $offset, $rows";
			else
				$sql .= "DESC LIMIT $offset, $rows";

			$result = mysqli_query($db, $sql);
			while ($row = mysqli_fetch_array($result)) {
				$output .= '<tr class="link" role="button" data-href="director.php?id=' . $row['id'] . '">';
				$output .= '<td>' . $row["name"] . '</td>';
				$output .= '</tr>';
			}
			$output .= '</tbody></table>';
		}
		else {
			if (isset($_SESSION['username']))
				$output = '<div style="text-align:center;padding-bottom:20px;"><a href="addstudio.php" class="btn btn-info" role="button">Add Studio</a></div>';
			$output .= '<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
					<tr>
						<th>Name <a href="index.php?db=' . $item . '&c=1"><span class="glyphicon glyphicon-menu-up"></span></a> <a href="index.php?db=' . $item . '&s=1"><span class="glyphicon glyphicon-menu-down"></span></a></th>
					</tr>
					</thead>
					<tbody>';

			$sql = "SELECT id, name
					FROM studios
					ORDER BY name ";

			if ($sort == 0)
				$sql .= "LIMIT $offset, $rows";
			else
				$sql .= "DESC LIMIT $offset, $rows";

			$result = mysqli_query($db, $sql);
			while ($row = mysqli_fetch_array($result)) {
				$output .= '<tr class="link" role="button" data-href="studio.php?id=' . $row['id'] . '">';
				$output .= '<td>' . $row["name"] . '</td>';
				$output .= '</tr>';
			}
			$output .= '</tbody></table>';
		}

		mysqli_close($db);

	}
	catch (Exception $e) {
		echo "Data could not be retrieved from the database.";
		mysqli_close($db);
		exit();
	}

	return $output;
}

function movieInfo($id) {
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	try {
		$result = mysqli_query($db, "SELECT m.id, m.title, m.year, ROUND(AVG(r.rating), 2) as rating, COUNT(r.rating) as ratecount, di.id as did, di.name as director, s.name as producer, s.id as pid
										FROM movies m 
										LEFT JOIN rated r on r.mid = m.id
										LEFT JOIN directed d on d.mid = m.id
										LEFT JOIN directors di on di.id = d.did
                                        LEFT JOIN produced p on p.mid = m.id
                                        LEFT JOIN studios s on p.sid = s.id
										WHERE m.id = " . $id .
										" GROUP BY m.title");
		if (mysqli_num_rows($result) == 0) {
			header("location: index.php");
			mysqli_close($db);
			exit();
		}
		$row = mysqli_fetch_array($result);
		mysqli_close($db);
	}
	catch (Exception $e) {
		echo "Data could not be retrieved from the database.";
		mysqli_close($db);
		exit();
	}

	return $row;
}

function actorsInMovie($id) {
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	try {
		$result = mysqli_query($db, "SELECT a.id, a.name
										FROM actedin ai 
										INNER JOIN actors a ON ai.aid = a.id
										WHERE ai.mid = " . $id);
		$count = mysqli_num_rows($result);
		$output = "<h1 class='text-center'>Actor List</h1>";
		if ($count == 0)
			$output .= "<h3 class='text-center'>There are no known actors for this movie.</h3>";
		else {
			while ($row = mysqli_fetch_array($result)) {
				$output .= "<h4 class='text-center'><a href='actor.php?id=" . $row['id'] . "'>" . $row['name'] . "</a>";
				if (isset($_SESSION['username']))
					$output .= " <a href='movie.php?id=" . $id . "&aid=" . $row['id'] . "' class='text-warning'><span class='glyphicon glyphicon-remove'></span>";
				$output .= "</a></h4>";
			}
		}
		mysqli_close($db);
	}
	catch (Exception $e) {
		echo "Data could not be retrieved from the database.";
		mysqli_close($db);
		exit();
	}

	return $output;
}

function actorInfo($id) {
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	try {
		$result = mysqli_query($db, "SELECT name, gender 
							FROM actors
							WHERE id = $id");
		if (mysqli_num_rows($result) == 0) {
			header("location: index.php");
			mysqli_close($db);
			exit();
		}
		$row = mysqli_fetch_array($result);
		mysqli_close($db);
	}
	catch (Exception $e) {
		echo "Data could not be retrieved from the database.";
		mysqli_close($db);
		exit();
	}

	return $row;
}

function actorMovieInfo($id) {
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	try {
		$result = mysqli_query($db, "SELECT m.id, m.title, m.year
							FROM actors a 
							LEFT JOIN actedin ai ON a.id = ai.aid 
							LEFT JOIN movies m on m.id = ai.mid 
							WHERE a.id = $id
							ORDER BY m.year");
		$output = "<h1 class='text-center'>Movies Starred In</h1>";
		$row = mysqli_fetch_array($result);
		do {
			if (is_null($row['id'])) {
				$output .= "<h3 class='text-center'>There are no known movies for this actor.</h3>";
				break;
			}
			$output .= "<h4 class='text-center'><a href='movie.php?id=" . $row['id'] . "'>" . $row['title'] . " (" . $row['year'] . ")</a>";
			if (isset($_SESSION['username']))
				$output .= " <a href='actor.php?id=" . $id . "&mid=" . $row['id'] . "' class='text-warning'><span class='glyphicon glyphicon-remove'></span>";
			$output .= "</a></h4>";
		} while ($row = mysqli_fetch_array($result));
		mysqli_close($db);
	}
	catch (Exception $e) {
		echo "Data could not be retrieved from the database.";
		mysqli_close($db);
		exit();
	}

	return $output;
}

function directorInfo($id) {
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	try {
		$result = mysqli_query($db, "SELECT name 
							FROM directors
							WHERE id = $id");
		if (mysqli_num_rows($result) == 0) {
			header("location: index.php");
			mysqli_close($db);
			exit();
		}
		$row = mysqli_fetch_array($result);
		mysqli_close($db);
	}
	catch (Exception $e) {
		echo "Data could not be retrieved from the database.";
		mysqli_close($db);
		exit();
	}

	return $row;
}

function directorMovieInfo($id) {
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	try {
		$result = mysqli_query($db, "SELECT m.id, m.title, m.year
							FROM directors d
							LEFT JOIN directed di ON d.id = di.did 
							LEFT JOIN movies m on m.id = di.mid 
							WHERE d.id = $id
							ORDER BY m.year");
		$output = "<h1 class='text-center'>Movies Directed</h1>";
		$row = mysqli_fetch_array($result);
		do {
			if (is_null($row['id'])) {
				$output .= "<h3 class='text-center'>There are no known movies for this director.</h3>";
				break;
			}
			$output .= "<h4 class='text-center'><a href='movie.php?id=" . $row['id'] . "'>" . $row['title'] . " (" . $row['year'] . ")</a>";
			if (isset($_SESSION['username']))
				$output .= " <a href='director.php?id=" . $id . "&mid=" . $row['id'] . "' class='text-warning'><span class='glyphicon glyphicon-remove'></span>";
			$output .= "</a></h4>";
		} while ($row = mysqli_fetch_array($result));
		mysqli_close($db);
	}
	catch (Exception $e) {
		echo "Data could not be retrieved from the database.";
		mysqli_close($db);
		exit();
	}

	return $output;
}

function studioInfo($id) {
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	try {
		$result = mysqli_query($db, "SELECT name 
							FROM studios
							WHERE id = $id");
		if (mysqli_num_rows($result) == 0) {
			header("location: index.php");
			mysqli_close($db);
			exit();
		}
		$row = mysqli_fetch_array($result);
		mysqli_close($db);
	}
	catch (Exception $e) {
		echo "Data could not be retrieved from the database.";
		mysqli_close($db);
		exit();
	}

	return $row;
}

function studioMovieInfo($id) {
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	try {
		$result = mysqli_query($db, "SELECT m.id, m.title, m.year
							FROM studios s
							LEFT JOIN produced p ON s.id = p.sid 
							LEFT JOIN movies m on m.id = p.mid 
							WHERE s.id = $id
							ORDER BY m.year");
		$output = "<h1 class='text-center'>Movies Produced</h1>";
		$row = mysqli_fetch_array($result);
		do {
			if (is_null($row['id'])) {
				$output .= "<h3 class='text-center'>There are no known movies for this studio.</h3>";
				break;
			}
			$output .= "<h4 class='text-center'><a href='movie.php?id=" . $row['id'] . "'>" . $row['title'] . " (" . $row['year'] . ")</a>";
			if (isset($_SESSION['username']))
				$output .= " <a href='studio.php?id=" . $id . "&mid=" . $row['id'] . "' class='text-warning'><span class='glyphicon glyphicon-remove'></span>";
			$output .= "</a></h4>";
		} while ($row = mysqli_fetch_array($result));
		mysqli_close($db);
	}
	catch (Exception $e) {
		echo "Data could not be retrieved from the database.";
		mysqli_close($db);
		exit();
	}

	return $output;
}

function getUser($id) {
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	try {
		$result = mysqli_query($db, "SELECT uname FROM users WHERE id = $id");
		if (mysqli_num_rows($result) == 0) {
			header("location: index.php");
			mysqli_close($db);
			exit();
		}
		$row = mysqli_fetch_array($result);
		mysqli_close($db);

	}
	catch (Exception $e) {
		echo "Could not connect to the database.";
		mysqli_close($db);
		exit();
	}

	return $row;
}

function getUserRatings($id) {
	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

	try {
		$result = mysqli_query($db, "SELECT m.id, m.title, r.rating
										FROM movies m, rated r 
										WHERE r.mid = m.id
										AND r.uid = $id
										ORDER BY r.entry DESC");
		$count = mysqli_num_rows($result);
		$output = "";
		if ($count == 0) {
			$output .= "<h3 class='text-center'>This user has not rated any movies</h3>";
			return $output;
		}
		else {
			if (isset($_SESSION['userid']) && $id == $_SESSION['userid'])
				$output .= "<h3 class='text-center'>You have rated <strong>";
			else
				$output .= "<h3 class='text-center'>This user has rated <strong>";

			$output .= $count . "</strong> movies.</h3>";
			$output .= "<h1 class='text-center'>Recent Ratings</h1>";
			$output .= '<div class="table-responsive">
						<table class="table table-bordered table-hover">
							<thead>
								<tr>
									<th>Movie Title</th>
									<th>Rating</th>
								</tr>
							</thead>
							<tbody>';
			$max25 = 1;
			while ($max25 <= 25 && $row = mysqli_fetch_array($result)) {
				$output .= '<tr class="link" role="button" data-href="movie.php?id=' . $row['id'] . '">';
				$output .= '<td>' . $row["title"] . '</td>';
				$output .= '<td>' . $row["rating"] . '</td>';
				$output .= '</tr>';
				$max25++;
			}
		}
		mysqli_close($db);
	}
	catch (Exception $e) {
		echo "Could bot connect to the database.";
		mysqli_close($db);
		exit();
	}

	return $output;
}

?>