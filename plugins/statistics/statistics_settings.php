<?php

function percentage_modified($current, $old) {
    $old2 = ( $old == 0 )? 1: $old;
    $result = number_format((($current - $old) / $old2 * 100), 0);
    if($result < 0) {
        return '<span class="negative">'.$result.'%</span>';
    } elseif($result > 0) {
        return '<span class="positive">+'.$result.'%</span>';
    } else {
        return '<span class="neutral">0%</span>';
    }
}

function getPluginURL($url, $plugin_path){
    return $url.'/'.$plugin_path.'/statistics/';
}

function getIcon($data, $type = "browser"){
    if ($type == "browser") {
        switch ($data) {
            case 'Android':
                return 'android.png';
                break;
            case 'Chrome':
                return 'chrome.png';
                break;
            case 'Edge':
                return 'edge.png';
                break;
            case 'iCab':
                return 'firefox.png';
                break;
            case 'Internet Explorer':
                return 'internet-explorer.png';
                break;
            case 'Internet Explorer Mobile':
                return 'internet-explorer.png';
                break;
            case 'Mozilla':
                return 'firefox.png';
                break;
            case 'Firefox':
                return 'firefox.png';
                break;
            case 'Opera':
                return 'opera.png';
                break;
            case 'Opera Mini':
                return 'opera-mini.png';
                break;
            case 'Opera Mobile':
                return 'opera-mini.png';
                break;
            case 'Safari':
                return 'safari.png';
                break;
            case 'Konqueror':
                return 'konqueror.png';
                break;
            default:
                return false;
                break;
        }
    } elseif($type == "os"){
        switch ($data) {
            case 'Android':
                return 'Android.png';
                break;
            case 'BeOS':
                return 'BeOS.png';
                break;
            case 'BlackBerry':
                return 'Blackberry.png';
                break;
            case 'FreeBSD':
                return 'FreeBSD.png';
                break;
            case 'Linux':
                return 'Linux.png';
                break;
            case 'iPad':
                return 'MacOS.png';
                break;
            case 'iPhone':
                return 'MacOS.png';
                break;
            case 'iPod':
                return 'MacOS.png';
                break;
            case 'Macintosh':
                return 'MacOS2.png';
                break;
            case 'OS/2':
                return 'OS2.png';
                break;
            case 'Symbian':
                return 'Symbian.png';
                break;
            case 'Windows':
                return 'Windows.png';
                break;
            case 'Windows CE':
                return 'WindowsMobile.png';
                break;
            case 'Windows Phone':
                return 'WindowsMobile.png';
                break;
            default:
                return false;
                break;
        }
    }

}

function getGender($code){
    switch ($code) {
        case 1:
            return "Male";
            break;
        case 2:
            return "Female";
            break;
        
        default:
            return "Unknown";
            break;
    }
}

function statistics_settings() {
    global $CONF, $db;

    $output = '
        <script src="'.getPluginURL($CONF['url'], $CONF['plugin_path']).'statistics_admin.js"></script>
        <link rel="stylesheet" href="'.getPluginURL($CONF['url'], $CONF['plugin_path']).'statistics_admin.css">
    ';

    $getAdvanced = $db->real_escape_string($_GET['advanced']);
    if ($getAdvanced) {
        $date = explode('_', $getAdvanced);
        $dateDay = $date[0];
        $dateMonth = $date[1];
        $dateYear = $date[2];
        $today = date("d-n-Y");
        $date = implode('-', $date);
        if ( strtotime($today) >= strtotime($date) ) {
            $gregorianDate = gregoriantojd($dateMonth,$dateDay,$dateYear);
            $dayTitle = jdmonthname($gregorianDate, CAL_GREGORIAN).' '.$dateDay.', '.$dateYear;

            // Users Gender Informations
            $malesRegistered                 = $db->query("SELECT COUNT(idu) as cnt FROM `users` WHERE `gender`='1' AND `suspended` != 2 AND DAY(`date`) = '".$dateDay."' AND MONTH(`date`) = '".$dateMonth."' AND YEAR(`date`) = '".$dateYear."'");
            $malesRegistered                 = $malesRegistered->fetch_assoc();
            $malesRegistered                 = $malesRegistered['cnt'];
            $femalesRegistered               = $db->query("SELECT COUNT(idu) as cnt FROM `users` WHERE `gender`='2' AND `suspended` != 2 AND DAY(`date`) = '".$dateDay."' AND MONTH(`date`) = '".$dateMonth."' AND YEAR(`date`) = '".$dateYear."'");
            $femalesRegistered               = $femalesRegistered->fetch_assoc();
            $femalesRegistered               = $femalesRegistered['cnt'];
            $unknownRegistered               = $db->query("SELECT COUNT(idu) as cnt FROM `users` WHERE `gender`='0' AND `suspended` != 2 AND DAY(`date`) = '".$dateDay."' AND MONTH(`date`) = '".$dateMonth."' AND YEAR(`date`) = '".$dateYear."'");
            $unknownRegistered               = $unknownRegistered->fetch_assoc();
            $unknownRegistered               = $unknownRegistered['cnt'];
            $totalRegistered                 = $db->query("SELECT COUNT(idu) as cnt FROM users WHERE `suspended` != 2 AND DAY(`date`) = '".$dateDay."' AND MONTH(`date`) = '".$dateMonth."' AND YEAR(`date`) = '".$dateYear."'");
            $totalRegistered                 = $totalRegistered->fetch_assoc();
            $totalRegistered                 = $totalRegistered['cnt'];

            // General Informations
            $userPosts                       = $db->query("SELECT COUNT(id) as cnt FROM messages WHERE `type` != 'shared' AND DAY(`time`) = '".$dateDay."' AND MONTH(`time`) = '".$dateMonth."' AND YEAR(`time`) = '".$dateYear."'");
            $userPosts                       = $userPosts->fetch_assoc();
            $userPosts                       = $userPosts['cnt'];
            $userComments                    = $db->query("SELECT COUNT(id) as cnt FROM comments WHERE DAY(`time`) = '".$dateDay."' AND MONTH(`time`) = '".$dateMonth."' AND YEAR(`time`) = '".$dateYear."'");
            $userComments                    = $userComments->fetch_assoc();
            $userComments                    = $userComments['cnt'];
            $userShared                      = $db->query("SELECT COUNT(id) as cnt FROM messages WHERE `type` = 'shared' AND DAY(`time`) = '".$dateDay."' AND MONTH(`time`) = '".$dateMonth."' AND YEAR(`time`) = '".$dateYear."'");
            $userShared                      = $userShared->fetch_assoc();
            $userShared                      = $userShared['cnt'];
            $userLikes                       = $db->query("SELECT count(id) as cnt FROM `likes` WHERE DAY(`time`) = '".$dateDay."' AND MONTH(`time`) = '".$dateMonth."' AND YEAR(`time`) = '".$dateYear."'");
            $userLikes                       = $userLikes->fetch_assoc();
            $userLikes                       = $userLikes['cnt'];

            $output .= '
            <div class="page-inner">
                Statistics for: <strong>'.$dayTitle.'</strong>
            </div>
            <div class="message-divider"></div>
            <div class="page-inner">
                <div class="page-input-container">
                    <div><strong>Users Gender</strong></div>
                    <div class="stats-container columns">
                        Males Registered
                        <div class="stats-values">'.$malesRegistered.'</div>
                    </div>
                    <div class="stats-container columns">
                        Females Registered
                        <div class="stats-values">'.$femalesRegistered.'</div>
                    </div>
                    <div class="stats-container columns">
                        Unknown Gender
                        <div class="stats-values">'.$unknownRegistered.'</div>
                    </div>
                    <div class="stats-container columns">
                        Total Registered
                        <div class="stats-values">'.$totalRegistered.'</div>
                    </div>
                </div>
            </div>
            <div class="message-divider"></div>
            <div class="page-inner">
                <div class="page-input-container">
                    <div><strong>Website Informations</strong></div>
                    <div class="stats-container columns">
                        Posts
                        <div class="stats-values">'.$userPosts.'</div>
                    </div>
                    <div class="stats-container columns">
                        Comments Posted
                        <div class="stats-values">'.$userComments.'</div>
                    </div>
                    <div class="stats-container columns">
                        Posts Shared
                        <div class="stats-values">'.$userShared.'</div>
                    </div>
                    <div class="stats-container columns">
                        Total Likes
                        <div class="stats-values">'.$userLikes.'</div>
                    </div>
                </div>
            </div>
            <div class="message-divider"></div>
            <div class="page-inner">
                <h4 style="margin-top: 0">Browsers</h4>
                <div class="page-input-container">
                    <div class="advanced-stats">
                        <table width="100%">
                            <thead>
                                <tr>
                                    <th class="th-browser" width="30%">Browser</th>
                                    <th class="th-percentage" width="40%">Percentage</th>
                                    <th class="th-os" width="30%">OS</th>
                                </tr>
                            </thead>
                            <tbody>
            ';

            $lastVisits = $db->query("SELECT `browser`, `browser_version`, `os`, `os_version`, COUNT(*) AS cnt FROM `statistics_hits` WHERE DAY(`time`) = '".$dateDay."' AND MONTH(`time`) = '".$dateMonth."' AND YEAR(`time`) = '".$dateYear."' GROUP BY `browser` ORDER BY `cnt` DESC");
            $totalVisits = $db->query("SELECT COUNT(*) AS cnt FROM `statistics_hits` WHERE DAY(`time`) = '".$dateDay."' AND MONTH(`time`) = '".$dateMonth."' AND YEAR(`time`) = '".$dateYear."'");
            $totalVisits = $totalVisits->fetch_assoc();
            $totalVisits = $totalVisits['cnt'];
            while ( $row = $lastVisits->fetch_assoc() ) {
                $browserIcon = ( getIcon($row['browser']) )? '<img src="'.getPluginURL($CONF['url'], $CONF['plugin_path']).'/images/browsers/'.getIcon($row['browser']).'"> ': '';;
                $osIcon = ( getIcon($row['os'], 'os') )? '<img src="'.getPluginURL($CONF['url'], $CONF['plugin_path']).'/images/os/'.getIcon($row['os'], 'os').'"> ': '';

                $percentage = number_format(( $row['cnt'] * 100 ) / $totalVisits, 0);

                $output .= '
                            <tr>
                                <td>'.$browserIcon.$row['browser'].' '.$row['browser_version'].'</td>
                                <td><div class="progress-bar" title="'.$percentage.'% ('.$row['cnt'].')'.'"><div class="progress" style="width: '.$percentage.'%;"></div></div></td>
                                <td>'.$osIcon.$row['os'].' '.$row['os_version'].'</td>
                            </tr>
                ';
            }

            $output .= '
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="message-divider"></div>
            <div class="page-inner">
                <h4 style="margin-top: 0">Countries</h4>
                <div class="page-input-container">
                    <div class="advanced-stats">
                        <table width="100%">
                            <thead>
                                <tr>
                                    <th class="th-browser" width="30%">Name</th>
                                    <th class="th-os" width="40%">Percentage</th>
                                    <th class="th-date" width="30%">Visits</th>
                                </tr>
                            </thead>
                            <tbody>
            ';

            $lastVisits = $db->query("SELECT `country`, COUNT(*) AS `cnt` FROM `statistics_hits` WHERE DAY(`time`) = '".$dateDay."' AND MONTH(`time`) = '".$dateMonth."' AND YEAR(`time`) = '".$dateYear."' GROUP BY `country` ORDER BY `cnt` DESC");
            while ( $row = $lastVisits->fetch_assoc() ) {
                $country = ( empty($row['country']) )? 'Unknown': $row['country'];

                $percentage = ( $row['cnt'] * 100 ) / $totalVisits;

                $output .= '
                            <tr>
                                <td>'.$country.'</td>
                                <td><div class="progress-bar" title="'.$percentage.'%"><div class="progress" style="width: '.$percentage.'%;"></div></div></td>
                                <td>'.$row['cnt'].'</td>
                            </tr>
                ';
            }

            $output .= '
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="message-divider"></div>
            <div class="page-inner">
                <h4 style="margin-top: 0">Visitors</h4>
                <div class="page-input-container">
                    <div class="advanced-stats">
                        <table>
                            <thead>
                                <tr>
                                    <th class="th-browser" width="28%">Browser</th>
                                    <th class="th-os" width="28%">OS</th>
                                    <th class="th-date" width="28%">Date</th>
                                    <th class="th-ip" width="15%">IP</th>
                                    <th class="th-refferer" width="10%">Refferer</th>
                                </tr>
                            </thead>
                            <tbody>
            ';

            $lastVisits = $db->query("SELECT * FROM `statistics_hits` WHERE DAY(`time`) = '".$dateDay."' AND MONTH(`time`) = '".$dateMonth."' AND YEAR(`time`) = '".$dateYear."' ORDER BY `time` DESC");
            while ( $row = $lastVisits->fetch_assoc() ) {
                $country = ( empty($row['country']) )? 'Unknown': $row['country'];
                $browserIcon = ( getIcon($row['browser']) )? '<img src="'.getPluginURL($CONF['url'], $CONF['plugin_path']).'/images/browsers/'.getIcon($row['browser']).'"> ': '';;
                $osIcon = ( getIcon($row['os'], 'os') )? '<img src="'.getPluginURL($CONF['url'], $CONF['plugin_path']).'/images/os/'.getIcon($row['os'], 'os').'"> ': '';
                $refferer = ( !empty($row['referer']) )? '<a href="'.$row['referer'].'" title="'.$row['referer'].'" target="_blank">Link</a>': '';

                $output .= '
                            <tr>
                                <td>'.$browserIcon.$row['browser'].' '.$row['browser_version'].'</td>
                                <td>'.$osIcon.$row['os'].' '.$row['os_version'].'</td>
                                <td>'.$row['time'].'</td>
                                <td><span title="'.$country.'">'.$row['ip'].'</span></td>
                                <td style="text-align: center">'.$refferer.'</td>
                            </tr>
                ';
            }

            $output .= '
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            ';
        } else {
            $output .= '
            <div class="page-inner 404error">
                <div class="page-input-container">
                    <h4 style="margin-top: 0; text-align: center;">You built a time machine... out of a delorian ?</h4>
                </div>
            </div>
            ';
        }

    } else {

        // Users Gender Informations
        $malesRegistered                 = $db->query("SELECT COUNT(idu) as cnt FROM `users` WHERE `gender`='1' AND `suspended` != 2");
        $malesRegistered                 = $malesRegistered->fetch_assoc();
        $malesRegistered                 = $malesRegistered['cnt'];
        $femalesRegistered               = $db->query("SELECT COUNT(idu) as cnt FROM `users` WHERE `gender`='2' AND `suspended` != 2");
        $femalesRegistered               = $femalesRegistered->fetch_assoc();
        $femalesRegistered               = $femalesRegistered['cnt'];
        $unknownRegistered               = $db->query("SELECT COUNT(idu) as cnt FROM `users` WHERE `gender`='0' AND `suspended` != 2");
        $unknownRegistered               = $unknownRegistered->fetch_assoc();
        $unknownRegistered               = $unknownRegistered['cnt'];
        $totalRegistered                 = $db->query("SELECT COUNT(idu) as cnt FROM users WHERE `suspended` != 2");
        $totalRegistered                 = $totalRegistered->fetch_assoc();
        $totalRegistered                 = $totalRegistered['cnt'];

        // General Informations
        $userPosts                       = $db->query("SELECT COUNT(id) as cnt FROM messages WHERE `type` != 'shared'");
        $userPosts                       = $userPosts->fetch_assoc();
        $userPosts                       = $userPosts['cnt'];
        $userComments                    = $db->query("SELECT COUNT(id) as cnt FROM comments");
        $userComments                    = $userComments->fetch_assoc();
        $userComments                    = $userComments['cnt'];
        $userShared                      = $db->query("SELECT COUNT(id) as cnt FROM messages WHERE `type` = 'shared'");
        $userShared                      = $userShared->fetch_assoc();
        $userShared                      = $userShared['cnt'];
        $userLikes                       = $db->query("SELECT count(id) as cnt FROM `likes`");
        $userLikes                       = $userLikes->fetch_assoc();
        $userLikes                       = $userLikes['cnt'];

        // Countries Informations
        $popularCountry                  = $db->query("SELECT `country`, COUNT(`country`) AS cnt FROM `users` GROUP BY `country` ORDER BY cnt DESC");
        $popularCountry                  = $popularCountry->fetch_assoc();
        $malesCountry                    = $db->query("SELECT `country`, COUNT(`country`) AS cnt FROM `users` WHERE `gender`='1' GROUP BY `country` ORDER BY cnt DESC");
        $malesCountry                    = $malesCountry->fetch_assoc();
        $femalesCountry                  = $db->query("SELECT `country`, COUNT(`country`) AS cnt FROM `users` WHERE `gender`='2' GROUP BY `country` ORDER BY cnt DESC");
        $femalesCountry                  = $femalesCountry->fetch_assoc();
        $noOfCountry                     = $db->query("SELECT 'idu' FROM `users` GROUP BY `country`");
        $noOfCountry                     = $noOfCountry->num_rows;

        // Browser & OS Informations
        $popularBrowser                  = $db->query("SELECT `browser`, COUNT(`browser`) AS cnt FROM `statistics_hits` GROUP BY `browser` ORDER BY cnt DESC");
        $popularBrowser                  = $popularBrowser->fetch_assoc();
        $popularBrowserVersion           = $db->query("SELECT `browser_version`, COUNT(`browser`) AS cnt FROM `statistics_hits` WHERE `browser`='".$popularBrowser['browser']."' GROUP BY `browser` ORDER BY cnt DESC");
        $popularBrowserVersion           = $popularBrowserVersion->fetch_assoc();
        $popularOS                       = $db->query("SELECT `os`, COUNT(`os`) AS cnt FROM `statistics_hits` GROUP BY `os` ORDER BY cnt DESC");
        $popularOS                       = $popularOS->fetch_assoc();
        $popularOSVersion                = $db->query("SELECT `os_version`, COUNT(`os`) AS cnt FROM `statistics_hits` WHERE `os`='".$popularOS['os']."' GROUP BY `os` ORDER BY cnt DESC");
        $popularOSVersion                = $popularOSVersion->fetch_assoc();

        $chartCountries                  = $db->query("SELECT `country`, COUNT(`country`) AS cnt FROM `users` GROUP BY `country` ORDER BY cnt DESC LIMIT 0,5");
        $chartCountriesListNames         = array();
        $chartCountriesListValues        = array();
        $chartCountriesColors            = '"rgba(54, 162, 235, 1)","#C9F73E","rgba(255, 99, 132, 1)","#F5B833","#FF2626",';

        while ($chartCountry = $chartCountries->fetch_assoc() ) {
            $chartCountriesListNames[] = "'".$chartCountry['country']."'";
            $chartCountriesListValues[] = $chartCountry['cnt'];
        }

        $chartBrowsers                  = $db->query("SELECT `browser`, COUNT(*) AS cnt FROM `statistics_hits` GROUP BY `browser` ORDER BY cnt DESC LIMIT 0,5");
        $chartBrowsersListNames         = array();
        $chartBrowsersListValues        = array();
        $chartBrowsersColors            = '"rgba(54, 162, 235, 1)","#C9F73E","rgba(255, 99, 132, 1)","#F5B833","#FF2626",';
        while ($chartBrowser = $chartBrowsers->fetch_assoc() ) {
            $chartBrowsersListNames[] = "'".$chartBrowser['browser']."'";
            $chartBrowsersListValues[] = $chartBrowser['cnt'];
        }

        // Filter
        $sqlYears = $db->query("SELECT YEAR(`time`) FROM `statistics_hits` GROUP BY YEAR(`time`) ORDER BY YEAR(`time`) ASC");
        $sqlYears = $sqlYears->fetch_assoc();

        $output .= '
        <script>
        $(document).ready(function(){
            if(window.location.hash) {
                $(".tab-item").hide();
                $(window.location.hash).addClass("edit-menu-item-active").siblings().removeClass("edit-menu-item-active");
                $("."+window.location.hash.substring(1)).show();
                if (window.location.hash != "#tab-visits") {
                    $(".404error").remove();
                }
            }
            $(".edit-menu-item").unbind();
            $(".edit-menu-item").on("click", function() {
                $(".tab-item").hide();
                $(this).addClass("edit-menu-item-active").siblings().removeClass("edit-menu-item-active");
                $("."+$(this).attr("id")).show();
                window.location.hash = $(this).attr("id");
                $(".404error").remove();
            });
        });
        </script>
        <div class="page-inner" style="padding-bottom: 0; padding-top: 0;">
            <div class="edit-menu">
                <div class="edit-menu-item edit-menu-item-active menu-item" id="stats-general">General</div>
                <div class="edit-menu-item menu-item" id="stats-last">Visits</div>
                <div class="edit-menu-item menu-item" id="stats-charts">Charts</div>
            </div>
        </div>
        <div class="tab-item stats-general" style="display: block">
            <div class="page-inner">
                <h4 style="margin-top: 0">Registered Users Informations</h4>
                <div class="page-input-container">
                    <div><strong>Users Gender</strong></div>
                    <div class="stats-container columns">
                        Males Registered
                        <div class="stats-values">'.$malesRegistered.'</div>
                    </div>
                    <div class="stats-container columns">
                        Females Registered
                        <div class="stats-values">'.$femalesRegistered.'</div>
                    </div>
                    <div class="stats-container columns">
                        Unknown Gender
                        <div class="stats-values">'.$unknownRegistered.'</div>
                    </div>
                    <div class="stats-container columns">
                        Total Registered
                        <div class="stats-values">'.$totalRegistered.'</div>
                    </div>
                </div>
            </div>
            <div class="message-divider"></div>
            <div class="page-inner">
                <div class="page-input-container">
                    <div><strong>Country</strong></div>
                    <div class="stats-container columns">
                        Most Popular
                        <div class="stats-values">'.$popularCountry['country'].'</div>
                    </div>
                    <div class="stats-container columns">
                        Most Males
                        <div class="stats-values">'.$malesCountry['country'].'</div>
                    </div>
                    <div class="stats-container columns">
                        Most Females
                        <div class="stats-values">'.$femalesCountry['country'].'</div>
                    </div>
                    <div class="stats-container columns">
                        Different Countries
                        <div class="stats-values">'.$noOfCountry.'</div>
                    </div>
                </div>
            </div>
            <div class="message-divider"></div>
            <div class="page-inner">
                <h4 style="margin-top: 0">Website Informations</h4>
                <div class="page-input-container">
                    <div class="stats-container columns">
                        Posts
                        <div class="stats-values">'.$userPosts.'</div>
                    </div>
                    <div class="stats-container columns">
                        Comments Posted
                        <div class="stats-values">'.$userComments.'</div>
                    </div>
                    <div class="stats-container columns">
                        Posts Shared
                        <div class="stats-values">'.$userShared.'</div>
                    </div>
                    <div class="stats-container columns">
                        Total Likes
                        <div class="stats-values">'.$userLikes.'</div>
                    </div>
                </div>
            </div>
            <div class="message-divider"></div>
            <div class="page-inner">
                <h4 style="margin-top: 0">Visitors Informations</h4>
                <div class="page-input-container">
                    <div><strong>Popular Browsers & Operating Systems</strong></div>
                    <div class="stats-container columns">
                        Browser
                        <div class="stats-values">'.$popularBrowser['browser'].'</div>
                    </div>
                    <div class="stats-container columns">
                        Browser Version
                        <div class="stats-values">'.$popularBrowserVersion['browser_version'].'</div>
                    </div>
                    <div class="stats-container columns">
                        OS (Operating System)
                        <div class="stats-values">'.$popularOS['os'].'</div>
                    </div>
                    <div class="stats-container columns">
                        OS Version
                        <div class="stats-values">'.$popularOSVersion['os_version'].'</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-item stats-last" style="display: none">
            <div class="page-inner">
                <div class="page-input-container">
                    <strong class="filter-title">Last 10 Visits</strong>
                    <form action="'.$CONF['url'].'/index.php?a=admin&b=plugins&settings='.$_GET['settings'].'#stats-last" id="filter-form" method="POST">
                        <select name="filter_month">
                            <option value="-" selected disabled>Month</option>
        ';
            $mons = array(1 => "January", 2 => "February", 3 => "March", 4 => "April", 5 => "May", 6 => "June", 7 => "July", 8 => "August", 9 => "September", 10 => "October", 11 => "November", 12 => "December");
            for ($i=01; $i <= 12; $i++) {
                $monthNo = ($i < 10)? '0'.$i: $i;
                $selected = ($_POST['filter_month'] == $i)? ' selected': '';
                $output .= '<option value="'.$i.'"'.$selected.'>'.$mons[$i].'</option>';
            }

        $output .= '
                        </select>
                        <select name="filter_year">
                            <option value="-" selected disabled>Year</option>
        ';
            foreach ($sqlYears as $year) {
                $selected = ($_POST['filter_year'] == $year)? ' selected': '';
                $output .= '<option value="'.$year.'"'.$selected.'>'.$year.'</option>';
            }    

        $output .= '
                        </select>
                        <input type="submit" value="Filter">
                    </form>
                </div>
                <table class="table-last-visits" width="100%">
        ';

        if ($_POST['filter_month'] && ($_POST['filter_month'] || empty($_POST['filter_month']) ) ) {
            $month = ( !empty($_POST['filter_month']) )? $_POST['filter_month']: date("n");
            $year = ( !empty($_POST['filter_year']) )? $_POST['filter_year']: date("Y");
            $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

            $month_temp = ($month == '01')? 12: $month;
            $year_temp = ($month == '01')? $year-1: $year;
            $daysInMonth_last = cal_days_in_month(CAL_GREGORIAN, $month_temp, $year_temp);
            $temp = $db->query("SELECT COUNT(`id`) AS cnt FROM `statistics_hits` WHERE DAY(`time`) = '".$i."' AND MONTH(`time`) = '".$month_temp."' AND YEAR(`time`) = '".$year."'");
            $temp = $temp->fetch_assoc();
            $temp = $temp['cnt'];

            for ($i=1; $i <= $daysInMonth; $i++) {
                $date = ( $i < 10 )? '0'.$i.'_'.$month.'_'.$year: $i.'_'.$month.'_'.$year;
                $day = ( $i < 10 )? '0'.$i : $i;
                $gregorianDate = gregoriantojd($month,$day,$year);

                $views = $db->query("SELECT COUNT(`id`) AS cnt FROM `statistics_hits` WHERE DAY(`time`) = '".$i."' AND MONTH(`time`) = '".$month."' AND YEAR(`time`) = '".$year."'");
                $views = $views->fetch_assoc();
                $views = $views['cnt'];

                if ( date('n') > $month || (date('d') >= $day && date('n') >= $month) ) {
                    $percentage = percentage_modified($views, $temp);
                    $views_temp = $views;
                    $link = '<a href="'.$CONF['url'].'/index.php?a=admin&b=plugins&settings='.$_GET['settings'].'&advanced='.$date.'#stats-advanced">More Statistics</a>';
                    $views = ' - '.$views.' views';
                    $percentage = ' - <span class="filter-percentage">'.$percentage.'</span>';
                } else {
                    $link = '';
                    $views = '';
                    $percentage = '';
                }

                $output .= '
                <tr>
                    <td colspan="5" class="filter-month-day"><strong>'.jdmonthname($gregorianDate, 1).' '.$day.', '.$year.'</strong>'.$views.$percentage.$link.'</td>
                </tr>
                ';

                $temp = $views_temp;
            }
        } else {

            $output .= '
                    <thead>
                        <tr>
                            <th class="th-browser" width="28%">Browser</th>
                            <th class="th-os" width="28%">OS</th>
                            <th class="th-date" width="28%">Date</th>
                            <th class="th-ip" width="15%">IP</th>
                            <th class="th-refferer" width="10%">Refferer</th>
                        </tr>
                    </thead>
                    <tbody>
            ';

            $lastVisits = $db->query("SELECT * FROM `statistics_hits` ORDER BY `time` DESC LIMIT 0,10");
            while ( $row = $lastVisits->fetch_assoc() ) {
                $country = ( empty($row['country']) )? 'Unknown': $row['country'];
                $browserIcon = ( getIcon($row['browser']) )? '<img src="'.getPluginURL($CONF['url'], $CONF['plugin_path']).'/images/browsers/'.getIcon($row['browser']).'"> ': '';;
                $osIcon = ( getIcon($row['os'], 'os') )? '<img src="'.getPluginURL($CONF['url'], $CONF['plugin_path']).'/images/os/'.getIcon($row['os'], 'os').'"> ': '';
                $refferer = ( !empty($row['referer']) )? '<a href="'.$row['referer'].'" title="'.$row['referer'].'" target="_blank">Link</a>': '';

                $output .= '
                            <tr>
                                <td>'.$browserIcon.$row['browser'].' '.$row['browser_version'].'</td>
                                <td>'.$osIcon.$row['os'].' '.$row['os_version'].'</td>
                                <td>'.$row['time'].'</td>
                                <td><span title="'.$country.'">'.$row['ip'].'</span></td>
                                <td style="text-align: center">'.$refferer.'</td>
                            </tr>
                ';
            }

        }

        $output .= '
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-item stats-charts" style="display: none">
            <div class="page-inner">
                <h4 style="margin-top: 0">Registered Users Charts</h4>
                <div><strong>Users Gender</strong></div>
                <canvas id="chart-gender" width="700" height="400"></canvas>

                <h4>Added & Shared Posts</h4>
                <canvas id="chart-posts-shared" width="700" height="400"></canvas>

                <h4>TOP 5 Countries</h4>
                <canvas id="chart-top-countries" width="700" height="400"></canvas>

                <h4>TOP 5 Browsers</h4>
                <canvas id="chart-top-browsers" width="700" height="400"></canvas>
            </div>
        </div>
        ';

        // Charts
        $output .= '
        
        <!-- Users Gender -->
        <script>
            var ctx = jQuery("#chart-gender");
            var chartGender = new Chart(ctx, {
                type: "pie",
                data: {
                    labels: ["Females", "Males", "Unknown"],
                    datasets: [{
                        label: "Total Users",
                        data: ['.$femalesRegistered.', '.$malesRegistered.', '.$unknownRegistered.'],
                        backgroundColor: [
                            "rgba(255, 99, 132, 1)",
                            "rgba(54, 162, 235, 1)",
                            "grey",
                        ]
                    }]
                },
                options: {
                    tooltips: {
                        mode: "label"
                    }
                }
            });
        </script>

        <!-- Added & Shared Posts -->
        <script>
            var ctx = jQuery("#chart-posts-shared");
            var chartPostsShared = new Chart(ctx, {
                type: "pie",
                data: {
                    labels: ["Published Posts", "Shared Posts"],
                    datasets: [{
                        label: "Total Posts",
                        data: ['.$userPosts.', '.$userShared.'],
                        backgroundColor: [
                            "grey",
                            "rgba(54, 162, 235, 1)",
                        ]
                    }]
                },
                options: {
                    tooltips: {
                        mode: "label"
                    }
                }
            });
        </script>

        <!-- TOP 5 Countries -->
        <script>
            var ctx = jQuery("#chart-top-countries");
            var chart_top_countries = new Chart(ctx, {
                type: "pie",
                data: {
                    labels: ['.implode(',',$chartCountriesListNames).'],
                    datasets: [{
                        label: "TOP 5 Countries",
                        data: ['.implode(',',$chartCountriesListValues).'],
                        backgroundColor: ['.$chartCountriesColors.']
                    }]
                },
                options: {
                    tooltips: {
                        mode: "label"
                    }
                }
            });
        </script>

        <!-- TOP 5 Browsers -->
        <script>
            var ctx = jQuery("#chart-top-browsers");
            var chart_top_browsers = new Chart(ctx, {
                type: "pie",
                data: {
                    labels: ['.implode(',',$chartBrowsersListNames).'],
                    datasets: [{
                        label: "TOP 5 Browsers",
                        data: ['.implode(',',$chartBrowsersListValues).'],
                        backgroundColor: ['.$chartBrowsersColors.']
                    }]
                },
                options: {
                    tooltips: {
                        mode: "label"
                    }
                }
            });
        </script>
        ';

    }

    return $output;
}

?>