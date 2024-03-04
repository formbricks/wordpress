document.addEventListener("DOMContentLoaded", function () {
  if (formbricksPluginSettings && formbricksPluginSettings.environmentId && formbricksPluginSettings.apiHost) {
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