<?php

/**
 * Very basic Site Streams example. In production you would store the
 * received tweets in a queue or database for later processing.
 *
 * Site Streams require you to use the user token/secret of the owner
 * of the application.
 *
 * Instructions:
 * 1) If you don't have one already, create a Twitter application on
 *      https://dev.twitter.com/apps
 * 2) From the application details page copy the consumer key and consumer
 *      secret into the place in this code marked with (YOUR_CONSUMER_KEY
 *      and YOUR_CONSUMER_SECRET)
 * 3) From the application details page copy the access token and access token
 *      secret into the place in this code marked with (A_USER_TOKEN
 *      and A_USER_SECRET)
 * 4) In a terminal or server type:
 *      php /path/to/here/sitestream.php
 * 5) To stop the Streaming API either press CTRL-C or, in the folder the
 *      script is running from type:
 *      touch STOP
 *
 * @author themattharris
 */

function my_streaming_callback($data, $length, $metrics) {
  echo $data .PHP_EOL;
  return file_exists(dirname(__FILE__) . '/STOP');
}

require 'php/tmhOAuth.php';
require 'php/tmhUtilities.php';
$tmhOAuth = new tmhOAuth(array(
  'consumer_key'    => 'TijU7s6lXjHH0J7XC4e70g',
  'consumer_secret' => '9sB69dOIPv7GM5mmG0szK3yvJ1mGRywLhJXyze0Mvc',
  'user_token'      => '17883-v71sWnKn69A2puEDRIbUgXi60U3dHBT8nYbJhXeZA8',
  'user_secret'     => 'wfJKcIKZ8ush1AOWfJl767xgfNGNq1GA2K2lwNhXKeI',
));

$method = "https://sitestream.twitter.com/2b/site.json";
$params = array(
  // comma seperated list of user_ids who have authorised your application through OAuth
  'follow' => '17883'
);
$tmhOAuth->streaming_request('POST', $method, $params, 'my_streaming_callback');

// output any response we get back AFTER the Stream has stopped -- or it errors
tmhUtilities::pr($tmhOAuth);
?>

