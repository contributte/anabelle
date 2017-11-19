/**
 * 1, Make nav sections clickable - load apropriate section into the iframe
 * 2, Load immediately first section from the nav
 */
document.addEventListener("DOMContentLoaded", function(event) { 
	var buttons = document.querySelectorAll('[data-section-href]');

	for (var i = 0; i < buttons.length; i++) {
		buttons[i].addEventListener("click", function() {
			var sectionLink = this.getAttribute("data-section-href");

			document.getElementsByTagName('iframe')[0].setAttribute("src", sectionLink);
		});
	}

	if (buttons.length) {
		var sectionLink = buttons[0].getAttribute("data-section-href");

		document.getElementsByTagName('iframe')[0].setAttribute("src", sectionLink);
	}
});

/**
 * Adjust iframe size it's content
 */
document.getElementsByTagName("iframe")[0].addEventListener("load", function() {
	this.style.height = this.contentWindow.document.body.scrollHeight + 'px';
})
