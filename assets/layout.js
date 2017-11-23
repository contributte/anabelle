/**
 * Syntax highlighting - JSON
 */
var initSyntaxHighlighting = function() {
	var jsonAreas = document.getElementById("section").getElementsByClassName("language-json");
	var httpheadersAreas = document.getElementById("section").getElementsByClassName("language-http-headers");

	for (var i = 0; i < jsonAreas.length; i++) {
		hljs.highlightBlock(jsonAreas[i]);

		var languageName = document.createElement('div');
		languageName.classList.add('language-name');
		languageName.innerText = 'JSON';

		jsonAreas[i].parentElement.appendChild(languageName);
	}

	for (var i = 0; i < httpheadersAreas.length; i++) {
		hljs.highlightBlock(httpheadersAreas[i]);

		var languageName = document.createElement('div');
		languageName.classList.add('language-name');
		languageName.innerText = 'HTTP Headers';

		httpheadersAreas[i].parentElement.appendChild(languageName);
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
		var activeLinks = document.querySelectorAll('.active[data-target]');

		for (var i = 0; i < activeLinks.length; i++) {
			activeLinks[i].classList.remove('active');
		}

		if (sectionLink.length) {
			sectionLink = sectionLink[0];

			var xhr = new XMLHttpRequest();

			xhr.addEventListener("load", function(e) {
				document.getElementById("section").innerHTML = this.response;

				sectionLink.classList.add('active');

				initSyntaxHighlighting();
			});

			xhr.open("GET", sectionLink.getAttribute("data-section-src"));
			xhr.send();
		}
	}
};

window.addEventListener("hashchange", onHashChangeRouter);
