$(document).ready(function() {

	//on click signup, hide login & show reg form
	$("#signup").click(function() {
		$("#first").slideUp("slow", function() {
			$("#second").slideDown("slow");
		});

	});

	//on click signup, hide reg & show login form
	$("#signin").click(function() {
		$("#second").slideUp("slow", function() {
			$("#first").slideDown("slow");
		});

	});

});