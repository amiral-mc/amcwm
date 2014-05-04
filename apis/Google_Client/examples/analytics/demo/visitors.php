<?php
require_once 'src/Google_Client.php';
require_once 'src/contrib/Google_AnalyticsService.php';
require_once 'storage.php';
require_once 'authHelper.php';

        const REDIRECT_URL = 'http://kolelmala3eb.net/gapic/';
        const CLIENT_ID = '359337324430.apps.googleusercontent.com';
        const CLIENT_SECRET = 'aJfot90aHrMCOfhucoVz9XQs';
        const THIS_PAGE = 'visitors.php';
        const APP_NAME = 'Website visitors';
        const ANALYTICS_SCOPE = 'https://www.googleapis.com/auth/analytics.readonly';

$client = new Google_Client();
$client->setClientId(CLIENT_ID);
$client->setClientSecret(CLIENT_SECRET);
$client->setRedirectUri(REDIRECT_URL);
$client->setApplicationName(APP_NAME);
$client->setScopes(array(ANALYTICS_SCOPE));
$client->setDeveloperKey('AIzaSyAEtsUzgT7j_CgFN-0RWhIPPdaNlyXN-dA');
$client->getIo()->setOptions(array(CURLOPT_PROXY => '101.101.1.2', CURLOPT_PROXYPORT => '8080'));
// Magic. Returns objects from the Analytics Service
// instead of associative arrays.
$client->setUseObjects(true);
// Build a new storage object to handle and store tokens in sessions.
// Create a new storage object to persist the tokens across sessions.
$storage = new apiFileStorage();
$authHelper = new AuthHelper($client, $storage, THIS_PAGE);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title><?php echo APP_NAME; ?></title>
    </head>
    <body>
        <?php
        $authHelper->setTokenFromStorage();
        if ($authHelper->isAuthorized()) {
            $analytics = new Google_AnalyticsService($client);

            /**
             * Platform / Device
             * 1- di:->ga:browser,ga:browserVersion,ga:operatingSystem,ga:operatingSystemVersion
             *    met:->ga:visitors,ga:newVisits,ga:visits,ga:bounces,ga:visitBounceRate,ga:timeOnSite
             * Traffic Sources
             * 2- di->ga:referralPath,ga:source,ga:keyword
             *    met:->ga:visitors,ga:newVisits,ga:pageviews,ga:timeOnPage,ga:pageviewsPerVisit,ga:avgTimeOnPage
             * 
             */
            try {
                $optParams = array(
                    'dimensions' => 'ga:day,ga:visitorType',
                    'sort' => 'ga:day',
                    'max-results' => '50');
                $results = $analytics->data_ga->get(
                        urldecode('ga:52314655'), '2013-05-27', '2013-05-27',
//                        'ga:visits', 
                        'ga:visits,ga:visitors,ga:newVisits,ga:visitBounceRate,ga:bounces,ga:pageviews', $optParams);

                $rowCount = count($results->getRows());
                $totalResults = $results->getTotalResults();

                $table = '<h3>Rows Of Data</h3>';

                if (count($results->getRows()) > 0) {
                    $table .= '<table>';

                    // Print headers.
                    $table .= '<tr>';
                    foreach ($results->getColumnHeaders() as $header) {
                        $table .= '<th>' . $header->name . '</th>';
                    }
                    $table .= '</tr>';

                    // Print table rows.
                    foreach ($results->getRows() as $k1 => $row) {
                        $table .= '<tr>';
                        foreach ($row as $k => $cell) {
                            $table .= '<td>'
                                    . htmlspecialchars($cell, ENT_NOQUOTES)
                                    . '</td>';
                        }
                        $table .= '</tr>';
                    }
                    $table .= '</table>';
                } else {
                    $table .= '<p>No results found.</p>';
                }
                echo $table;
//                $output .= $this->getFormattedResults($results);
            } catch (Google_ServiceException $e) {
                echo $e->getMessage();
            }

//            $demo = new CoreReportingApiReference($analytics, THIS_PAGE);
//            echo $demo->getHtmlOutput('ga:52314655');
//            echo $demo->getError();

            $storage->set($client->getAccessToken());
        }
        ?>
    </body>
</html>
