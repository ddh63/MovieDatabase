<?php
	session_start();
	include("inc/config.php");
	include("inc/functions.php");

	if (isset($_GET['id']))
		$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
	else {
		header("location: index.php");
		exit();
	}

	if (!$id) {
		header("location: index.php");
		exit();
	}

	if (isset($_SESSION['username']) && $_SESSION['userid'] == $id) {
		$username = $_SESSION['username'];
		$title = $username . " profile";
	}
	else {
		$user = getUser($id);
		$username = $user['uname'];
		$title = $username . " profile";
	}

	$userRatings = getUserRatings($id);

	include_once("inc/head.php");
?>
<script src="inc/tablelinks.js"></script>
<?php
	include_once("inc/nav.php");
?>
<div class="container">
	<div class="row">
		<div class="jumbotron">
			<h1 class="text-center"><strong><?php echo $username; ?></strong></h1>
		</div>
	</div>
	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<?php echo $userRatings; ?>
		</div>
	</div>
</div>