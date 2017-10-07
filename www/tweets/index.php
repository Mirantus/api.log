<?
    require '../../vendor/autoload.php';
    require '../../app/Tweets.php';

    Tweets::connect();
    $tweets = Tweets::all();
    $result = [];
    foreach ($tweets as $tweet) {
        $result[] = $tweet->data;
    }

    header("Access-Control-Allow-Origin: *");
    echo json_encode($result);
