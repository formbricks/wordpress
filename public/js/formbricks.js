document.addEventListener("DOMContentLoaded", function () {
  if (
    formbricksPluginSettings &&
    formbricksPluginSettings.environmentId &&
    formbricksPluginSettings.apiHost
  ) {
    // var t = document.createElement("script");
    // t.type = "text/javascript";
    // t.async = true;
    // t.src = "formbricks-client.umd.js";
    // var e = document.getElementsByTagName("script")[0];
    // e.parentNode.insertBefore(t, e);

    window.addEventListener("load", function () {
      setTimeout(function () {
        window.formbricks.init({
          environmentId: formbricksPluginSettings.environmentId,
          apiHost: formbricksPluginSettings.apiHost,
          debug: Boolean(formbricksPluginSettings.debug),
        });
      }, 500);
    });
  }
});
