(function() {
  'use strict';

  Drupal.AjaxCommands.prototype.PageReload = function() {
    if (window.history.replaceState)
      window.history.replaceState(null, null, window.location.href);
    window.location = window.location.href;
  };
})(window.location);
