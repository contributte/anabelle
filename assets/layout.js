/**
 * Syntax highlighting - JSON
 */
var initSyntaxHighlighting = function() {
	var jsonAreas = document.getElementById("section").getElementsByClassName("language-json");

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
};

/**
 * 1, Make nav sections clickable - load apropriate section into the iframe
 * 2, Load immediately first section from the nav
 */
document.addEventListener("DOMContentLoaded", function(event) {
	var buttons = document.querySelectorAll("[data-section-src]");

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
			window.location.hash = buttons[0].getAttribute("data-target");
		}
	}
});

/**
 * Simple router
 */
var onHashChangeRouter = function() {
	if (window.location.hash) {
		var hash = window.location.hash.replace(/#/, "");
		var sectionLink = document.querySelectorAll('[data-target="'+hash+'"]');

		if (sectionLink.length) {
			var xhr = new XMLHttpRequest();

			xhr.addEventListener("load", function(e) {
				document.getElementById("section").innerHTML = this.response;
				initSyntaxHighlighting();
			});

			xhr.open("GET", sectionLink[0].getAttribute("data-section-src"));
			xhr.send();
		}
	}
};

window.addEventListener("hashchange", onHashChangeRouter);
