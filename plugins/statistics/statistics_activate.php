<?php



function statistics_activate() {

    global $db;

    // Hits / visits
    $db->query("CREATE TABLE IF NOT EXISTS `statistics_hits` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `uid` int(1) COLLATE utf8_unicode_ci NOT NULL,
                `country` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
                `os` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
                `os_version` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
                `browser` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
                `browser_version` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
                `ip` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
                `referer` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
                `gender` int(1) COLLATE utf8_unicode_ci NOT NULL,
                `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY `id` (`id`)
               ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1;");


}


?>