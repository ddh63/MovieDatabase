<?php
	include("config.php");

	$db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
	$output = '';
	if (isset($_POST['text'])) $query = mysqli_real_escape_string($db, $_POST['text']);
	else $query = '';

	try {
		$result = mysqli_query($db, "SELECT id, name
										FROM studios
										WHERE name LIKE '%" . $query . "%'");
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
									<th>Name</th>
								</tr>
								</thead>
								<tbody>';

			while ($count < 25 && ($row = mysqli_fetch_array($result))) {
				$output .= '<tr class="link" role="button" data-href="studio.php?id=' . $row['id'] . '">';
				$output .= '<td>' . $row["name"] . '</td>';
				$output .= '</tr>';
				$count++;
			}
			$output .= '</tbody></table>';
			if (isset($_POST['text'])) echo $output;
			mysqli_close($db);
		}
		else {
			if (isset($_POST['text'])) echo "No Actors Found";
			mysqli_close($db);
		}
	}
	catch (Exception $e) {
		if (isset($_POST['text'])) echo "Could not connect to database";
		mysqli_close($db);
	}
?>
