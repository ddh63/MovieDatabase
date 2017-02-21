<?php
	session_start();
	include("inc/config.php");

	$currPage = 2;
	$title = "Advanced Search";
	include_once("inc/head.php");
	include_once("inc/nav.php");
?>
<div class="container">

	<h3 class="text-center">Search movie rating range</h3>
	<h4 class="text-center text-danger" id="ratingerror"></h4>
	<form action="results.php" method="post" id="ratingform" style="max-width:300px; margin:0 auto; padding-top: 15px;">
		<select class="form-control" id="searchrating" name="ratingrange">
			<option value="1">&lt;</option>
			<option value="2">&gt;</option>
			<option value="3">X &lt; Rating &lt; Y</option>
		</select>
		<div class="form-group" id="formratingstuff" style="padding-top:5px;">
			<label for='ltr'>Less Than</label>
			<input type='text' name='lessthanr' class='form-control' id='ltr' autocomplete='off' required>
		</div>
		<div class="radio">
			<label>
				<input type="radio" name="order" value="1" checked>
				Descending Order
			</label>
		</div>
		<div class="radio">
			<label>
				<input type="radio" name="order" value="2">
				Ascending Order
			</label>
		</div>
		<button type="submit" name="submitrating" class="btn btn-primary">Submit</button>
	</form>

	<hr>

	<h3 class="text-center">Search movie year range</h3>
	<h4 class="text-center text-danger" id="yearerror"></h4>
	<form action="results.php" method="post" id="yearform" style="max-width:300px; margin:0 auto; padding-top: 15px;">
		<select class="form-control" id="searchyear" name="yearrange">
			<option value="1">&lt;</option>
			<option value="2">&gt;</option>
			<option value="3">X &lt; Year &lt; Y</option>
		</select>
		<div class="form-group" id="formyearstuff" style="padding-top:5px;">
			<label for='lty'>Less Than</label>
			<input type='text' name='lessthany' class='form-control' id='lty' autocomplete='off' required>
		</div>
		<div class="radio">
			<label>
				<input type="radio" name="order" value="1" checked>
				Descending Order
			</label>
		</div>
		<div class="radio">
			<label>
				<input type="radio" name="order" value="2">
				Ascending Order
			</label>
		</div>
		<button type="submit" name="submityear" class="btn btn-primary" id="button">Submit</button>
	</form>

	<hr>

	<h3 class="text-center">Predict movie rating</h3>
	<h4 class="text-center text-danger" id="predicterror"></h4>
	<form action="results.php" method="post" id="predictform" style="max-width:300px; margin:0 auto; padding-top: 15px;">
		<div class="form-group">
			<label for="actorID">Actor ID</label>
			<input type="text" name="actorid" class="form-control" id="actorID" autocomplete="off">
		</div>
		<div id="act"></div>
		<div class="form-group">
			<label for="actorID">Director ID</label>
			<input type="text" name="directorid" class="form-control" id="directorID" autocomplete="off">
		</div>
		<div id="dir"></div>
		<div class="form-group">
			<label for="actorID">Producer ID</label>
			<input type="text" name="studioid" class="form-control" id="studioID" autocomplete="off">
		</div>
		<div id="pro"></div>
		<button type="submit" name="submitpredict" class="btn btn-primary" id="button">Submit</button>
	</form>

	<div class="row" style="margin-top:30px;">
		<div class="col-md-8 col-md-offset-2">
			<div class="col-md-4" style="margin-bottom:20px;">
				<button type="button" name="actor" id="actor" class="btn btn-default btn-block">Search Actors for ID</button>
			</div>
			<div class="col-md-4">
				<button type="button" name="direct" id="direct" class="btn btn-default btn-block">Search Directors for ID</button>
			</div>
			<div class="col-md-4" style="margin-bottom:20px;">
				<button type="button" name="studio" id="studio" class="btn btn-default btn-block">Search Producers for ID</button>
			</div>

			<div class="searchhide" id="searchhideactor">
				<div class="form-group">
					<input type="text" class="form-control search-size center-block" id="actoridsearch" placeholder="Search Actors">
				</div>
				<div id="actoridstuff"></div>
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

	<hr>

	<h3 class="text-center">Actors in same movie</h3>
	<h4 class="text-center text-danger" id="sharederror"></h4>
	<form action="results.php" method="post" id="sharedform" style="max-width:300px; margin:0 auto; padding-top: 15px;">
		<div class="form-group">
			<label for="actor1ID">Actor 1 ID</label>
			<input type="text" name="actor1id" class="form-control" id="actor1ID" autocomplete="off">
		</div>
		<div id="act1"></div>
		<div class="form-group">
			<label for="actor2ID">Actor 2 ID</label>
			<input type="text" name="actor2id" class="form-control" id="actor2ID" autocomplete="off">
		</div>
		<div id="act2"></div>
		<button type="submit" name="submitshared" class="btn btn-primary" id="button">Submit</button>
	</form>

</div>
<script>
$(document).ready(function() {
	$('#searchrating').change(function() {
		var $str = "";
		var $value = $(this).val();
		if ($value == 1) {
			$str += "<label for='ltr'>Less Than</label>";
			$str += "<input type='text' name='lessthanr' class='form-control' id='ltr' autocomplete='off' required>";
		}
		else if ($value == 2) {
			$str += "<label for='gtr'>Greater Than</label>";
			$str += "<input type='text' name='greaterthanr' class='form-control' id='gtr' autocomplete='off' required>";
		}
		else {
			$str += "<label for='lowerr'>Range From</label>";
			$str += "<input type='text' name='lowr' class='form-control' id='lowerr' autocomplete='off' required>";
			$str += "<label for='higherr'>To</label>";
			$str += "<input type='text' name='highr' class='form-control' id='higherr' autocomplete='off' required>";
		}
		$('#formratingstuff').html($str);
	});

	$("#ratingform").submit("click", function(e) {
		var val = $("#searchrating").val();
		if (val == 1) {
			var num = $("#ltr").val();
			if (isNaN(num)) {
				$("#ratingerror").text("Not a number");
				return false;
			}
			if (num < 1 || num > 5) {
				$("#ratingerror").text("Out of range");
				return false;
			}
		}
		else if (val == 2) {
			var num = $("#gtr").val();
			if (isNaN(num)) {
				$("#ratingerror").text("Not a number");
				return false;
			}
			if (num < 1 || num > 5) {
				$("#ratingerror").text("Out of range");
				return false;
			}
		}
		else {
			var num1 = $("#lowerr").val();
			var num2 = $("#higherr").val();
			if (isNaN(num1) || isNaN(num2)) {
				$("#ratingerror").text("Not a number");
				return false;
			}
			if ((num1 < 1 || num1 > 5) || (num2 < 1 || num2 > 5)) {
				$("#ratingerror").text("Out of range");
				return false;
			}
			if (num1 > num2) {
				$("#ratingerror").text("First number must be less than second");
				return false;
			}
		}
	});

	$('#searchyear').change(function() {
		var $str = "";
		var $value = $(this).val();
		if ($value == 1) {
			$str += "<label for='lty'>Less Than</label>";
			$str += "<input type='text' name='lessthany' class='form-control' id='lty' autocomplete='off' required>";
		}
		else if ($value == 2) {
			$str += "<label for='gty'>Greater Than</label>";
			$str += "<input type='text' name='greaterthany' class='form-control' id='gty' autocomplete='off' required>";
		}
		else {
			$str += "<label for='lowery'>Range From</label>";
			$str += "<input type='text' name='lowy' class='form-control' id='lowery' autocomplete='off' required>";
			$str += "<label for='higherr'>To</label>";
			$str += "<input type='text' name='highy' class='form-control' id='highery' autocomplete='off' required>";
		}
		$('#formyearstuff').html($str);
	});

	$("#yearform").submit("click", function(e) {
		var val = $("#searchyear").val();
		if (val == 1) {
			var num = $("#lty").val();
			if (isNaN(num)) {
				$("#yearerror").text("Not a number");
				return false;
			}
		}
		else if (val == 2) {
			var num = $("#gty").val();
			if (isNaN(num)) {
				$("#yearerror").text("Not a number");
				return false;
			}
		}
		else {
			var num1 = $("#lowery").val();
			var num2 = $("#highery").val();
			if (isNaN(num1) || isNaN(num2)) {
				$("#yearerror").text("Not a number");
				return false;
			}
			if (num1 > num2) {
				$("#yearerror").text("First number must be less than second");
				return false;
			}
		}
	});

	$("#actorID").on('input', function() {
		var text = $(this).val();
		$.ajax({
			url: "inc/actorupdate.php",
			method: "post",
			data: {text:text},
			dataType: "text",
			success: function(data) {
				$("#act").html(data);
			}
		});
	});

	$("#directorID").on('input', function() {
		var text = $(this).val();
		$.ajax({
			url: "inc/directorupdate.php",
			method: "post",
			data: {text:text},
			dataType: "text",
			success: function(data) {
				$("#dir").html(data);
			}
		});
	});

	$("#studioID").on('input', function() {
		var text = $(this).val();
		$.ajax({
			url: "inc/producerupdate.php",
			method: "post",
			data: {text:text},
			dataType: "text",
			success: function(data) {
				$("#pro").html(data);
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

	$("#actor").on("click", function() {
		$("#searchhideactor").toggleClass("searchhide");

		var actor = $("#searchhideactor").hasClass("searchhide");
		var producer = $("#searchhideproducer").hasClass("searchhide");
		var director = $("#searchhidedirector").hasClass("searchhide");

		if (!producer && !actor) 
			$("#searchhideproducer").toggleClass("searchhide");
		if (!director && !actor) 
			$("#searchhidedirector").toggleClass("searchhide");
	});

	$("#direct").on("click", function() {
		$("#searchhidedirector").toggleClass("searchhide");

		var actor = $("#searchhideactor").hasClass("searchhide");
		var producer = $("#searchhideproducer").hasClass("searchhide");
		var director = $("#searchhidedirector").hasClass("searchhide");

		if (!producer && !director) 
			$("#searchhideproducer").toggleClass("searchhide");
		if (!director && !actor) 
			$("#searchhideactor").toggleClass("searchhide");
	});

	$("#studio").on("click", function() {
		$("#searchhideproducer").toggleClass("searchhide");

		var actor = $("#searchhideactor").hasClass("searchhide");
		var producer = $("#searchhideproducer").hasClass("searchhide");
		var director = $("#searchhidedirector").hasClass("searchhide");

		if (!producer && !director) 
			$("#searchhidedirector").toggleClass("searchhide");
		if (!producer && !actor) 
			$("#searchhideactor").toggleClass("searchhide");
	});

	$("#predictform").submit("click", function(e) {
		var act = $("#actorID").val();
		var dir = $("#directorID").val();
		var pro = $("#studioID").val();
		if (!act && !dir && !pro) {
			$("#predicterror").text("Must enter atleast one ID");
			return false;
		}
	});

	$("#sharedform").submit("click", function(e) {
		var act1 = $("#actor1ID").val();
		var act2 = $("#actor2ID").val();
		var checkact1 = $("#act1").text();
		var checkact2 = $("#act2").text();
		if (act1.length == 0 || act2.length == 0) {
			$("#sharederror").text("Both input fields must be filled out");
			return false;
		}
		if (isNaN(act1) || isNaN(act2)) {
			$("#sharederror").text("Both actor IDs must be numbers");
			return false;
		}
		if (act1 == act2) {
			$("#sharederror").text("Actor IDs must be different");
			return false;
		}
		if (checkact1.indexOf("with this ID.") != -1 || checkact2.indexOf("with this ID.") != -1) {
			$("#sharederror").text("Both actor IDs must have be attached to an actor");
			return false;
		}

	});

	$("#actor1ID").on('input', function() {
		var text = $(this).val();
		$.ajax({
			url: "inc/actorupdate.php",
			method: "post",
			data: {text:text},
			dataType: "text",
			success: function(data) {
				$("#act1").html(data);
			}
		});
	});

	$("#actor2ID").on('input', function() {
		var text = $(this).val();
		$.ajax({
			url: "inc/actorupdate.php",
			method: "post",
			data: {text:text},
			dataType: "text",
			success: function(data) {
				$("#act2").html(data);
			}
		});
	});

});
</script>