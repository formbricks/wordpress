(function () {
	var t = document.createElement("script");
	t.type = "text/javascript";
	t.async = true;
	t.src = "https://unpkg.com/@formbricks/js@latest/dist/index.umd.js";
	var e = document.getElementsByTagName("script")[0];
	e.parentNode.insertBefore(t, e);

	// Wait for the page to fully load
	window.addEventListener("load", function () {
		window.formbricks = window.js;
		window.formbricks.init({
			environmentId: myPluginSettings.environmentId,
			apiHost: myPluginSettings.apiHost,
		});
	});
})();
