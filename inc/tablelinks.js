$(document).ready(function() {
	$(".disabled a").click(function(e) {
		e.preventDefault();
	});

	$(document).on("click", ".link", function() {
		window.document.location = $(this).data("href");
	});
});