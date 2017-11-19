document.addEventListener("DOMContentLoaded", function(event) { 
	var buttons = document.querySelectorAll('[data-section-href]');

	for (var i = 0; i < buttons.length; i++) {
		buttons[i].addEventListener("click", function() {
			var sectionLink = this.getAttribute("data-section-href");

			document.getElementsByTagName('iframe')[0].setAttribute("src", sectionLink);
		});
	}
});