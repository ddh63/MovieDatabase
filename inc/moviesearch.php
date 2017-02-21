<?php
	include("config.php");

	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$output = '';
	if (isset($_POST['text'])) $query = mysqli_real_escape_string($db, $_POST['text']);
	else $query = '';

	try {
		$result = mysqli_query($db, "SELECT m.id, m.title, m.year, ROUND(AVG(r.rating), 2) as rating
										FROM movies m 
										LEFT JOIN rated r on r.mid = m.id
										WHERE m.title LIKE '%" . $query . "%' 
										GROUP BY m.title");
		$num_rows = mysqli_num_rows($result);
		$count = 1;
		if ($num_rows > 0) {
			$output .= '<p>There are <strong>' . $num_rows . '</strong> results.';
			if ($num_rows > 25)
				$output .= ' Showing <strong>25</strong> of them.';

			$output .=	'</p><div class="table-responsive">
							<table class="table table-bordered table-hover">
								<thead>
								<tr>
									<th>Movie Name</th>
									<th>Year Released</th>
									<th>User Rating</th>
								</tr>
								</thead>
								<tbody>';


			while ($count < 25 && ($row = mysqli_fetch_array($result))) {
				$output .= '<tr class="link" role="button" data-href="movie.php?id=' . $row['id'] . '">';
				$output .= '<td>' . $row["title"] . '</td>';
				$output .= '<td>' . $row["year"] . '</td>';
				$output .= '<td>' . $row["rating"] . '</td>'; 
				$output .= '</tr>';
				$count++;
			}
			$output .= '</tbody></table>';
			if (isset($_POST['text'])) echo $output;
			mysqli_close($db);
		}
		else {
			if (isset($_POST['text'])) echo "No Movies Found";
			mysqli_close($db);
		}
	}
	catch (Exception $e) {
		if (isset($_POST['text'])) echo "Could not connect to database";
		mysqli_close($db);
	}
?>
