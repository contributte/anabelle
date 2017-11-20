/**
 * 1, Make nav sections clickable - load apropriate section into the iframe
 * 2, Load immediately first section from the nav
 */
document.addEventListener("DOMContentLoaded", function(event) {
	var buttons = document.querySelectorAll('[data-section-href]');

	for (var i = 0; i < buttons.length; i++) {
		buttons[i].addEventListener("click", function(event) {
			event.stopPropagation();
			event.preventDefault();

			window.location.hash = this.getAttribute("data-target");
		});
	}

	if (buttons.length) {
		if (window.location.hash) {
			onHashChangeRouter();
		} else {
			var sectionLink = buttons[0].getAttribute("data-section-href");

			document.getElementsByTagName('iframe')[0].setAttribute("src", sectionLink);
		}
	}
});

/**
 * Adjust iframe size it's content
 */
document.getElementsByTagName("iframe")[0].addEventListener("load", function() {
	this.style.height = this.contentWindow.document.body.scrollHeight + 'px';
});

/**
 * Syntax highlighting - JSON
 */
document.getElementsByTagName("iframe")[0].addEventListener("load", function() {
	var jsonAreas = this.contentWindow.document.getElementsByClassName('language-json');

	for (var i = 0; i < jsonAreas.length; i++) {
		var content = jsonAreas[i].innerHTML;
		/**
		 * Key
		 */
		content = content.replace(
			/"([^"]+)":/g,
			"<span class=language-json-key>&quot;$1&quot;:</span>"
		);

		/**
		 * String
		 */
		content = content.replace(
			/"([^"]+)"([^:])/g,
			"<span class=language-json-string>&quot;$1&quot;</span>$2"
		);

		/**
		 * Number
		 */
		content = content.replace(
			/([ ,:{\n])(\d+)/g,
			"$1<span class=language-json-number>$2</span>"
		);
		
		//content = content.replace(/class=language-json-([^>]+)/, "class=\"language-json-$1\"");

		jsonAreas[i].innerHTML = content;
	}
});

var onHashChangeRouter = function() {
	if (window.location.hash) {
		var hash = window.location.hash.replace(/#/, '');
		var sectionLink = document.querySelectorAll('[data-target="'+hash+'"]');

		if (sectionLink.length) {
			var sectionSrc = sectionLink[0].getAttribute("data-section-href");

			document.getElementsByTagName('iframe')[0].setAttribute("src", sectionSrc);
		}
	}
};

/**
 * Simple router
 */
window.onhashchange = function() {
	onHashChangeRouter();
};
