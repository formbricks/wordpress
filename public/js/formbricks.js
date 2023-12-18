(function () {
  var t = document.createElement("script");
  t.type = "text/javascript";
  t.async = true;
  t.src = "https://unpkg.com/@formbricks/js@latest/dist/index.umd.js";
  var e = document.getElementsByTagName("script")[0];
  e.parentNode.insertBefore(t, e);

  window.addEventListener("load", function () {
    setTimeout(function () {
      window.formbricks.init({
        environmentId: myPluginSettings.environmentId,
        apiHost: myPluginSettings.apiHost,
      });
    }, 500);
  });
})();
