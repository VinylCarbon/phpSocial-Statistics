<?php

include_once getcwd().'/'.$CONF['plugin_path'].'/statistics/browser.php';

function statistics($values) {
    global $db;

    $browser = new BrowserDetection();

    // Verify if the user is not a robot / crawler, etc
    if ( !$browser->isRobot() ) {

        // Get user IP
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP)){
            $ip = $client;
        } elseif(filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }

        // Get referer
        if(!isset($_SESSION['org_referer'])){
            $_SESSION['org_referer'] = $_SERVER['HTTP_REFERER'];
        }

        // Strip version to single digit and remove NT from Windows 10
        $browserVersion = explode('.', $browser->getVersion());
        $browserVersion = $browserVersion[0];
        $OSVersion = ( $browser->getPlatform() == 'Windows' )? str_replace('NT ', '', $browser->getPlatformVersion(true)): $browser->getPlatformVersion(true);

        if ($values['idu'] == 0) {
            $country = 'Unknown';
        } else {
            $country = $values['country'];
        }

        $country         = htmlspecialchars( $country );
        $browserName     = $browser->getName();
        $browserName     = htmlspecialchars( $browserName );
        $browserPlatform = $browser->getPlatform();
        $browserPlatform = htmlspecialchars( $browserPlatform );
        $browserVersion  = htmlspecialchars( $browserVersion );
        $osVersion       = htmlspecialchars( $OSVersion );
        $ip              = htmlspecialchars( $ip );
        $referer         = htmlspecialchars( $_SESSION['org_referer'] );

        // Insert into MySQL
        $sql_insert = "INSERT INTO `statistics_hits`(`uid`, `country`, `os`, `os_version`, `browser`, `browser_version`, `ip`, `referer`, `gender`, `time`) VALUES ('".$values['idu']."', '".$country."', '".$browserPlatform."', '".$OSVersion."', '".$browserName."', '".$browserVersion."', '".$ip."', '".$referer."', '".$values['gender']."', NOW())";
        $result = $db->query($sql_insert);
    }
}



?>