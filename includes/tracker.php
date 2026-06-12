<?php
/**
 * includes/tracker.php
 *
 * Visitor / session tracking stub.
 * Generates a persistent session ID stored in localStorage so that
 * cart.php can attach it to log entries for analytics purposes.
 * No external requests are made — all tracking is local.
 */
?>
<script>
(function() {
    'use strict';
    var key = 'tracker_session_id';
    var sid = localStorage.getItem(key);
    if (!sid) {
        sid = 'sid_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
        localStorage.setItem(key, sid);
    }
    // Expose for use by other scripts on the same page
    window.__trackerSessionId = sid;
})();
</script>
