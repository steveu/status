<!DOCTYPE html>
<head>
  <meta charset="UTF-8" />
</head>
<body>
<?php

/**
 * Render a very rough timeline with entities included.
 *
 * Although this example uses your user token/secret, you can use
 * the user token/secret of any user who has authorised your application.
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
 * 4) Visit this page using your web browser.
 *
 * @author themattharris
 */

date_default_timezone_set('UTC');

require 'php/tmhOAuth.php';
require 'php/tmhUtilities.php';
$tmhOAuth = new tmhOAuth(array(
  'consumer_key'    => 'TijU7s6lXjHH0J7XC4e70g',
  'consumer_secret' => '9sB69dOIPv7GM5mmG0szK3yvJ1mGRywLhJXyze0Mvc',
  'user_token'      => '17883-v71sWnKn69A2puEDRIbUgXi60U3dHBT8nYbJhXeZA8',
  'user_secret'     => 'wfJKcIKZ8ush1AOWfJl767xgfNGNq1GA2K2lwNhXKeI',
));

$code = $tmhOAuth->request('GET', $tmhOAuth->url('1/statuses/home_timeline'), array(
  'include_entities' => '1',
  'include_rts'      => '0',
  'screen_name'      => 'steveu',
  'exclude_replies'  => true,
  'count'            => 100,
));

if ($code == 200) {
  $timeline = json_decode($tmhOAuth->response['response'], true);
  foreach ($timeline as $tweet) :
    $entified_tweet = tmhUtilities::entify_with_options($tweet);
    $is_retweet = isset($tweet['retweeted_status']);

    $diff = time() - strtotime($tweet['created_at']);
    if ($diff < 60*60)
      $created_at = floor($diff/60) . ' minutes ago';
    elseif ($diff < 60*60*24)
      $created_at = floor($diff/(60*60)) . ' hours ago';
    else
      $created_at = date('d M', strtotime($tweet['created_at']));

    $permalink  = str_replace(
      array(
        '%screen_name%',
        '%id%',
        '%created_at%'
      ),
      array(
        $tweet['user']['screen_name'],
        $tweet['id_str'],
        $created_at,
      ),
      '<a href="https://twitter.com/%screen_name%/%id%">%created_at%</a>'
    );

  ?>
  <div id="<?php echo $tweet['id_str']; ?>" style="margin-bottom: 1em">
    <span>ID: <?php echo $tweet['id_str']; ?></span><br>
    <span>Orig: <?php echo $tweet['text']; ?></span><br>
    <span>Entitied: <?php echo $entified_tweet ?></span><br>
    <small><?php echo $permalink ?><?php if ($is_retweet) : ?>is retweet<?php endif; ?>
    <span>via <?php echo $tweet['source']?></span></small>
  </div>
<?php
  endforeach;
} else {
  tmhUtilities::pr($tmhOAuth->response);
}
?>
</body>
</html>