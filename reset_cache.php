    <?php
    // FILE: backend_core/reset_cache.php

    // Periksa apakah fungsi opcache_reset ada
    if (function_exists('opcache_reset')) {
        // Reset seluruh konten OPcache
        opcache_reset();
        echo "<h1>SUCCESS: OPcache has been reset!</h1>";
        echo "<p>Server cache has been cleared. Please try testing the notification feature again.</p>";
    } else {
        echo "<h1>WARNING: OPcache is not enabled or the opcache_reset() function is not available.</h1>";
        echo "<p>Cache could not be cleared via this script.</p>";
    }
    