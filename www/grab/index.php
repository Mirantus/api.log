<?
require __DIR__ . '/../../vendor/autoload.php';
require __DIR__ . '/../../app/Tweets.php';

$settings = array(
    'oauth_access_token' => "3999197043-pHPoIjg0K07XA1vzZgoq4cFmkNVlNRtJwXptwfl",
    'oauth_access_token_secret' => "jRvg9Fmwi1LmVidi4ZfKNLksRYhGgK1DJW9EqHeHHSApr",
    'consumer_key' => "vChRaSa95ajzFtJgdixdzcXX2",
    'consumer_secret' => "vRT7ljkiY7sieE45vsIZrzSiGkWn8yOvapXnUhPa15e6KNIis3"
);

$url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
$requestMethod = 'GET';

$twitter = new TwitterAPIExchange($settings);

$response = $twitter->setGetfield('?count=200&screen_name=mirantus')->buildOauth($url, $requestMethod)->performRequest();

$tweets = json_decode($response, true);

$response = $twitter->setGetfield('?count=200&max_id=' . end($tweets)['id_str'] . '&screen_name=mirantus')->buildOauth($url, $requestMethod)->performRequest();

$tweets = array_reverse(array_merge($tweets, json_decode($response, true)));

Tweets::createConnection('localhost', 'root', '', 'tweets', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''));

foreach($tweets as $tweet) {
    $text = $tweet['text'];

    if (strpos($text, 'http') !== false) {
        continue;
    }

    $row = new Tweets;
    $row->text = $text;
    $row->date = date('Y-m-d', strtotime($tweet['created_at']));
    $row->save();
}
