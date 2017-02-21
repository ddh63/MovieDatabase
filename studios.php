<?php
	session_start();
	$title = "Studios";
	include_once("inc/head.php");
?>
<script src="inc/tablelinks.js"></script>
<?php
	include_once("inc/nav.php");

		// Shows all of the movies by default
	include("inc/studiosearch.php");
?>

<div class="container">
	<div class="row">
		<div class="col-md-12">

			<h1 class="text-center">Search Studio Database</h1>

			<div class="col-md-offset-3 col-md-6">
				<div class="form-group">
					<input type="text" class="form-control search-size center-block" id="search" placeholder="Search...">
				</div>
			</div>
	
		</div>
		<div class="col-md-12">
			<div id="res"><?php echo $output ?></div>
		</div>
	</div>
</div>

</body>
</html>

<script>
$(document).ready(function() {
	$("#search").keyup(function() {
		var text = $(this).val();
		$.ajax({
			url: "inc/studiosearch.php",
			method: "post",
			data: {text:text},
			dataType: "text",
			success: function(data) {
				$("#res").html(data);
			}
		});
	});
});
</script>