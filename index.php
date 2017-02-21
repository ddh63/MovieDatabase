<?php
	session_start();
	$title = "Movie Database";
	$currPage = 1;
	include_once("inc/head.php");
?>

<script src="inc/tablelinks.js"></script>

<?php
	include_once("inc/nav.php");

	include("inc/config.php");
	include("inc/functions.php");

	if (empty($_GET['db']) || $_GET['db'] < 1 || $_GET['db'] > 4)
		$type = 1;
	else
		$type = $_GET['db'];

	if (empty($_GET["pg"]))
		$current_page = 1;
	else
		$current_page = $_GET["pg"];

	$count = item_count($type);
	$items_per_page = 25;
	$total_pages = ceil($count / $items_per_page);

	if ($current_page > $total_pages)
		header("Location: index.php?pg=" . $total_pages);

	if ($current_page < 1)
		header("Location: index.php");

	$start = ($current_page - 1) * $items_per_page + 1;
	$end = $current_page * $items_per_page;

	if ($end > $count)
		$end = $count;

	$items_on_page = $end - $start;

	if (isset($_GET['s']) && $_GET['s'] == 1)
		$sort = $_GET['s']; // DESC
	else
		$sort = 0; // ASC

	if ($type == 1 && isset($_GET['c'])) {
		switch ($_GET['c']) {
			case 1: $col = 1; break; // Title
			case 2: $col = 2; break; // Year
			case 3: $col = 3; break; // Rating
			default: $col = 1;
		}
	}
	else if ($type == 2 && isset($_GET['c'])) {
		switch ($_GET['c']) {
			case 1: $col = 1; break; // Name
			case 2: $col = 2; break; // Gender
			default: $col = 1;
		}
	}
	else
		$col = 1; // Director and Studio only have name + catch all

	$items = get_items_sub($start, $end, $type, $sort, $col);

?>

<div class="container">

	<div class="row">
		<div class="col-md-12">
			<?php if (isset($_SESSION['deleted'])) { echo "<h3 class='text-center'>" . $_SESSION['deleted'] . "</h3>"; unset($_SESSION['deleted']); } ?>
			<h1 class="text-center">Full Database</h1>
		</div>
		<div class="col-md-3">
			<a href="index.php?db=1"><button type="button" class="btn <?php if ($type == 1) echo 'btn-primary'; else echo 'btn-default'; ?> btn-block">Movies</button></a>
		</div>
		<div class="col-md-3">
			<a href="index.php?db=2"><button type="button" class="btn <?php if ($type == 2) echo 'btn-primary'; else echo 'btn-default'; ?> btn-block">Actors</button></a>
		</div>
		<div class="col-md-3">
			<a href="index.php?db=3"><button type="button" class="btn <?php if ($type == 3) echo 'btn-primary'; else echo 'btn-default'; ?> btn-block">Directors</button></a>
		</div>
		<div class="col-md-3">
			<a href="index.php?db=4"><button type="button" class="btn <?php if ($type == 4) echo 'btn-primary'; else echo 'btn-default'; ?> btn-block">Studios</button></a>
		</div>

		<div class="col-md-12 pad">
			<div id="here"><?php echo $items; ?></div>

			<?php if ($total_pages > 1) { ?>
			<nav aria-label="Page navigation">
			  	<ul class="pagination pagination-sm">
			    	<li <?php if ($current_page == 1) echo 'class="disabled"'; ?>>
			      		<a href="index.php?db=<?php echo $type; ?>&pg=<?php echo $current_page - 1; if ($sort == 1) echo "&s=1"; if ($col != 1) echo "&c=$col"; ?>" aria-label="Previous">
			        	<span aria-hidden="true">&laquo;</span>
			      		</a>
			    	</li>
			    	<?php for ($i = 1; $i <= $total_pages; $i++) { 
			    		echo "<li " . (($i == $current_page)?'class="active"':'') . "><a href='index.php?db=" . $type . "&pg=" . $i; 
			    		if ($sort == 1) echo "&s=1";
			    		if ($col != 1) echo "&c=" . $col; 
			    		echo "'>" . $i . "</a></li>"; 
			    	} ?>
			    	<li <?php if ($current_page == $total_pages) echo 'class="disabled"'; ?>>
			      		<a href="index.php?db=<?php echo $type; ?>&pg=<?php echo $current_page + 1; if ($sort == 1) echo "&s=1"; if ($col != 1) echo "&c=" . "$col"; ?>" aria-label="Next">
			        	<span aria-hidden="true">&raquo;</span>
			      		</a>
			    	</li>
			  	</ul>
			</nav>
			<?php } ?>
		</div>
	</div>

</div>

</body>
</html>