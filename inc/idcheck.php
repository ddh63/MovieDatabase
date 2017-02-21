<?php
	include("config.php");

	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$output = '';

	if (isset($_POST['text'])) {
		$query = mysqli_real_escape_string($db, $_POST['text']);
	}
	if (isset($_POST['check'])) {
		$check = mysqli_real_escape_string($db, $_POST['check']);
	}

	if (empty($_POST['text']) || !is_numeric($check) || $check < 1 || $check > 4) {
		mysqli_close($db);
		echo $output;
		exit();
	}

	try {
		if ($check == 1)
			$result = mysqli_query($db, "SELECT id, title FROM movies WHERE title LIKE '%" . $query . "%'");
		elseif ($check == 2) 
			$result = mysqli_query($db, "SELECT id, name FROM actors WHERE name LIKE '%" . $query . "%'");
		elseif ($check == 3)
			$result = mysqli_query($db, "SELECT * FROM directors WHERE name LIKE '%" . $query . "%'");
		else
			$result = mysqli_query($db, "SELECT * FROM studios WHERE name LIKE '%" . $query . "%'");

		$num_rows = mysqli_num_rows($result);

		if ($num_rows == 0) {
			if ($check == 1)
				$output = "<h4>No movie with this name.</h4>";
			elseif ($check == 2)
				$output = "<h4>No actor with this name.</h4>";
			elseif ($check == 3)
				$output = "<h4>No director with this name.</h4>";
			else
				$output = "<h4>No producer with this name.</h4>";
			mysqli_close($db);
			echo $output;
			exit();
		}

		$count = 1;

		$output .=	'<div class="table-responsive">
							<table class="table table-bordered">
								<thead>
								<tr>
									<th>ID</th>';
		if ($check == 1) $output .= '<th>Title</th>';
		else $output .= '<th>Name</th>';
		$output.= '</tr>
					</thead>
					<tbody>';

		while ($count <= 10 && ($row = mysqli_fetch_array($result))) {
				$output .= '<tr>';
				$output .= '<td>' . $row["id"] . '</td>';
				if ($check == 1) $output .= '<td>' . $row["title"] . '</td>';
				else $output .= '<td>' . $row["name"] . '</td>';
				$output .= '</tr>';
				$count++;
		}
		echo $output;
		mysqli_close($db);
		exit();
	}
	catch (Exception $e) {
		echo "Could not retrieve data from database. Try again later.";
		mysqli_close($db);
		exit();
	}
?>